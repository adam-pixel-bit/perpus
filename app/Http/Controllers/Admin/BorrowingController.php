<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Book;
use App\Models\Borrowing;
use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class BorrowingController extends Controller
{
    /**
     * Display a listing of all borrowings with filters and search.
     */
    public function index(Request $request): View
    {
        $status = $request->query('status');
        $search = $request->query('search');
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        $query = Borrowing::with(['user', 'book', 'approver', 'receiver']);

        if ($status && $status !== 'all') {
            if ($status === 'overdue') {
                $query->where('status', 'borrowed')
                    ->where('return_due_date', '<', Carbon::today());
            } else {
                $query->where('status', $status);
            }
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('borrowing_code', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($uq) use ($search) {
                        $uq->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%");
                    })
                    ->orWhereHas('book', function ($bq) use ($search) {
                        $bq->where('title', 'like', "%{$search}%")->orWhere('isbn', 'like', "%{$search}%");
                    });
            });
        }

        if ($startDate) {
            $query->whereDate('borrow_date', '>=', $startDate);
        }

        if ($endDate) {
            $query->whereDate('borrow_date', '<=', $endDate);
        }

        $borrowings = $query->latest()->paginate(15)->withQueryString();

        // Statistics for summary tabs
        $pendingCount = Borrowing::where('status', 'pending')->count();
        $borrowedCount = Borrowing::where('status', 'borrowed')->count();
        $overdueCount = Borrowing::where('status', 'borrowed')->where('return_due_date', '<', Carbon::today())->count();
        $returnedCount = Borrowing::where('status', 'returned')->count();

        return view('admin.borrowings.index', compact(
            'borrowings',
            'status',
            'search',
            'startDate',
            'endDate',
            'pendingCount',
            'borrowedCount',
            'overdueCount',
            'returnedCount'
        ));
    }

    /**
     * Approve a pending borrowing request.
     */
    public function approve(Borrowing $borrowing): RedirectResponse
    {
        if ($borrowing->status !== 'pending') {
            return back()->with('error', 'Hanya permintaan dengan status menunggu yang dapat disetujui.');
        }

        try {
            DB::transaction(function () use ($borrowing) {
                // Lock the book to safely check and decrement stock
                $book = Book::where('id', $borrowing->book_id)->lockForUpdate()->firstOrFail();

                if ($book->available_stock < 1) {
                    throw new \Exception("Stok buku '{$book->title}' sedang tidak tersedia (habis). Permintaan tidak dapat disetujui.");
                }

                // Decrement available stock
                $book->available_stock -= 1;
                $book->is_available = $book->available_stock > 0;
                $book->save();

                // Update borrowing record
                $borrowing->update([
                    'status' => 'borrowed',
                    'borrow_date' => Carbon::today(),
                    'approved_by' => Auth::id(),
                ]);

                // Create notification for the user
                Notification::create([
                    'user_id' => $borrowing->user_id,
                    'title' => 'Peminjaman Disetujui',
                    'message' => "Pengajuan peminjaman buku '{$book->title}' telah disetujui. Harap mengembalikan sebelum tanggal {$borrowing->return_due_date->format('d M Y')}.",
                    'type' => 'success',
                    'link' => route('user.borrowings.index'),
                ]);

                // Record Audit Log
                AuditLog::record('approve_borrowing', "Menyetujui peminjaman {$borrowing->borrowing_code} ('{$book->title}') untuk anggota {$borrowing->user->name}.", $borrowing);
            });

            return back()->with('success', "Peminjaman dengan kode {$borrowing->borrowing_code} berhasil disetujui dan stok buku telah diperbarui.");
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Reject a pending borrowing request.
     */
    public function reject(Request $request, Borrowing $borrowing): RedirectResponse
    {
        if ($borrowing->status !== 'pending') {
            return back()->with('error', 'Hanya permintaan dengan status menunggu yang dapat ditolak.');
        }

        $validated = $request->validate([
            'admin_note' => ['nullable', 'string', 'max:500'],
        ]);

        $borrowing->update([
            'status' => 'rejected',
            'admin_note' => $validated['admin_note'] ?? 'Pengajuan peminjaman ditolak oleh Administrator perpustakaan.',
            'approved_by' => Auth::id(),
        ]);

        Notification::create([
            'user_id' => $borrowing->user_id,
            'title' => 'Peminjaman Ditolak',
            'message' => "Pengajuan peminjaman buku '{$borrowing->book->title}' ditolak. Alasan: ".($validated['admin_note'] ?? 'Tidak memenuhi syarat.'),
            'type' => 'danger',
            'link' => route('user.borrowings.index'),
        ]);

        AuditLog::record('reject_borrowing', "Menolak permohonan peminjaman {$borrowing->borrowing_code} ('{$borrowing->book->title}') anggota {$borrowing->user->name}.", $borrowing);

        return back()->with('success', "Pengajuan peminjaman {$borrowing->borrowing_code} telah ditolak.");
    }

    /**
     * Process book return.
     */
    public function returnBook(Request $request, Borrowing $borrowing): RedirectResponse
    {
        if (! in_array($borrowing->status, ['borrowed', 'approved'])) {
            return back()->with('error', 'Hanya buku yang sedang dipinjam yang dapat diproses pengembaliannya.');
        }

        $validated = $request->validate([
            'admin_note' => ['nullable', 'string', 'max:500'],
            'fines' => ['nullable', 'numeric', 'min:0'],
        ]);

        try {
            DB::transaction(function () use ($borrowing, $validated) {
                // Lock book to restore stock
                $book = Book::where('id', $borrowing->book_id)->lockForUpdate()->firstOrFail();

                // Increment stock, never exceeding total stock
                if ($book->available_stock < $book->stock) {
                    $book->available_stock += 1;
                }
                $book->is_available = true;
                $book->save();

                $actualDate = Carbon::today();
                $fineAmount = $validated['fines'] ?? 0;

                // Auto calculate fines if not manually set and overdue (Rp 2.000 / day)
                if (empty($validated['fines']) && $actualDate > $borrowing->return_due_date) {
                    $daysLate = $borrowing->return_due_date->diffInDays($actualDate);
                    $fineAmount = $daysLate * 2000;
                }

                $borrowing->update([
                    'status' => 'returned',
                    'actual_return_date' => $actualDate,
                    'fines' => $fineAmount,
                    'admin_note' => $validated['admin_note'] ?? 'Buku telah diterima kembali oleh staf perpustakaan.',
                    'returned_to' => Auth::id(),
                ]);

                // Create notification
                Notification::create([
                    'user_id' => $borrowing->user_id,
                    'title' => 'Pengembalian Buku Selesai',
                    'message' => "Buku '{$book->title}' telah berhasil dikembalikan.".($fineAmount > 0 ? ' Denda keterlambatan: Rp '.number_format($fineAmount, 0, ',', '.') : ''),
                    'type' => 'info',
                    'link' => route('user.borrowings.index'),
                ]);

                // Record Audit Log
                AuditLog::record('return_book', "Menerima pengembalian buku {$borrowing->borrowing_code} ('{$book->title}') dari {$borrowing->user->name}.".($fineAmount > 0 ? " Denda: Rp {$fineAmount}" : ''), $borrowing);
            });

            return back()->with('success', "Pengembalian buku {$borrowing->book->title} berhasil dicatat!");
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memproses pengembalian: '.$e->getMessage());
        }
    }

    /**
     * Trigger manual calculation of overdue fines.
     */
    public function runFinesCalculation(): RedirectResponse
    {
        Artisan::call('perpus:calculate-fines');

        return back()->with('success', 'Kalkulasi denda dan pengingat jatuh tempo berhasil dieksekusi secara otomatis!');
    }
}
