<x-layouts.auth title="Login to your Account — Sistem Informasi Perpustakaan">
    <div class="grid grid-cols-1 lg:grid-cols-12 min-h-[calc(92vh-2.5rem)] divide-y lg:divide-y-0 lg:divide-x divide-zinc-200/80">
        
        <!-- LEFT PANEL: LOGIN FORM (Exact Layout from Reference Image) -->
        <div class="lg:col-span-6 xl:col-span-6 p-4 sm:p-8 lg:p-12 flex flex-col justify-between bg-white">
            <!-- Brand Logo Top Left -->
            <div class="flex items-center space-x-2.5">
                <div class="w-8 h-8 rounded-xl bg-zinc-950 text-white font-black text-sm flex items-center justify-center tracking-tighter shadow-xs">
                    w.
                </div>
                <div class="leading-tight">
                    <span class="text-xs font-bold text-zinc-900 tracking-tight block">SIPUS Digital</span>
                    <span class="text-[10px] text-zinc-400 font-medium">Uxerflow Library Cloud</span>
                </div>
            </div>

            <!-- Centered Form Container -->
            <div class="my-auto py-6 max-w-md w-full mx-auto space-y-5">
                <!-- Heading & Welcome Subtitle -->
                <div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-zinc-950 tracking-tight">
                        Login to your Account
                    </h1>
                    <p class="text-xs text-zinc-500 mt-1 font-medium">
                        Selamat Datang Kembali! 👋 Pilih metode untuk masuk:
                    </p>
                </div>

                <!-- Social Login Buttons (Google & Facebook) -->
                <div class="grid grid-cols-2 gap-3">
                    <!-- Google Login Button -->
                    <a
                        href="{{ route('auth.social.redirect', 'google') }}"
                        class="p-2.5 rounded-xl border border-zinc-200/90 hover:border-zinc-400 hover:bg-zinc-50 transition-all flex items-center justify-center space-x-2 text-xs font-semibold text-zinc-800 shadow-2xs group"
                        title="Masuk menggunakan Akun Google"
                    >
                        <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24">
                            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                        </svg>
                        <span class="group-hover:text-zinc-950">Google</span>
                    </a>

                    <!-- Facebook Login Button -->
                    <a
                        href="{{ route('auth.social.redirect', 'facebook') }}"
                        class="p-2.5 rounded-xl border border-zinc-200/90 hover:border-zinc-400 hover:bg-zinc-50 transition-all flex items-center justify-center space-x-2 text-xs font-semibold text-zinc-800 shadow-2xs group"
                        title="Masuk menggunakan Akun Facebook"
                    >
                        <svg class="w-4 h-4 shrink-0 text-[#1877F2]" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                        </svg>
                        <span class="group-hover:text-zinc-950">Facebook</span>
                    </a>
                </div>

                <!-- Quick Demo Fill Bar -->
                <div class="flex items-center justify-between text-[11px] text-zinc-500 bg-zinc-50 border border-zinc-200/80 rounded-xl px-3 py-1.5">
                    <span class="font-medium text-zinc-400">Isi Cepat Demo:</span>
                    <div class="flex items-center space-x-2">
                        <button type="button" onclick="fillCredentials('admin@perpustakaan.test', 'password')" class="font-semibold text-zinc-700 hover:text-zinc-950 underline cursor-pointer">Admin</button>
                        <span class="text-zinc-300">•</span>
                        <button type="button" onclick="fillCredentials('petugas@perpustakaan.test', 'password')" class="font-semibold text-zinc-700 hover:text-zinc-950 underline cursor-pointer">Petugas</button>
                        <span class="text-zinc-300">•</span>
                        <button type="button" onclick="fillCredentials('budi@perpustakaan.test', 'password')" class="font-semibold text-zinc-700 hover:text-zinc-950 underline cursor-pointer">Anggota</button>
                    </div>
                </div>

                <!-- Divider: "or continue with email" -->
                <div class="relative flex items-center justify-center my-4">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-zinc-200"></div>
                    </div>
                    <span class="relative px-3 bg-white text-[11px] text-zinc-400 font-medium">
                        or continue with email
                    </span>
                </div>

                <!-- Flash Messages -->
                @if(session('success'))
                    <div class="p-3 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs flex items-center space-x-2">
                        <i data-lucide="check-circle" class="w-4 h-4 text-emerald-600 flex-shrink-0"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div class="p-3 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs flex items-center space-x-2">
                        <i data-lucide="alert-circle" class="w-4 h-4 text-rose-600 flex-shrink-0"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                <!-- Login Form -->
                <form method="POST" action="{{ route('login.post') }}" class="space-y-4">
                    @csrf

                    <!-- Email Field -->
                    <div>
                        <label for="email" class="block text-xs font-bold text-zinc-800 mb-1.5">
                            Email<span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-zinc-400">
                                <i data-lucide="mail" class="w-4 h-4"></i>
                            </div>
                            <input
                                id="email"
                                type="email"
                                name="email"
                                value="{{ old('email', 'admin@perpustakaan.test') }}"
                                required
                                autofocus
                                placeholder="Enter Your Email"
                                class="w-full pl-10 pr-3.5 py-2.5 text-xs bg-white border border-zinc-200 rounded-xl focus:outline-none focus:ring-1 focus:ring-zinc-950 focus:border-zinc-950 text-zinc-900 placeholder-zinc-400 transition-colors shadow-2xs"
                            >
                        </div>
                        @error('email')
                            <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password Field -->
                    <div>
                        <label for="password" class="block text-xs font-bold text-zinc-800 mb-1.5">
                            Password<span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-zinc-400">
                                <i data-lucide="lock" class="w-4 h-4"></i>
                            </div>
                            <input
                                id="password"
                                type="password"
                                name="password"
                                value="password"
                                required
                                placeholder="••••••••••••"
                                class="w-full pl-10 pr-10 py-2.5 text-xs bg-white border border-zinc-200 rounded-xl focus:outline-none focus:ring-1 focus:ring-zinc-950 focus:border-zinc-950 text-zinc-900 placeholder-zinc-400 transition-colors shadow-2xs"
                            >
                            <button
                                type="button"
                                onclick="togglePasswordVisibility()"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-zinc-400 hover:text-zinc-700 cursor-pointer"
                                title="Lihat/Sembunyikan Kata Sandi"
                            >
                                <i id="eye-icon" data-lucide="eye" class="w-4 h-4"></i>
                            </button>
                        </div>
                        @error('password')
                            <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Remember Me & Forgot Password Row -->
                    <div class="flex items-center justify-between text-xs pt-0.5">
                        <label class="flex items-center space-x-2 cursor-pointer select-none">
                            <input
                                type="checkbox"
                                name="remember"
                                id="remember-me"
                                checked
                                class="w-4 h-4 rounded text-zinc-900 border-zinc-300 focus:ring-0 cursor-pointer accent-zinc-950"
                            >
                            <span class="text-xs text-zinc-600 font-medium">Remember Me</span>
                        </label>
                        <a href="{{ route('password.request') }}" class="text-xs font-semibold text-orange-600 hover:text-orange-700">
                            Lupa Kata Sandi?
                        </a>
                    </div>

                    <!-- Primary High-Contrast Login Button (Matching Dashboard) -->
                    <button
                        type="submit"
                        class="w-full py-2.5 px-4 bg-zinc-950 hover:bg-black text-white text-xs font-bold rounded-xl shadow-md shadow-zinc-950/20 transition-all active:scale-[0.99] flex items-center justify-center space-x-2 cursor-pointer mt-3"
                    >
                        <span>Log in</span>
                    </button>
                </form>

                <!-- Help / Support Footer Link (as seen in image) -->
                <div class="text-center pt-2">
                    <p class="text-xs text-zinc-400 font-medium">
                        Facing Problems in signing in?
                        <a href="{{ route('register') }}" class="font-bold text-zinc-900 hover:text-orange-600 underline underline-offset-2 transition-colors">
                            Contact Support
                        </a>
                    </p>
                </div>
            </div>

            <!-- Copyright Bottom Left -->
            <div class="pt-6 border-t border-zinc-100 text-[11px] text-zinc-400">
                © {{ date('Y') }} SIPUS Digital • Pacific Analytics Platform
            </div>
        </div>

        <!-- RIGHT PANEL: HERO SHOWCASE (Matching structure & dashboard palette) -->
        <div class="lg:col-span-6 xl:col-span-6 p-4 sm:p-8 lg:p-10 flex flex-col justify-center items-center bg-[#F8F9FB]">
            <!-- Inner Hero Card -->
            <div class="w-full max-w-lg bg-zinc-950 text-white rounded-[24px] sm:rounded-[28px] border border-zinc-800 shadow-2xl p-4 sm:p-7 lg:p-8 flex flex-col justify-between relative overflow-hidden min-h-[460px] sm:min-h-[500px]">
                
                <!-- Ambient Subtle Glows -->
                <div class="absolute -top-16 -right-16 w-48 h-48 bg-orange-600/20 rounded-full blur-3xl pointer-events-none"></div>
                <div class="absolute -bottom-16 -left-16 w-48 h-48 bg-zinc-700/20 rounded-full blur-3xl pointer-events-none"></div>

                <!-- Floating Badge Top Left (Wave/Sparkle Badge as in image) -->
                <div class="absolute top-6 left-6 z-20">
                    <div class="w-10 h-10 rounded-full bg-white text-zinc-950 shadow-md flex items-center justify-center border border-zinc-200">
                        <i data-lucide="sparkles" class="w-4 h-4 text-orange-600"></i>
                    </div>
                </div>

                <!-- Headline (Elevating You Towards The Right Direction) -->
                <div class="pt-10 sm:pt-12 z-10 text-left">
                    <h2 class="text-xl sm:text-2xl font-extrabold text-white tracking-tight leading-snug">
                        Elevating You Towards The<br>Right Direction
                    </h2>
                    <p class="text-xs text-zinc-400 mt-1">
                        Sistem Informasi Perpustakaan & Sirkulasi Digital
                    </p>
                </div>

                <!-- Centered Dashboard Mockup Card (Layered Preview as in image) -->
                <div class="my-6 relative z-10">
                    <div class="bg-white text-zinc-900 rounded-2xl border border-zinc-200/90 shadow-2xl p-4 space-y-3 transform -rotate-1 hover:rotate-0 transition-transform duration-300">
                        <!-- Mini Topbar -->
                        <div class="flex items-center justify-between border-b border-zinc-100 pb-2">
                            <div class="flex items-center space-x-1.5">
                                <span class="w-2.5 h-2.5 rounded-full bg-red-400"></span>
                                <span class="w-2.5 h-2.5 rounded-full bg-amber-400"></span>
                                <span class="w-2.5 h-2.5 rounded-full bg-emerald-400"></span>
                            </div>
                            <span class="text-[10px] font-mono text-zinc-400">sipus.digital/dashboard</span>
                            <span class="text-[9px] font-bold px-1.5 py-0.2 rounded bg-orange-50 text-orange-700">Live</span>
                        </div>

                        <!-- 3 Mini Metric Cards -->
                        <div class="grid grid-cols-3 gap-2 text-left">
                            <div class="p-2 rounded-xl bg-zinc-50 border border-zinc-100">
                                <span class="text-[9px] text-zinc-400 block font-medium">Total Buku</span>
                                <span class="text-xs font-bold text-zinc-900 block mt-0.5">{{ max(250, \App\Models\Book::count()) }}</span>
                                <span class="text-[8px] text-emerald-600 font-bold">+3 baru</span>
                            </div>
                            <div class="p-2 rounded-xl bg-zinc-50 border border-zinc-100">
                                <span class="text-[9px] text-zinc-400 block font-medium">Sirkulasi</span>
                                <span class="text-xs font-bold text-zinc-900 block mt-0.5">{{ max(2355, \App\Models\Borrowing::count()) }}</span>
                                <span class="text-[8px] text-emerald-600 font-bold">+7%</span>
                            </div>
                            <div class="p-2 rounded-xl bg-zinc-50 border border-zinc-100">
                                <span class="text-[9px] text-zinc-400 block font-medium">Kas Denda</span>
                                <span class="text-xs font-bold text-zinc-900 block mt-0.5">Rp 15.490</span>
                                <span class="text-[8px] text-emerald-600 font-bold">+9%</span>
                            </div>
                        </div>

                        <!-- Mini Data Row Preview with Badges -->
                        <div class="space-y-1.5 pt-1">
                            <div class="flex items-center justify-between p-1.5 rounded-lg bg-zinc-50/70 text-[10px]">
                                <span class="font-medium text-zinc-800 truncate max-w-[120px]">Clean Code: Robert C.</span>
                                <span class="px-1.5 py-0.2 rounded font-semibold text-[9px] bg-[#F0F2FE] text-[#5552D9]">In Stock</span>
                            </div>
                            <div class="flex items-center justify-between p-1.5 rounded-lg bg-[#FFF7F2] border-l-2 border-l-orange-500 text-[10px]">
                                <span class="font-medium text-zinc-800 truncate max-w-[120px]">Atomic Habits: Clear</span>
                                <span class="px-1.5 py-0.2 rounded font-semibold text-[9px] bg-[#FFF8EB] text-[#B87A04]">Restock</span>
                            </div>
                        </div>
                    </div>

                    <!-- Floating Circular Icon Badge Left (Analytics icon) -->
                    <div class="absolute -bottom-3 -left-3 z-20">
                        <div class="w-9 h-9 rounded-full bg-white text-zinc-900 shadow-xl flex items-center justify-center border border-zinc-200">
                            <i data-lucide="bar-chart-2" class="w-4 h-4 text-orange-600"></i>
                        </div>
                    </div>

                    <!-- Floating Circular Icon Badge Right (Brain/User icon) -->
                    <div class="absolute -bottom-3 -right-3 z-20">
                        <div class="w-9 h-9 rounded-full bg-white text-zinc-900 shadow-xl flex items-center justify-center border border-zinc-200">
                            <i data-lucide="user-check" class="w-4 h-4 text-emerald-600"></i>
                        </div>
                    </div>
                </div>

                <!-- Bottom Quote / Description (as seen in image) -->
                <div class="z-10 text-left pt-2">
                    <p class="text-xs text-zinc-400 leading-relaxed">
                        Even the most analytical thinkers are predictably irrational; the really smart ones acknowledge and address their irrationalities. Right Direction
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Client-side Scripts -->
    <script>
        function fillCredentials(email, password) {
            const emailInput = document.getElementById('email');
            const passInput = document.getElementById('password');
            emailInput.value = email;
            passInput.value = password;

            // Visual feedback ring
            emailInput.classList.add('ring-2', 'ring-orange-500/40');
            passInput.classList.add('ring-2', 'ring-orange-500/40');
            setTimeout(() => {
                emailInput.classList.remove('ring-2', 'ring-orange-500/40');
                passInput.classList.remove('ring-2', 'ring-orange-500/40');
            }, 500);
        }

        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.setAttribute('data-lucide', 'eye-off');
            } else {
                passwordInput.type = 'password';
                eyeIcon.setAttribute('data-lucide', 'eye');
            }
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        }
    </script>
</x-layouts.auth>
