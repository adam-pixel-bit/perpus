<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Book;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Store a book review and rating from a member.
     */
    public function store(Request $request, Book $book): RedirectResponse
    {
        $validated = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['required', 'string', 'min:5', 'max:1000'],
            'borrowing_id' => ['nullable', 'exists:borrowings,id'],
        ], [
            'rating.required' => 'Silakan pilih rating bintang 1 hingga 5.',
            'rating.min' => 'Rating minimal 1 bintang.',
            'rating.max' => 'Rating maksimal 5 bintang.',
            'comment.required' => 'Ulasan wajib diisi.',
            'comment.min' => 'Ulasan minimal 5 karakter.',
        ]);

        $userId = Auth::id();

        // Check if member already reviewed this book
        $existingReview = Review::where('user_id', $userId)
            ->where('book_id', $book->id)
            ->first();

        if ($existingReview) {
            $existingReview->update([
                'rating' => $validated['rating'],
                'comment' => $validated['comment'],
            ]);

            return back()->with('success', 'Ulasan buku Anda berhasil diperbarui!');
        }

        Review::create([
            'user_id' => $userId,
            'book_id' => $book->id,
            'borrowing_id' => $validated['borrowing_id'] ?? null,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
        ]);

        AuditLog::create([
            'user_id' => $userId,
            'action' => 'create_review',
            'description' => "Anggota memberikan ulasan (Bintang {$validated['rating']}) untuk buku '{$book->title}'.",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'created_at' => now(),
        ]);

        return back()->with('success', 'Terima kasih atas ulasan dan rating buku Anda!');
    }
}
