<x-layouts.auth title="Masuk ke Akun — Sistem Informasi Perpustakaan">
    <div class="bg-white rounded-[32px] border border-blue-100/80 shadow-2xl shadow-blue-900/10 p-5 sm:p-7 lg:p-8 grid grid-cols-1 lg:grid-cols-12 gap-8 items-stretch">
        
        <!-- LEFT PANEL: LOGIN FORM (Exact Layout from Reference Image) -->
        <div class="lg:col-span-6 flex flex-col justify-between p-2 sm:p-4">
            <!-- Brand Mark Top Left -->
            <div class="flex items-center space-x-2.5">
                <div class="w-8 h-8 rounded-xl bg-blue-600 text-white font-extrabold text-sm flex items-center justify-center shadow-xs">
                    P
                </div>
                <div class="leading-tight">
                    <span class="text-xs font-bold text-zinc-900 tracking-tight block">SIPUS Digital</span>
                    <span class="text-[10px] text-zinc-400 font-medium">Sistem Perpustakaan</span>
                </div>
            </div>

            <!-- Form Center Content -->
            <div class="my-6 space-y-5">
                <!-- Faint Grid Background & Central Blue Icon -->
                <div class="relative flex flex-col items-center justify-center py-2">
                    <div class="absolute w-40 h-20 pointer-events-none opacity-20" style="background-image: radial-gradient(#2563eb 1.5px, transparent 1.5px); background-size: 12px 12px;"></div>
                    <div class="relative w-13 h-13 rounded-2xl bg-gradient-to-tr from-blue-600 to-sky-500 text-white flex items-center justify-center shadow-lg shadow-blue-500/35 border border-white/80">
                        <i data-lucide="key-round" class="w-6 h-6"></i>
                    </div>
                    <h2 class="text-xl sm:text-2xl font-bold text-zinc-900 tracking-tight text-center mt-3">
                        Login to your account!
                    </h2>
                    <p class="text-xs text-zinc-400 text-center mt-1">
                        Selamat Datang Kembali! Masukkan email dan kata sandi Anda untuk login.
                    </p>
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
                        <label for="email" class="block text-xs font-semibold text-zinc-700 mb-1.5">Email</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-zinc-400">
                                <i data-lucide="mail" class="w-4 h-4"></i>
                            </div>
                            <input
                                id="email"
                                type="email"
                                name="email"
                                value="{{ old('email', 'admin@perpustakaan.com') }}"
                                required
                                autofocus
                                placeholder="eg. pixelcot@gmail.com"
                                class="w-full pl-10 pr-4 py-2.5 text-xs bg-white border border-zinc-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-zinc-900 placeholder-zinc-400 transition-colors shadow-2xs"
                            >
                        </div>
                        @error('email')
                            <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password Field -->
                    <div>
                        <label for="password" class="block text-xs font-semibold text-zinc-700 mb-1.5">Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-zinc-400">
                                <i data-lucide="lock" class="w-4 h-4"></i>
                            </div>
                            <input
                                id="password"
                                type="password"
                                name="password"
                                value="password123"
                                required
                                placeholder="••••••••••••"
                                class="w-full pl-10 pr-10 py-2.5 text-xs bg-white border border-zinc-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-zinc-900 placeholder-zinc-400 transition-colors shadow-2xs"
                            >
                            <button
                                type="button"
                                onclick="togglePasswordVisibility()"
                                class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-zinc-400 hover:text-zinc-600 cursor-pointer"
                                title="Lihat/Sembunyikan Kata Sandi"
                            >
                                <i id="eye-icon" data-lucide="eye" class="w-4 h-4"></i>
                            </button>
                        </div>
                        @error('password')
                            <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div class="flex items-center justify-between text-xs">
                        <label class="flex items-center space-x-2 cursor-pointer select-none">
                            <input
                                type="checkbox"
                                name="remember"
                                checked
                                class="w-3.5 h-3.5 rounded border-zinc-300 text-blue-600 focus:ring-0 cursor-pointer"
                            >
                            <span class="text-zinc-500 font-medium">Remember me</span>
                        </label>
                        <button type="button" onclick="fillCredentials('admin@perpustakaan.com', 'password123')" class="font-semibold text-blue-600 hover:text-blue-700 transition-colors cursor-pointer">
                            Isi Akun Admin?
                        </button>
                    </div>

                    <!-- Primary Blue Login Button -->
                    <button
                        type="submit"
                        class="w-full py-2.5 px-4 bg-[#1860E8] hover:bg-[#1350C7] text-white text-xs font-bold rounded-xl shadow-md shadow-blue-600/25 transition-all active:scale-[0.99] flex items-center justify-center space-x-2 cursor-pointer"
                    >
                        <span>Login</span>
                    </button>
                </form>

                <!-- Or Login With / Quick Demo Switcher -->
                <div class="space-y-3 pt-1">
                    <div class="relative flex items-center justify-center">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-zinc-200/80"></div>
                        </div>
                        <span class="relative px-3 bg-white text-[11px] text-zinc-400 font-medium">
                            Or login with
                        </span>
                    </div>

                    <!-- 3 Quick Action Cards (Google, Apple, Microsoft style in reference) -->
                    <div class="grid grid-cols-3 gap-2.5">
                        <!-- Admin Quick Login -->
                        <button
                            type="button"
                            onclick="fillCredentials('admin@perpustakaan.com', 'password123')"
                            class="p-2.5 rounded-xl border border-zinc-200/90 hover:border-blue-400 hover:bg-blue-50/40 transition-all flex flex-col items-center justify-center text-center group cursor-pointer shadow-2xs"
                            title="Klik untuk mengisi data Administrator"
                        >
                            <div class="w-5 h-5 flex items-center justify-center text-blue-600 group-hover:scale-110 transition-transform">
                                <i data-lucide="shield-check" class="w-4 h-4"></i>
                            </div>
                            <span class="text-[10px] font-bold text-zinc-700 group-hover:text-blue-600 mt-1">Admin</span>
                        </button>

                        <!-- Member Quick Login -->
                        <button
                            type="button"
                            onclick="fillCredentials('budi@perpustakaan.com', 'password123')"
                            class="p-2.5 rounded-xl border border-zinc-200/90 hover:border-emerald-400 hover:bg-emerald-50/40 transition-all flex flex-col items-center justify-center text-center group cursor-pointer shadow-2xs"
                            title="Klik untuk mengisi data Anggota"
                        >
                            <div class="w-5 h-5 flex items-center justify-center text-emerald-600 group-hover:scale-110 transition-transform">
                                <i data-lucide="user-check" class="w-4 h-4"></i>
                            </div>
                            <span class="text-[10px] font-bold text-zinc-700 group-hover:text-emerald-600 mt-1">Anggota</span>
                        </button>

                        <!-- Register New Account -->
                        <a
                            href="{{ route('register') }}"
                            class="p-2.5 rounded-xl border border-zinc-200/90 hover:border-purple-400 hover:bg-purple-50/40 transition-all flex flex-col items-center justify-center text-center group shadow-2xs"
                            title="Registrasi Akun Anggota Baru"
                        >
                            <div class="w-5 h-5 flex items-center justify-center text-purple-600 group-hover:scale-110 transition-transform">
                                <i data-lucide="user-plus" class="w-4 h-4"></i>
                            </div>
                            <span class="text-[10px] font-bold text-zinc-700 group-hover:text-purple-600 mt-1">Daftar</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Footer Link -->
            <div class="text-center pt-2">
                <p class="text-[11px] text-zinc-400">
                    Belum punya akun anggota?
                    <a href="{{ route('register') }}" class="font-bold text-blue-600 hover:underline">
                        Daftar sekarang
                    </a>
                </p>
            </div>
        </div>

        <!-- RIGHT PANEL: HERO SHOWCASE (Concentric Radar Orbits & Illustration) -->
        <div class="lg:col-span-6 bg-gradient-to-b from-[#EBF3FE] via-[#F3F7FF] to-[#EAF2FE] rounded-[28px] border border-blue-100/90 p-6 sm:p-8 lg:p-10 flex flex-col justify-between items-center text-center relative overflow-hidden shadow-inner min-h-[480px]">
            <!-- Top Headline -->
            <div class="space-y-1 z-10">
                <h3 class="text-xl sm:text-2xl font-extrabold text-zinc-900 tracking-tight">
                    Write Better <span class="text-blue-600">Everywhere</span>
                </h3>
                <p class="text-xs text-zinc-500 font-medium">
                    Sistem otomasi dan sirkulasi perpustakaan modern
                </p>
            </div>

            <!-- Center Planetary Orbit Graphic (Concentric Rings matching Reference) -->
            <div class="relative w-72 h-72 my-4 flex items-center justify-center">
                <!-- Outer Orbit Ring (Ring 3) -->
                <div class="absolute w-64 h-64 rounded-full border border-blue-200/60 flex items-center justify-center"></div>

                <!-- Middle Orbit Ring (Ring 2) -->
                <div class="absolute w-48 h-48 rounded-full border border-blue-200/80 flex items-center justify-center"></div>

                <!-- Inner Orbit Ring (Ring 1) -->
                <div class="absolute w-32 h-32 rounded-full border border-blue-300 flex items-center justify-center"></div>

                <!-- Central Glowing Brand Orb -->
                <div class="relative z-20 w-16 h-16 rounded-full bg-gradient-to-tr from-blue-600 to-sky-400 text-white flex items-center justify-center shadow-xl shadow-blue-500/40 border-4 border-white">
                    <div class="flex items-center justify-center text-white font-black text-xl tracking-tighter">
                        <i data-lucide="book-open-check" class="w-7 h-7"></i>
                    </div>
                </div>

                <!-- Orbiting App Badges (Floating nodes matching Chrome, Edge, Apple, etc.) -->
                <!-- Node 1: Edge / Sirkulasi (Top Right of Ring 3) -->
                <div class="absolute -top-1 right-20 w-8 h-8 rounded-full bg-white shadow-md border border-blue-100 flex items-center justify-center text-cyan-600 z-10 hover:scale-110 transition-transform cursor-pointer" title="Sirkulasi Peminjaman">
                    <i data-lucide="repeat" class="w-4 h-4"></i>
                </div>

                <!-- Node 2: Android / Mobile (Top Left of Ring 2) -->
                <div class="absolute top-12 left-5 w-7 h-7 rounded-full bg-white shadow-md border border-blue-100 flex items-center justify-center text-emerald-500 z-10 hover:scale-110 transition-transform cursor-pointer" title="Aksesibilitas Mobile">
                    <i data-lucide="smartphone" class="w-3.5 h-3.5"></i>
                </div>

                <!-- Node 3: Outlook / Notifikasi (Top Center of Ring 2) -->
                <div class="absolute top-10 right-28 w-7 h-7 rounded-full bg-white shadow-md border border-blue-100 flex items-center justify-center text-blue-600 z-10 hover:scale-110 transition-transform cursor-pointer" title="Notifikasi Sistem">
                    <i data-lucide="bell" class="w-3.5 h-3.5"></i>
                </div>

                <!-- Node 4: Messenger / Transaksi (Far Right of Ring 3) -->
                <div class="absolute top-24 right-2 w-7 h-7 rounded-full bg-white shadow-md border border-blue-100 flex items-center justify-center text-indigo-500 z-10 hover:scale-110 transition-transform cursor-pointer" title="Riwayat Transaksi">
                    <i data-lucide="clock" class="w-3.5 h-3.5"></i>
                </div>

                <!-- Node 5: Apple / Pengguna (Bottom Right of Ring 3) -->
                <div class="absolute bottom-16 right-5 w-8 h-8 rounded-full bg-white shadow-md border border-blue-100 flex items-center justify-center text-zinc-900 z-10 hover:scale-110 transition-transform cursor-pointer" title="Data Anggota">
                    <i data-lucide="users" class="w-4 h-4"></i>
                </div>

                <!-- Node 6: Chrome / Katalog (Far Left of Ring 3) -->
                <div class="absolute bottom-20 left-10 w-8 h-8 rounded-full bg-white shadow-md border border-blue-100 flex items-center justify-center text-amber-500 z-10 hover:scale-110 transition-transform cursor-pointer" title="Katalog Koleksi">
                    <i data-lucide="book" class="w-4 h-4"></i>
                </div>

                <!-- Node 7: Yahoo / Denda (Bottom of Ring 1) -->
                <div class="absolute -bottom-2 left-32 w-7 h-7 rounded-full bg-white shadow-md border border-blue-100 flex items-center justify-center text-purple-600 z-10 hover:scale-110 transition-transform cursor-pointer" title="Kalkulasi Denda">
                    <i data-lucide="coins" class="w-3.5 h-3.5"></i>
                </div>

                <!-- Node 8: LinkedIn / Supabase PostgreSQL (Far Bottom Right of Ring 3) -->
                <div class="absolute -bottom-1 right-20 w-8 h-8 rounded-full bg-white shadow-md border border-blue-100 flex items-center justify-center text-blue-700 z-10 hover:scale-110 transition-transform cursor-pointer" title="Supabase PostgreSQL">
                    <i data-lucide="database" class="w-4 h-4"></i>
                </div>
            </div>

            <!-- Bottom Description & Carousel Indicators -->
            <div class="space-y-3 z-10">
                <p class="text-xs text-zinc-500 max-w-sm leading-relaxed px-2">
                    Compatible with <strong>Gmail</strong>, <strong>Outlook Web</strong>, <strong>LinkedIn</strong> and most web editors for a smooth writing experience anywhere online.
                </p>
                <div class="flex items-center justify-center space-x-1.5 pt-1">
                    <span class="w-6 h-1.5 bg-blue-600 rounded-full shadow-xs"></span>
                    <span class="w-1.5 h-1.5 bg-blue-200 rounded-full"></span>
                    <span class="w-1.5 h-1.5 bg-blue-200 rounded-full"></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Interactive Scripts -->
    <script>
        function fillCredentials(email, password) {
            const emailInput = document.getElementById('email');
            const passInput = document.getElementById('password');
            emailInput.value = email;
            passInput.value = password;

            // Subtle visual feedback
            emailInput.classList.add('ring-2', 'ring-blue-500/30');
            passInput.classList.add('ring-2', 'ring-blue-500/30');
            setTimeout(() => {
                emailInput.classList.remove('ring-2', 'ring-blue-500/30');
                passInput.classList.remove('ring-2', 'ring-blue-500/30');
            }, 600);
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
