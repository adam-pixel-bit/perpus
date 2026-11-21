<?php

namespace App\Http\Controllers\Admin;

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
     * Display all book requests from members.
     */
    public function index(Request $request): View
    {
        $status = $request->query('status');
        $search = $request->query('search');

        $query = BookRequest::with('user');

        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('author', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($uq) use ($search) {
                        $uq->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        $requests = $query->latest()->paginate(15)->withQueryString();

        return view('admin.requests.index', compact('requests', 'status', 'search'));
    }

    /**
     * Update status of a book request.
     */
    public function updateStatus(Request $request, BookRequest $bookRequest): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:approved,rejected,fulfilled'],
            'admin_note' => ['nullable', 'string', 'max:500'],
        ]);

        $bookRequest->update([
            'status' => $validated['status'],
            'admin_note' => $validated['admin_note'] ?? null,
            'reviewed_by' => Auth::id(),
        ]);

        $statusText = match ($validated['status']) {
            'approved' => 'disetujui untuk pengadaan',
            'rejected' => 'ditolak',
            'fulfilled' => 'telah diadakan dan tersedia di katalog',
            default => 'diperbarui',
        };

        Notification::create([
            'user_id' => $bookRequest->user_id,
            'title' => 'Status Usulan Buku',
            'message' => "Usulan buku '{$bookRequest->title}' Anda telah {$statusText}.".($validated['admin_note'] ? " Catatan: {$validated['admin_note']}" : ''),
            'type' => $validated['status'] === 'rejected' ? 'danger' : 'success',
            'link' => route('user.requests.index'),
        ]);

        AuditLog::record('review_book_request', "Admin memperbarui usulan buku '{$bookRequest->title}' menjadi '{$validated['status']}'.");

        return back()->with('success', "Status usulan buku '{$bookRequest->title}' berhasil diperbarui!");
    }
}
