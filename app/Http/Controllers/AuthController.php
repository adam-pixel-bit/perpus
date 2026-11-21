<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLoginForm(): View|RedirectResponse
    {
        if (Auth::check()) {
            return Auth::user()->isAdmin()
                ? redirect()->route('admin.dashboard')
                : redirect()->route('user.dashboard');
        }

        return view('auth.login');
    }

    /**
     * Handle an authentication attempt with Rate Limiting and Audit Log.
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $throttleKey = Str::transliterate(Str::lower($request->input('email')).'|'.$request->ip());

        // Maximum 5 failed attempts within 60 seconds
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            AuditLog::create([
                'action' => 'login_throttled',
                'description' => "Percobaan login diblokir karena terlalu banyak percobaan gagal (Email: {$request->input('email')})",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'created_at' => now(),
            ]);

            return back()->withErrors([
                'email' => "Terlalu banyak percobaan login gagal. Demi keamanan, silakan coba lagi dalam {$seconds} detik.",
            ])->onlyInput('email');
        }

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();

            // Clear throttle on successful login
            RateLimiter::clear($throttleKey);

            // Check if account is active
            if (! $user->is_active) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                AuditLog::create([
                    'user_id' => $user->id,
                    'action' => 'login_deactivated_attempt',
                    'description' => "Pengguna non-aktif '{$user->name}' mencoba masuk.",
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'created_at' => now(),
                ]);

                return back()->withErrors([
                    'email' => 'Akun Anda telah dinonaktifkan oleh Administrator.',
                ])->onlyInput('email');
            }

            $request->session()->regenerate();

            // Audit log on login
            AuditLog::record('user_login', "Pengguna {$user->name} ({$user->email}) berhasil login sebagai {$user->role}.");

            if ($user->isAdmin()) {
                return redirect()->intended(route('admin.dashboard'))
                    ->with('success', 'Selamat datang kembali, '.$user->name.'!');
            }

            return redirect()->intended(route('user.dashboard'))
                ->with('success', 'Selamat datang di Perpustakaan Digital, '.$user->name.'!');
        }

        // Increment rate limiter on failure
        RateLimiter::hit($throttleKey, 60);

        AuditLog::create([
            'action' => 'login_failed',
            'description' => "Gagal login untuk email '{$request->input('email')}'.",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'created_at' => now(),
        ]);

        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan tidak sesuai.',
        ])->onlyInput('email');
    }

    /**
     * Show registration form for members.
     */
    public function showRegisterForm(): View|RedirectResponse
    {
        if (Auth::check()) {
            return Auth::user()->isAdmin()
                ? redirect()->route('admin.dashboard')
                : redirect()->route('user.dashboard');
        }

        return view('auth.register');
    }

    /**
     * Handle member registration.
     */
    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(6)],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.unique' => 'Alamat email ini sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.min' => 'Password minimal terdiri dari 6 karakter.',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'user',
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
            'is_active' => true,
        ]);

        AuditLog::create([
            'user_id' => $user->id,
            'action' => 'member_registered',
            'description' => "Anggota baru {$user->name} mendaftar mandiri.",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'created_at' => now(),
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('user.dashboard')
            ->with('success', 'Registrasi berhasil! Selamat bergabung sebagai anggota perpustakaan.');
    }

    /**
     * Show Forgot Password Form.
     */
    public function showForgotPasswordForm(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect('/');
        }

        return view('auth.forgot-password');
    }

    /**
     * Generate password reset token.
     */
    public function sendResetLink(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.exists' => 'Kami tidak menemukan akun dengan alamat email tersebut.',
        ]);

        $token = Str::random(64);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->input('email')],
            [
                'token' => Hash::make($token),
                'created_at' => Carbon::now(),
            ]
        );

        AuditLog::create([
            'action' => 'password_reset_requested',
            'description' => "Permintaan reset password diajukan untuk email: {$request->input('email')}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'created_at' => now(),
        ]);

        $resetUrl = route('password.reset', ['token' => $token, 'email' => $request->input('email')]);

        return back()->with('status', 'Tautan pemulihan kata sandi telah dibuat.')
            ->with('demo_reset_link', $resetUrl);
    }

    /**
     * Show Reset Password Form.
     */
    public function showResetPasswordForm(Request $request, string $token): View
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->query('email'),
        ]);
    }

    /**
     * Reset the user password.
     */
    public function resetPassword(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email', 'exists:users,email'],
            'password' => ['required', 'confirmed', Password::min(6)],
        ], [
            'password.required' => 'Kata sandi baru wajib diisi.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
            'password.min' => 'Kata sandi minimal 6 karakter.',
        ]);

        $record = DB::table('password_reset_tokens')
            ->where('email', $request->input('email'))
            ->first();

        if (! $record || ! Hash::check($request->input('token'), $record->token)) {
            return back()->withErrors(['email' => 'Tautan pemulihan kata sandi tidak valid atau telah kedaluwarsa.']);
        }

        // Expire after 60 minutes
        if (Carbon::parse($record->created_at)->addMinutes(60)->isPast()) {
            DB::table('password_reset_tokens')->where('email', $request->input('email'))->delete();

            return back()->withErrors(['email' => 'Tautan pemulihan kata sandi telah kedaluwarsa. Silakan ajukan ulang.']);
        }

        $user = User::where('email', $request->input('email'))->firstOrFail();
        $user->update([
            'password' => Hash::make($request->input('password')),
        ]);

        // Delete token
        DB::table('password_reset_tokens')->where('email', $request->input('email'))->delete();

        AuditLog::create([
            'user_id' => $user->id,
            'action' => 'password_reset_success',
            'description' => "Pengguna {$user->name} berhasil memperbarui kata sandi via pemulihan token.",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'created_at' => now(),
        ]);

        return redirect()->route('login')
            ->with('success', 'Kata sandi Anda berhasil diperbarui! Silakan masuk dengan kata sandi baru.');
    }

    /**
     * Log the user out of the application.
     */
    public function logout(Request $request): RedirectResponse
    {
        if (Auth::check()) {
            AuditLog::record('user_logout', 'Pengguna keluar dari sistem.');
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('info', 'Anda telah berhasil keluar dari sistem.');
    }
}
