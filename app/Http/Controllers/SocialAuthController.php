<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\User;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    /**
     * Supported social providers.
     *
     * @var array<string, string>
     */
    protected array $providers = [
        'google' => 'Google',
        'facebook' => 'Facebook',
    ];

    /**
     * Redirect the user to the provider authentication page.
     */
    public function redirect(string $provider): RedirectResponse
    {
        if (! array_key_exists($provider, $this->providers)) {
            return redirect()->route('login')->with('error', 'Penyedia layanan login tidak didukung.');
        }

        $clientId = config("services.{$provider}.client_id");
        $clientSecret = config("services.{$provider}.client_secret");

        if (empty($clientId) || empty($clientSecret)) {
            return redirect()->route('login')->with('error', "Layanan login {$this->providers[$provider]} belum dikonfigurasi di sistem (Client ID / Secret kosong). Silakan hubungi Administrator.");
        }

        try {
            return Socialite::driver($provider)->redirect();
        } catch (Exception $e) {
            return redirect()->route('login')->with('error', "Gagal menghubungi server {$this->providers[$provider]}: {$e->getMessage()}");
        }
    }

    /**
     * Obtain the user information from the provider callback.
     */
    public function callback(string $provider, Request $request): RedirectResponse
    {
        if (! array_key_exists($provider, $this->providers)) {
            return redirect()->route('login')->with('error', 'Penyedia layanan login tidak didukung.');
        }

        if ($request->has('error') || $request->has('error_code')) {
            return redirect()->route('login')->with('error', "Login dengan {$this->providers[$provider]} dibatalkan.");
        }

        try {
            $socialUser = Socialite::driver($provider)->user();
        } catch (Exception $e) {
            return redirect()->route('login')->with('error', "Gagal memproses data otentikasi {$this->providers[$provider]}. Silakan coba lagi.");
        }

        $socialId = $socialUser->getId();
        $email = $socialUser->getEmail();
        $name = $socialUser->getName() ?: ($socialUser->getNickname() ?: 'Pengguna '.$this->providers[$provider]);
        $avatar = $socialUser->getAvatar();

        if (empty($email)) {
            return redirect()->route('login')->with('error', "Akun {$this->providers[$provider]} Anda tidak memiliki alamat email publik yang dapat diverifikasi.");
        }

        $idColumn = "{$provider}_id";

        // 1. Cari berdasarkan Social ID
        $user = User::where($idColumn, $socialId)->first();

        // 2. Jika belum ada, cari berdasarkan Email
        if (! $user) {
            $user = User::where('email', $email)->first();

            if ($user) {
                // Tautkan akun sosial ke user yang sudah ada
                $user->{$idColumn} = $socialId;
                if (empty($user->avatar) && ! empty($avatar)) {
                    $user->avatar = $avatar;
                }
                $user->save();
            } else {
                // 3. Buat user baru jika belum pernah terdaftar
                $user = User::create([
                    'name' => $name,
                    'email' => $email,
                    'password' => Hash::make(Str::random(32)),
                    'role' => 'user',
                    'avatar' => $avatar,
                    $idColumn => $socialId,
                    'is_active' => true,
                ]);
            }
        }

        // Cek status keaktifan akun
        if (! $user->is_active) {
            AuditLog::create([
                'user_id' => $user->id,
                'action' => 'social_login_deactivated_attempt',
                'description' => "Pengguna non-aktif '{$user->name}' mencoba login via {$this->providers[$provider]}.",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'created_at' => now(),
            ]);

            return redirect()->route('login')->with('error', 'Akun Anda telah dinonaktifkan oleh Administrator.');
        }

        Auth::login($user, true);
        $request->session()->regenerate();

        AuditLog::record(
            'social_login',
            "Pengguna {$user->name} ({$user->email}) berhasil masuk melalui {$this->providers[$provider]}."
        );

        if ($user->isAdmin()) {
            return redirect()->intended(route('admin.dashboard'))
                ->with('success', "Selamat datang kembali, {$user->name}! (Masuk via {$this->providers[$provider]})");
        }

        return redirect()->intended(route('user.dashboard'))
            ->with('success', "Selamat datang di SIPUS Digital, {$user->name}! (Masuk via {$this->providers[$provider]})");
    }
}
