<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Borrowing;
use App\Models\Category;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the Admin Dashboard with analytical statistics.
     */
    public function index(): View
    {
        $totalBooks = Book::count();
        $totalCategories = Category::count();
        $totalUsers = User::where('role', 'user')->count();
        $totalBorrowings = Borrowing::count();

        // Available books (total physical available stock)
        $availableBooksCount = Book::sum('available_stock');

        // Status counts
        $pendingBorrowings = Borrowing::where('status', 'pending')->count();
        $activeBorrowings = Borrowing::where('status', 'borrowed')->count();
        $returnedBorrowings = Borrowing::where('status', 'returned')->count();

        // Count overdue borrowings
        $overdueBorrowings = Borrowing::where('status', 'borrowed')
            ->where('return_due_date', '<', Carbon::today())
            ->count();

        // Latest transactions
        $recentBorrowings = Borrowing::with(['user', 'book'])
            ->latest()
            ->take(6)
            ->get();

        // Popular books
        $popularBooks = Book::withCount('borrowings')
            ->orderByDesc('borrowings_count')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalBooks',
            'totalCategories',
            'totalUsers',
            'totalBorrowings',
            'availableBooksCount',
            'pendingBorrowings',
            'activeBorrowings',
            'returnedBorrowings',
            'overdueBorrowings',
            'recentBorrowings',
            'popularBooks'
        ));
    }
}
