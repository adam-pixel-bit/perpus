<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class BookController extends Controller
{
    /**
     * Display a listing of books with search and filters.
     */
    public function index(Request $request): View
    {
        $search = $request->query('search');
        $categoryId = $request->query('category_id');
        $status = $request->query('status'); // 'available', 'unavailable'
        $sort = $request->query('sort', 'latest');

        $query = Book::with('category');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('author', 'like', "%{$search}%")
                    ->orWhere('isbn', 'like', "%{$search}%")
                    ->orWhere('publisher', 'like', "%{$search}%");
            });
        }

        if ($categoryId && $categoryId !== 'all') {
            $query->where('category_id', $categoryId);
        }

        if ($status === 'available') {
            $query->where('available_stock', '>', 0)->where('is_available', true);
        } elseif ($status === 'unavailable') {
            $query->where(function ($q) {
                $q->where('available_stock', '<=', 0)->orWhere('is_available', false);
            });
        }

        if ($sort === 'oldest') {
            $query->oldest();
        } elseif ($sort === 'title_asc') {
            $query->orderBy('title', 'asc');
        } elseif ($sort === 'stock_desc') {
            $query->orderByDesc('stock');
        } else {
            $query->latest();
        }

        $perPage = (int) $request->query('per_page', 10);
        $perPage = in_array($perPage, [10, 25, 50], true) ? $perPage : 10;

        $books = $query->paginate($perPage)->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('admin.books.index', compact('books', 'categories', 'search', 'categoryId', 'status', 'sort', 'perPage'));
    }

    /**
     * Show the form for creating a new book.
     */
    public function create(): View
    {
        $categories = Category::orderBy('name')->get();

        return view('admin.books.create', compact('categories'));
    }

    /**
     * Store a newly created book in storage.
     */
    public function store(StoreBookRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $data['slug'] = Str::slug($data['title']).'-'.Str::random(5);
        $data['available_stock'] = $data['stock'];
        $data['is_available'] = $data['stock'] > 0;

        if ($request->hasFile('cover_image')) {
            $path = $request->file('cover_image')->store('covers', 'public');
            $data['cover_image'] = $path;
        } elseif (! empty($request->input('cover_image_url'))) {
            $data['cover_image'] = $request->input('cover_image_url');
        }

        Book::create($data);

        return redirect()->route('admin.books.index')
            ->with('success', 'Buku "'.$data['title'].'" berhasil ditambahkan ke katalog!');
    }

    /**
     * Display the specified book with borrowing history.
     */
    public function show(Book $book): View
    {
        $book->load(['category', 'borrowings.user']);
        $recentBorrowings = $book->borrowings()->latest()->take(10)->get();

        return view('admin.books.show', compact('book', 'recentBorrowings'));
    }

    /**
     * Show the form for editing the specified book.
     */
    public function edit(Book $book): View
    {
        $categories = Category::orderBy('name')->get();

        return view('admin.books.edit', compact('book', 'categories'));
    }

    /**
     * Update the specified book in storage.
     */
    public function update(UpdateBookRequest $request, Book $book): RedirectResponse
    {
        $data = $request->validated();

        // Calculate new available stock if total stock changed
        $borrowedUnits = $book->stock - $book->available_stock;
        $newStock = (int) $data['stock'];

        if ($newStock < $borrowedUnits) {
            return back()->withInput()->withErrors([
                'stock' => "Stok total tidak boleh lebih kecil dari jumlah buku yang sedang dipinjam ({$borrowedUnits} eksemplar).",
            ]);
        }

        $data['available_stock'] = $newStock - $borrowedUnits;
        $data['is_available'] = $data['available_stock'] > 0;

        // If title changed, update slug
        if ($book->title !== $data['title']) {
            $data['slug'] = Str::slug($data['title']).'-'.Str::random(5);
        }

        // Handle cover image
        if ($request->hasFile('cover_image')) {
            // Delete old uploaded cover if exists
            if ($book->cover_image && ! str_starts_with($book->cover_image, 'http')) {
                Storage::disk('public')->delete($book->cover_image);
            }
            $data['cover_image'] = $request->file('cover_image')->store('covers', 'public');
        } elseif (! empty($request->input('cover_image_url'))) {
            $data['cover_image'] = $request->input('cover_image_url');
        }

        $book->update($data);

        return redirect()->route('admin.books.index')
            ->with('success', 'Informasi buku "'.$book->title.'" berhasil diperbarui.');
    }

    /**
     * Remove the specified book from storage.
     */
    public function destroy(Book $book): RedirectResponse
    {
        // Business rule: Do not allow deletion if book is currently borrowed
        $activeBorrowings = $book->borrowings()->whereIn('status', ['borrowed', 'pending'])->count();

        if ($activeBorrowings > 0) {
            return back()->with('error', 'Buku tidak dapat dihapus karena sedang dalam proses peminjaman atau dipinjam oleh anggota.');
        }

        $title = $book->title;

        // Delete uploaded cover file if exists
        if ($book->cover_image && ! str_starts_with($book->cover_image, 'http')) {
            Storage::disk('public')->delete($book->cover_image);
        }

        $book->delete();

        return redirect()->route('admin.books.index')
            ->with('success', 'Buku "'.$title.'" berhasil dihapus dari sistem.');
    }
}
