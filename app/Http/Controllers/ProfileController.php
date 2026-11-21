<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the authenticated user profile and digital ID card.
     */
    public function index(): View
    {
        $user = Auth::user();
        $user->loadCount(['borrowings', 'reviews']);

        $activeBorrowings = $user->borrowings()->whereIn('status', ['borrowed', 'approved'])->count();

        return view('profile.index', compact('user', 'activeBorrowings'));
    }

    /**
     * Update user profile information.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.unique' => 'Email ini sudah digunakan oleh akun lain.',
            'avatar.max' => 'Ukuran file foto maksimal 2MB.',
        ]);

        if ($request->hasFile('avatar')) {
            // Delete old avatar if local file
            if ($user->avatar && ! str_starts_with($user->avatar, 'http')) {
                Storage::disk('public')->delete($user->avatar);
            }
            $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user->update($validated);

        AuditLog::record('update_profile', "Pengguna {$user->name} memperbarui data profil akun.");

        return back()->with('success', 'Informasi profil Anda berhasil diperbarui!');
    }

    /**
     * Update user password.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'confirmed', Password::min(6)],
        ], [
            'current_password.required' => 'Kata sandi saat ini wajib diisi.',
            'password.required' => 'Kata sandi baru wajib diisi.',
            'password.confirmed' => 'Konfirmasi kata sandi baru tidak cocok.',
            'password.min' => 'Kata sandi baru minimal 6 karakter.',
        ]);

        if (! Hash::check($request->input('current_password'), $user->password)) {
            return back()->withErrors(['current_password' => 'Kata sandi saat ini tidak sesuai.']);
        }

        $user->update([
            'password' => Hash::make($request->input('password')),
        ]);

        AuditLog::record('change_password', "Pengguna {$user->name} berhasil mengubah kata sandi.");

        return back()->with('success', 'Kata sandi akun Anda berhasil diperbarui!');
    }

    /**
     * Printable Member Card view.
     */
    public function memberCard(): View
    {
        $user = Auth::user();

        return view('profile.card-print', compact('user'));
    }
}
