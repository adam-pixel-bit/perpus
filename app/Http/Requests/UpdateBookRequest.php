<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBookRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->isAdmin();
    }

    public function rules(): array
    {
        $bookId = $this->route('book')?->id ?? $this->route('book');

        return [
            'category_id' => ['required', 'exists:categories,id'],
            'title' => ['required', 'string', 'max:255'],
            'isbn' => [
                'required',
                'string',
                'max:30',
                Rule::unique('books', 'isbn')->ignore($bookId),
            ],
            'author' => ['required', 'string', 'max:255'],
            'publisher' => ['required', 'string', 'max:255'],
            'publication_year' => ['required', 'integer', 'min:1800', 'max:'.(date('Y') + 1)],
            'stock' => ['required', 'integer', 'min:1'],
            'shelf_location' => ['required', 'string', 'max:50'],
            'description' => ['nullable', 'string'],
            'cover_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'cover_image_url' => ['nullable', 'url', 'max:500'],
            'is_available' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'category_id.required' => 'Kategori buku wajib dipilih.',
            'category_id.exists' => 'Kategori yang dipilih tidak valid.',
            'title.required' => 'Judul buku wajib diisi.',
            'isbn.required' => 'Nomor ISBN wajib diisi.',
            'isbn.unique' => 'Nomor ISBN ini sudah terdaftar di sistem.',
            'author.required' => 'Nama penulis wajib diisi.',
            'publisher.required' => 'Nama penerbit wajib diisi.',
            'publication_year.required' => 'Tahun terbit wajib diisi.',
            'stock.required' => 'Jumlah stok wajib diisi.',
            'stock.min' => 'Stok buku minimal 1.',
            'shelf_location.required' => 'Lokasi rak buku wajib diisi.',
            'cover_image.max' => 'Ukuran file sampul buku maksimal 2MB.',
        ];
    }
}
