<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookCatalogController extends Controller
{
    /**
     * Display public / member book catalog.
     */
    public function index(Request $request): View
    {
        $search = $request->query('search');
        $categoryId = $request->query('category_id');
        $availability = $request->query('availability'); // 'available', 'all'
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

        if ($availability === 'available') {
            $query->where('available_stock', '>', 0)->where('is_available', true);
        }

        if ($sort === 'title_asc') {
            $query->orderBy('title', 'asc');
        } elseif ($sort === 'popular') {
            $query->withCount('borrowings')->orderByDesc('borrowings_count');
        } else {
            $query->latest();
        }

        $books = $query->paginate(12)->withQueryString();
        $categories = Category::withCount('books')->orderBy('name')->get();

        return view('user.catalog.index', compact('books', 'categories', 'search', 'categoryId', 'availability', 'sort'));
    }

    /**
     * Display single book detail.
     */
    public function show(Book $book): View
    {
        $book->load('category');

        // Related books from same category
        $relatedBooks = Book::where('category_id', $book->category_id)
            ->where('id', '!=', $book->id)
            ->take(4)
            ->get();

        return view('user.catalog.show', compact('book', 'relatedBooks'));
    }
}
