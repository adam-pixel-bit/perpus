<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\BorrowBookRequest;
use App\Models\Book;
use App\Models\Borrowing;
use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class BorrowingController extends Controller
{
    /**
     * Display member's borrowing history with statuses.
     */
    public function index(Request $request): View
    {
        $status = $request->query('status');
        $userId = Auth::id();

        $query = Borrowing::with('book.category')
            ->where('user_id', $userId);

        if ($status && $status !== 'all') {
            if ($status === 'overdue') {
                $query->where('status', 'borrowed')
                    ->where('return_due_date', '<', Carbon::today());
            } else {
                $query->where('status', $status);
            }
        }

        $borrowings = $query->latest()->paginate(10)->withQueryString();

        return view('user.borrowings.index', compact('borrowings', 'status'));
    }

    /**
     * Submit a new borrowing request.
     */
    public function store(BorrowBookRequest $request): RedirectResponse
    {
        $userId = Auth::id();
        $bookId = $request->input('book_id');
        $durationDays = (int) $request->input('duration_days', 7);
        $notes = $request->input('notes');

        $book = Book::findOrFail($bookId);

        // Business Rule 1: Book must have stock
        if ($book->available_stock < 1 || ! $book->is_available) {
            return back()->with('error', 'Maaf, stok buku ini sedang habis atau tidak tersedia untuk dipinjam saat ini.');
        }

        // Business Rule 2: Limit active borrowings per user (max 3 active books)
        $activeBorrowingsCount = Borrowing::where('user_id', $userId)
            ->whereIn('status', ['pending', 'approved', 'borrowed'])
            ->count();

        if ($activeBorrowingsCount >= 3) {
            return back()->with('error', 'Anda telah mencapai batas maksimal peminjaman aktif (maksimal 3 buku). Silakan kembalikan buku yang dipinjam terlebih dahulu.');
        }

        // Business Rule 3: Cannot borrow the exact same book if already pending or borrowed
        $alreadyBorrowed = Borrowing::where('user_id', $userId)
            ->where('book_id', $bookId)
            ->whereIn('status', ['pending', 'approved', 'borrowed'])
            ->exists();

        if ($alreadyBorrowed) {
            return back()->with('error', 'Anda sudah memiliki permohonan aktif atau sedang meminjam buku ini.');
        }

        $borrowDate = Carbon::today();
        $returnDueDate = Carbon::today()->addDays($durationDays);
        $borrowingCode = 'BRW-'.date('Ym').'-'.strtoupper(Str::random(5));

        Borrowing::create([
            'user_id' => $userId,
            'book_id' => $bookId,
            'borrowing_code' => $borrowingCode,
            'borrow_date' => $borrowDate,
            'return_due_date' => $returnDueDate,
            'status' => 'pending',
            'notes' => $notes,
        ]);

        Notification::create([
            'user_id' => $userId,
            'title' => 'Pengajuan Peminjaman Terkirim',
            'message' => "Pengajuan peminjaman buku '{$book->title}' (Kode: {$borrowingCode}) telah dikirim dan sedang menunggu persetujuan Admin.",
            'type' => 'info',
            'link' => route('user.borrowings.index'),
        ]);

        return redirect()->route('user.borrowings.index')
            ->with('success', "Pengajuan peminjaman buku '{$book->title}' berhasil dikirim! Silakan pantau status pengajuan Anda.");
    }

    /**
     * Cancel a pending borrowing request.
     */
    public function cancel(Borrowing $borrowing): RedirectResponse
    {
        // Business Rule: Ensure user owns this transaction
        if ($borrowing->user_id !== Auth::id()) {
            abort(403, 'Anda tidak berhak mengakses transaksi ini.');
        }

        if ($borrowing->status !== 'pending') {
            return back()->with('error', 'Hanya pengajuan dengan status menunggu persetujuan yang dapat dibatalkan.');
        }

        $bookTitle = $borrowing->book->title;
        $borrowing->delete();

        return back()->with('success', "Pengajuan peminjaman buku '{$bookTitle}' berhasil dibatalkan.");
    }
}
