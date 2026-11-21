<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BorrowBookRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->isUser() && $this->user()->is_active;
    }

    public function rules(): array
    {
        return [
            'book_id' => ['required', 'exists:books,id'],
            'duration_days' => ['required', 'integer', 'min:1', 'max:14'],
            'notes' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'book_id.required' => 'Buku yang dipinjam tidak terdeteksi.',
            'book_id.exists' => 'Buku yang dipilih tidak ditemukan dalam katalog.',
            'duration_days.required' => 'Durasi peminjaman wajib ditentukan.',
            'duration_days.min' => 'Durasi peminjaman minimal 1 hari.',
            'duration_days.max' => 'Durasi peminjaman maksimal 14 hari.',
            'notes.max' => 'Catatan maksimal 500 karakter.',
        ];
    }
}
