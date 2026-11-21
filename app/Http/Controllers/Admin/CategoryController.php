<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CategoryController extends Controller
{
    /**
     * Display a listing of the categories with book counts.
     */
    public function index(): View
    {
        $categories = Category::withCount('books')
            ->orderBy('name')
            ->paginate(15);

        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Store a newly created category.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:categories,name'],
            'description' => ['nullable', 'string', 'max:500'],
        ], [
            'name.required' => 'Nama kategori wajib diisi.',
            'name.unique' => 'Kategori dengan nama ini sudah ada.',
            'description.max' => 'Deskripsi kategori maksimal 500 karakter.',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        Category::create($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori baru "'.$validated['name'].'" berhasil ditambahkan.');
    }

    /**
     * Update the specified category.
     */
    public function update(Request $request, Category $category): RedirectResponse
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('categories', 'name')->ignore($category->id),
            ],
            'description' => ['nullable', 'string', 'max:500'],
        ], [
            'name.required' => 'Nama kategori wajib diisi.',
            'name.unique' => 'Kategori dengan nama ini sudah ada.',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        $category->update($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori "'.$category->name.'" berhasil diperbarui.');
    }

    /**
     * Remove the specified category.
     */
    public function destroy(Category $category): RedirectResponse
    {
        // Business rule: Check if category has linked books
        if ($category->books()->count() > 0) {
            return back()->with('error', 'Kategori tidak dapat dihapus karena masih digunakan oleh beberapa buku di katalog.');
        }

        $name = $category->name;
        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori "'.$name.'" berhasil dihapus.');
    }
}
