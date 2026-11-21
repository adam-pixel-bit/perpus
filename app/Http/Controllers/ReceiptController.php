<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ReceiptController extends Controller
{
    /**
     * Display printable circulation receipt.
     */
    public function show(Borrowing $borrowing): View
    {
        $user = Auth::user();

        // Security check: only borrowing owner or admin can view receipt
        if (! $user->isAdmin() && $borrowing->user_id !== $user->id) {
            abort(403, 'Akses ditolak.');
        }

        $borrowing->load(['user', 'book']);

        return view('receipts.borrowing', compact('borrowing'));
    }
}
