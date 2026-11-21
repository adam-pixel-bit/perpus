<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Borrowing;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ScannerController extends Controller
{
    /**
     * Show the QR Scanner and Circulation Desk page.
     */
    public function index(Request $request): View
    {
        $code = $request->query('code');
        $borrowing = null;
        $book = null;
        $user = null;

        if ($code) {
            $code = trim($code);
            // Check borrowing code
            $borrowing = Borrowing::with(['user', 'book'])->where('borrowing_code', $code)->first();

            // Check if ISBN
            if (! $borrowing) {
                $book = Book::where('isbn', $code)->first();
            }

            // Check if member email or ID
            if (! $borrowing && ! $book) {
                $user = User::with('borrowings.book')->where('email', $code)->first();
            }
        }

        return view('admin.scanner.index', compact('code', 'borrowing', 'book', 'user'));
    }

    /**
     * API lookup for dynamic scanner input.
     */
    public function lookup(Request $request): JsonResponse
    {
        $code = trim($request->query('code', ''));

        if (! $code) {
            return response()->json(['found' => false, 'message' => 'Kode kosong']);
        }

        $borrowing = Borrowing::with(['user', 'book'])->where('borrowing_code', $code)->first();
        if ($borrowing) {
            return response()->json([
                'found' => true,
                'type' => 'borrowing',
                'data' => [
                    'id' => $borrowing->id,
                    'code' => $borrowing->borrowing_code,
                    'status' => $borrowing->status,
                    'borrower' => $borrowing->user->name,
                    'email' => $borrowing->user->email,
                    'book_title' => $borrowing->book->title,
                    'isbn' => $borrowing->book->isbn,
                    'borrow_date' => $borrowing->borrow_date->format('d M Y'),
                    'return_due_date' => $borrowing->return_due_date->format('d M Y'),
                    'is_overdue' => $borrowing->isOverdue(),
                    'fines' => $borrowing->fines,
                ],
            ]);
        }

        $book = Book::where('isbn', $code)->first();
        if ($book) {
            return response()->json([
                'found' => true,
                'type' => 'book',
                'data' => [
                    'id' => $book->id,
                    'title' => $book->title,
                    'isbn' => $book->isbn,
                    'author' => $book->author,
                    'shelf_location' => $book->shelf_location,
                    'available_stock' => $book->available_stock,
                    'stock' => $book->stock,
                ],
            ]);
        }

        return response()->json(['found' => false, 'message' => "Data dengan kode '{$code}' tidak ditemukan."]);
    }
}
