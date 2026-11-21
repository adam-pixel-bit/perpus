<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Borrowing;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    /**
     * Display reporting dashboard with filters and statistics.
     */
    public function index(Request $request): View
    {
        $reportType = $request->query('type', 'borrowings'); // borrowings, books, popular, users
        $startDate = $request->query('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->query('end_date', Carbon::now()->toDateString());

        // 1. Borrowing Report
        $borrowingsQuery = Borrowing::with(['user', 'book'])
            ->whereBetween('borrow_date', [$startDate, $endDate]);

        $borrowings = $borrowingsQuery->latest()->paginate(15)->withQueryString();

        // 2. Popular Books
        $popularBooks = Book::with('category')
            ->withCount(['borrowings' => function ($q) use ($startDate, $endDate) {
                $q->whereBetween('borrow_date', [$startDate, $endDate]);
            }])
            ->orderByDesc('borrowings_count')
            ->take(10)
            ->get();

        // 3. User Activity
        $activeUsers = User::where('role', 'user')
            ->withCount(['borrowings' => function ($q) use ($startDate, $endDate) {
                $q->whereBetween('borrow_date', [$startDate, $endDate]);
            }])
            ->orderByDesc('borrowings_count')
            ->take(10)
            ->get();

        // Summary Aggregates
        $totalTransactions = Borrowing::whereBetween('borrow_date', [$startDate, $endDate])->count();
        $totalReturned = Borrowing::whereBetween('borrow_date', [$startDate, $endDate])->where('status', 'returned')->count();
        $totalOverdue = Borrowing::whereBetween('borrow_date', [$startDate, $endDate])->where('status', 'borrowed')->where('return_due_date', '<', Carbon::today())->count();
        $totalFines = Borrowing::whereBetween('borrow_date', [$startDate, $endDate])->sum('fines');

        return view('admin.reports.index', compact(
            'reportType',
            'startDate',
            'endDate',
            'borrowings',
            'popularBooks',
            'activeUsers',
            'totalTransactions',
            'totalReturned',
            'totalOverdue',
            'totalFines'
        ));
    }

    /**
     * Export reports to CSV.
     */
    public function export(Request $request): StreamedResponse
    {
        $startDate = $request->query('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->query('end_date', Carbon::now()->toDateString());

        $borrowings = Borrowing::with(['user', 'book'])
            ->whereBetween('borrow_date', [$startDate, $endDate])
            ->latest()
            ->get();

        $filename = "laporan-peminjaman-{$startDate}-to-{$endDate}.csv";

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($borrowings) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Kode Peminjaman', 'Peminjam', 'Email', 'Judul Buku', 'ISBN', 'Tanggal Pinjam', 'Batas Kembali', 'Tanggal Pengembalian', 'Status', 'Denda']);

            foreach ($borrowings as $b) {
                fputcsv($handle, [
                    $b->borrowing_code,
                    $b->user->name ?? '-',
                    $b->user->email ?? '-',
                    $b->book->title ?? '-',
                    $b->book->isbn ?? '-',
                    $b->borrow_date->format('Y-m-d'),
                    $b->return_due_date->format('Y-m-d'),
                    $b->actual_return_date ? $b->actual_return_date->format('Y-m-d') : '-',
                    strtoupper($b->status),
                    $b->fines,
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Official printable report view for management/audits.
     */
    public function print(Request $request): View
    {
        $startDate = $request->query('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->query('end_date', Carbon::now()->toDateString());

        $borrowings = Borrowing::with(['user', 'book'])
            ->whereBetween('borrow_date', [$startDate, $endDate])
            ->latest()
            ->get();

        $totalTransactions = $borrowings->count();
        $totalReturned = $borrowings->where('status', 'returned')->count();
        $totalBorrowed = $borrowings->where('status', 'borrowed')->count();
        $totalOverdue = $borrowings->filter(fn ($b) => $b->isOverdue())->count();
        $totalFines = $borrowings->sum('fines');

        return view('admin.reports.print', compact(
            'borrowings',
            'startDate',
            'endDate',
            'totalTransactions',
            'totalReturned',
            'totalBorrowed',
            'totalOverdue',
            'totalFines'
        ));
    }
}
