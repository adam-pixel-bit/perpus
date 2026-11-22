<x-layouts.auth title="Pendaftaran Anggota — Sistem Informasi Perpustakaan">
    <div class="grid grid-cols-1 lg:grid-cols-12 min-h-[calc(92vh-2.5rem)] divide-y lg:divide-y-0 lg:divide-x divide-zinc-200/80">
        
        <!-- LEFT PANEL: REGISTER FORM -->
        <div class="lg:col-span-6 xl:col-span-6 p-4 sm:p-8 lg:p-12 flex flex-col justify-between bg-white">
            <!-- Brand Mark Top Left -->
            <div class="flex items-center space-x-2.5">
                <div class="w-8 h-8 rounded-xl bg-zinc-950 text-white font-black text-sm flex items-center justify-center tracking-tighter shadow-xs">
                    P
                </div>
                <div class="leading-tight">
                    <span class="text-xs font-bold text-zinc-900 tracking-tight block">SIPUS Digital</span>
                    <span class="text-[10px] text-zinc-400 font-medium">Sistem Informasi Perpustakaan Terpadu</span>
                </div>
            </div>

            <!-- Form Center Content -->
            <div class="my-auto py-4 max-w-md w-full mx-auto space-y-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-zinc-950 tracking-tight">
                        Pendaftaran Anggota Baru
                    </h1>
                    <p class="text-xs text-zinc-500 mt-1 font-medium">
                        Daftar instan dengan akun sosial media atau isi formulir di bawah ini.
                    </p>
                </div>

                <!-- Social Register Buttons (Google & Facebook) -->
                <div class="grid grid-cols-2 gap-3">
                    <a
                        href="{{ route('auth.social.redirect', 'google') }}"
                        class="p-2.5 rounded-xl border border-zinc-200/90 hover:border-zinc-400 hover:bg-zinc-50 transition-all flex items-center justify-center space-x-2 text-xs font-semibold text-zinc-800 shadow-2xs group"
                        title="Daftar dengan Google"
                    >
                        <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24">
                            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                        </svg>
                        <span class="group-hover:text-zinc-950">Google</span>
                    </a>

                    <a
                        href="{{ route('auth.social.redirect', 'facebook') }}"
                        class="p-2.5 rounded-xl border border-zinc-200/90 hover:border-zinc-400 hover:bg-zinc-50 transition-all flex items-center justify-center space-x-2 text-xs font-semibold text-zinc-800 shadow-2xs group"
                        title="Daftar dengan Facebook"
                    >
                        <svg class="w-4 h-4 shrink-0 text-[#1877F2]" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                        </svg>
                        <span class="group-hover:text-zinc-950">Facebook</span>
                    </a>
                </div>

                <div class="relative flex items-center justify-center my-2">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-zinc-200"></div>
                    </div>
                    <span class="relative px-3 bg-white text-[11px] text-zinc-400 font-medium">
                        atau daftar manual
                    </span>
                </div>

                <form method="POST" action="{{ route('register.post') }}" class="space-y-3.5">
                    @csrf

                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-xs font-bold text-zinc-800 mb-1">
                            Nama Lengkap<span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-zinc-400">
                                <i data-lucide="user" class="w-4 h-4"></i>
                            </div>
                            <input
                                id="name"
                                type="text"
                                name="name"
                                value="{{ old('name') }}"
                                required
                                autofocus
                                placeholder="Contoh: Budi Santoso"
                                class="w-full pl-10 pr-3.5 py-2 text-xs bg-white border border-zinc-200 rounded-xl focus:outline-none focus:ring-1 focus:ring-zinc-950 focus:border-zinc-950 text-zinc-900 placeholder-zinc-400 transition-colors shadow-2xs"
                            >
                        </div>
                        @error('name')
                            <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-xs font-bold text-zinc-800 mb-1">
                            Alamat Email<span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-zinc-400">
                                <i data-lucide="mail" class="w-4 h-4"></i>
                            </div>
                            <input
                                id="email"
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                required
                                placeholder="budi@perpustakaan.com"
                                class="w-full pl-10 pr-3.5 py-2 text-xs bg-white border border-zinc-200 rounded-xl focus:outline-none focus:ring-1 focus:ring-zinc-950 focus:border-zinc-950 text-zinc-900 placeholder-zinc-400 transition-colors shadow-2xs"
                            >
                        </div>
                        @error('email')
                            <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone & Address -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label for="phone" class="block text-xs font-bold text-zinc-800 mb-1">
                                Nomor WhatsApp
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-zinc-400">
                                    <i data-lucide="phone" class="w-4 h-4"></i>
                                </div>
                                <input
                                    id="phone"
                                    type="text"
                                    name="phone"
                                    value="{{ old('phone') }}"
                                    placeholder="08123456789"
                                    class="w-full pl-10 pr-3.5 py-2 text-xs bg-white border border-zinc-200 rounded-xl focus:outline-none focus:ring-1 focus:ring-zinc-950 focus:border-zinc-950 text-zinc-900 placeholder-zinc-400 transition-colors shadow-2xs"
                                >
                            </div>
                        </div>

                        <div>
                            <label for="address" class="block text-xs font-bold text-zinc-800 mb-1">
                                Alamat Domisili
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-zinc-400">
                                    <i data-lucide="map-pin" class="w-4 h-4"></i>
                                </div>
                                <input
                                    id="address"
                                    type="text"
                                    name="address"
                                    value="{{ old('address') }}"
                                    placeholder="Jakarta Selatan"
                                    class="w-full pl-10 pr-3.5 py-2 text-xs bg-white border border-zinc-200 rounded-xl focus:outline-none focus:ring-1 focus:ring-zinc-950 focus:border-zinc-950 text-zinc-900 placeholder-zinc-400 transition-colors shadow-2xs"
                                >
                            </div>
                        </div>
                    </div>

                    <!-- Password & Confirmation -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label for="password" class="block text-xs font-bold text-zinc-800 mb-1">
                                Kata Sandi<span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-zinc-400">
                                    <i data-lucide="lock" class="w-4 h-4"></i>
                                </div>
                                <input
                                    id="password"
                                    type="password"
                                    name="password"
                                    required
                                    placeholder="Min. 6 karakter"
                                    class="w-full pl-10 pr-3.5 py-2 text-xs bg-white border border-zinc-200 rounded-xl focus:outline-none focus:ring-1 focus:ring-zinc-950 focus:border-zinc-950 text-zinc-900 placeholder-zinc-400 transition-colors shadow-2xs"
                                >
                            </div>
                            @error('password')
                                <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-xs font-bold text-zinc-800 mb-1">
                                Ulangi Kata Sandi<span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-zinc-400">
                                    <i data-lucide="shield-check" class="w-4 h-4"></i>
                                </div>
                                <input
                                    id="password_confirmation"
                                    type="password"
                                    name="password_confirmation"
                                    required
                                    placeholder="Ulangi kata sandi"
                                    class="w-full pl-10 pr-3.5 py-2 text-xs bg-white border border-zinc-200 rounded-xl focus:outline-none focus:ring-1 focus:ring-zinc-950 focus:border-zinc-950 text-zinc-900 placeholder-zinc-400 transition-colors shadow-2xs"
                                >
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button
                        type="submit"
                        class="w-full py-2.5 px-4 bg-zinc-950 hover:bg-black text-white text-xs font-bold rounded-xl shadow-md shadow-zinc-950/20 transition-all active:scale-[0.99] flex items-center justify-center space-x-2 mt-4 cursor-pointer"
                    >
                        <i data-lucide="user-plus" class="w-4 h-4"></i>
                        <span>Selesaikan Pendaftaran</span>
                    </button>
                </form>
            </div>

            <!-- Footer Link -->
            <div class="pt-4 border-t border-zinc-100 flex items-center justify-between text-xs text-zinc-400">
                <span>Sudah memiliki akun?</span>
                <a href="{{ route('login') }}" class="font-bold text-zinc-900 hover:text-orange-600 transition-colors">
                    Masuk ke akun Anda →
                </a>
            </div>
        </div>

        <!-- RIGHT PANEL: HERO SHOWCASE (Palette matching Dashboard & Login) -->
        <div class="lg:col-span-6 xl:col-span-6 p-4 sm:p-8 lg:p-10 flex flex-col justify-center items-center bg-[#F8F9FB]">
            <!-- Inner Hero Card -->
            <div class="w-full max-w-lg bg-zinc-950 text-white rounded-[24px] sm:rounded-[28px] border border-zinc-800 shadow-2xl p-4 sm:p-7 lg:p-8 flex flex-col justify-between relative overflow-hidden min-h-[460px] sm:min-h-[500px]">
                
                <!-- Ambient Subtle Glows -->
                <div class="absolute -top-16 -right-16 w-48 h-48 bg-orange-600/20 rounded-full blur-3xl pointer-events-none"></div>
                <div class="absolute -bottom-16 -left-16 w-48 h-48 bg-zinc-700/20 rounded-full blur-3xl pointer-events-none"></div>

                <!-- Floating Badge Top Left -->
                <div class="absolute top-6 left-6 z-20">
                    <div class="w-10 h-10 rounded-full bg-white text-zinc-950 shadow-md flex items-center justify-center border border-zinc-200">
                        <i data-lucide="book-open" class="w-4 h-4 text-orange-600"></i>
                    </div>
                </div>

                <!-- Headline -->
                <div class="pt-10 sm:pt-12 z-10 text-left">
                    <h2 class="text-xl sm:text-2xl font-extrabold text-white tracking-tight leading-snug">
                        Bergabung ke <span class="text-orange-500">SIPUS Digital</span>
                    </h2>
                    <p class="text-xs text-zinc-400 mt-1">
                        Katalog koleksi buku dan sirkulasi peminjaman digital dalam satu platform.
                    </p>
                </div>

                <!-- Feature Benefits Cards (Styled in dark zinc to match) -->
                <div class="my-6 space-y-3 z-10">
                    <div class="p-3.5 bg-zinc-900/90 rounded-2xl border border-zinc-800/90 shadow-xs flex items-center space-x-3.5 text-left">
                        <div class="w-9 h-9 rounded-xl bg-orange-500/10 text-orange-500 border border-orange-500/20 flex items-center justify-center flex-shrink-0">
                            <i data-lucide="book" class="w-4 h-4"></i>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-white">Akses Seluruh Koleksi</h4>
                            <p class="text-[11px] text-zinc-400 mt-0.5">Pinjam buku fisik & pantau stok langsung di rak</p>
                        </div>
                    </div>

                    <div class="p-3.5 bg-zinc-900/90 rounded-2xl border border-zinc-800/90 shadow-xs flex items-center space-x-3.5 text-left">
                        <div class="w-9 h-9 rounded-xl bg-emerald-500/10 text-emerald-500 border border-emerald-500/20 flex items-center justify-center flex-shrink-0">
                            <i data-lucide="clock" class="w-4 h-4"></i>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-white">Pengajuan Mandiri</h4>
                            <p class="text-[11px] text-zinc-400 mt-0.5">Ajukan permohonan pinjam dan lacak persetujuan</p>
                        </div>
                    </div>

                    <div class="p-3.5 bg-zinc-900/90 rounded-2xl border border-zinc-800/90 shadow-xs flex items-center space-x-3.5 text-left">
                        <div class="w-9 h-9 rounded-xl bg-amber-500/10 text-amber-500 border border-amber-500/20 flex items-center justify-center flex-shrink-0">
                            <i data-lucide="bell" class="w-4 h-4"></i>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-white">Notifikasi Otomatis</h4>
                            <p class="text-[11px] text-zinc-400 mt-0.5">Pengingat jatuh tempo sirkulasi & info buku baru</p>
                        </div>
                    </div>
                </div>

                <!-- Bottom Note & Indicator -->
                <div class="z-10 text-left pt-2 flex items-center justify-between">
                    <p class="text-xs text-zinc-400">
                        Kartu anggota langsung aktif setelah pendaftaran.
                    </p>
                    <div class="flex items-center space-x-1.5">
                        <span class="w-6 h-1.5 bg-orange-600 rounded-full shadow-xs"></span>
                        <span class="w-1.5 h-1.5 bg-zinc-700 rounded-full"></span>
                        <span class="w-1.5 h-1.5 bg-zinc-700 rounded-full"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.auth>
