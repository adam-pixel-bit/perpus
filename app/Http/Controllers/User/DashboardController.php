<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Borrowing;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display member dashboard.
     */
    public function index(): View
    {
        $userId = Auth::id();

        // Stats for current user
        $currentlyBorrowedCount = Borrowing::where('user_id', $userId)
            ->where('status', 'borrowed')
            ->count();

        $pendingApprovalCount = Borrowing::where('user_id', $userId)
            ->where('status', 'pending')
            ->count();

        $returnedCount = Borrowing::where('user_id', $userId)
            ->where('status', 'returned')
            ->count();

        $overdueCount = Borrowing::where('user_id', $userId)
            ->where('status', 'borrowed')
            ->where('return_due_date', '<', Carbon::today())
            ->count();

        // Recent personal activity
        $recentBorrowings = Borrowing::where('user_id', $userId)
            ->with('book.category')
            ->latest()
            ->take(5)
            ->get();

        // Featured / recommended books
        $recommendedBooks = Book::with('category')
            ->where('available_stock', '>', 0)
            ->where('is_available', true)
            ->latest()
            ->take(4)
            ->get();

        return view('user.dashboard', compact(
            'currentlyBorrowedCount',
            'pendingApprovalCount',
            'returnedCount',
            'overdueCount',
            'recentBorrowings',
            'recommendedBooks'
        ));
    }
}
