<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Display a listing of all users.
     */
    public function index(Request $request): View
    {
        $search = $request->query('search');
        $role = $request->query('role');
        $status = $request->query('status');

        $query = User::withCount('borrowings');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($role && $role !== 'all') {
            $query->where('role', $role);
        }

        if ($status !== null && $status !== 'all') {
            $query->where('is_active', $status === 'active');
        }

        $users = $query->latest()->paginate(12)->withQueryString();

        return view('admin.users.index', compact('users', 'search', 'role', 'status'));
    }

    /**
     * Display the specified user details and transaction history.
     */
    public function show(User $user): View
    {
        $user->load(['borrowings.book']);
        $borrowings = $user->borrowings()->latest()->paginate(10);

        return view('admin.users.show', compact('user', 'borrowings'));
    }

    /**
     * Show the form for editing user profile.
     */
    public function edit(User $user): View
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update user details.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
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
            'role' => ['required', Rule::in(['admin', 'user'])],
            'is_active' => ['required', 'boolean'],
            'password' => ['nullable', 'string', 'min:6'],
        ]);

        if (! empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        // Prevent admin from deactivating themselves
        if ($user->id === Auth::id() && ! $validated['is_active']) {
            return back()->with('error', 'Anda tidak dapat menonaktifkan akun Anda sendiri.');
        }

        $user->update($validated);

        AuditLog::record('update_user', "Admin memperbarui data pengguna '{$user->name}' ({$user->email}, Role: {$user->role})", $user);

        return redirect()->route('admin.users.index')
            ->with('success', 'Data pengguna "'.$user->name.'" berhasil diperbarui.');
    }

    /**
     * Toggle active/inactive status for a user.
     */
    public function toggleStatus(User $user): RedirectResponse
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Anda tidak dapat menonaktifkan akun Anda sendiri.');
        }

        $user->is_active = ! $user->is_active;
        $user->save();

        $actionWord = $user->is_active ? 'mengaktifkan' : 'menonaktifkan';
        AuditLog::record('toggle_user_status', "Admin {$actionWord} akun pengguna '{$user->name}' ({$user->email})", $user);

        $statusStr = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "Akun pengguna {$user->name} berhasil {$statusStr}.");
    }
}
