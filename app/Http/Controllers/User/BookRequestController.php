<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\BookRequest;
use App\Models\Notification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class BookRequestController extends Controller
{
    /**
     * Display member's submitted book requests.
     */
    public function index(): View
    {
        $requests = BookRequest::where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('user.requests.index', compact('requests'));
    }

    /**
     * Submit a new book procurement request.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'author' => ['required', 'string', 'max:255'],
            'publisher' => ['nullable', 'string', 'max:255'],
            'publication_year' => ['nullable', 'integer', 'min:1800', 'max:'.(date('Y') + 1)],
            'isbn' => ['nullable', 'string', 'max:30'],
            'reason' => ['required', 'string', 'max:1000'],
        ], [
            'title.required' => 'Judul buku yang diusulkan wajib diisi.',
            'author.required' => 'Nama pengarang/penulis buku wajib diisi.',
            'reason.required' => 'Alasan atau kebutuhan pengadaan buku wajib diisi.',
        ]);

        $bookRequest = BookRequest::create([
            'user_id' => Auth::id(),
            'title' => $validated['title'],
            'author' => $validated['author'],
            'publisher' => $validated['publisher'] ?? null,
            'publication_year' => $validated['publication_year'] ?? null,
            'isbn' => $validated['isbn'] ?? null,
            'reason' => $validated['reason'],
            'status' => 'pending',
        ]);

        Notification::create([
            'user_id' => Auth::id(),
            'title' => 'Usulan Buku Dikirim',
            'message' => "Usulan pengadaan buku '{$bookRequest->title}' telah diterima dan akan dievaluasi oleh tim pengadaan perpustakaan.",
            'type' => 'info',
            'link' => route('user.requests.index'),
        ]);

        AuditLog::record('create_book_request', 'Anggota '.Auth::user()->name." mengajukan usulan pengadaan buku '{$bookRequest->title}'.");

        return back()->with('success', "Usulan pengadaan buku '{$bookRequest->title}' berhasil diajukan!");
    }
}
