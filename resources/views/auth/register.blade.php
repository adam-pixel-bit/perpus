<x-layouts.auth title="Pendaftaran Anggota — Sistem Informasi Perpustakaan">
    <div class="bg-white rounded-[32px] border border-blue-100/80 shadow-2xl shadow-blue-900/10 p-5 sm:p-7 lg:p-8 grid grid-cols-1 lg:grid-cols-12 gap-8 items-stretch">
        
        <!-- LEFT PANEL: REGISTER FORM -->
        <div class="lg:col-span-7 flex flex-col justify-between p-2 sm:p-4">
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
            <div class="my-4 space-y-4">
                <div>
                    <h2 class="text-xl sm:text-2xl font-bold text-zinc-900 tracking-tight">
                        Pendaftaran Anggota Baru
                    </h2>
                    <p class="text-xs text-zinc-400 mt-0.5">
                        Lengkapi data diri Anda untuk membuat kartu anggota digital perpustakaan
                    </p>
                </div>

                <form method="POST" action="{{ route('register.post') }}" class="space-y-3.5">
                    @csrf

                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-xs font-semibold text-zinc-700 mb-1">
                            Nama Lengkap <span class="text-red-500">*</span>
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
                                class="w-full pl-10 pr-4 py-2 text-xs bg-white border border-zinc-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-zinc-900 placeholder-zinc-400 transition-colors shadow-2xs"
                            >
                        </div>
                        @error('name')
                            <p class="text-[11px] text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-xs font-semibold text-zinc-700 mb-1">
                            Alamat Email <span class="text-red-500">*</span>
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
                                class="w-full pl-10 pr-4 py-2 text-xs bg-white border border-zinc-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-zinc-900 placeholder-zinc-400 transition-colors shadow-2xs"
                            >
                        </div>
                        @error('email')
                            <p class="text-[11px] text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone & Address -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label for="phone" class="block text-xs font-semibold text-zinc-700 mb-1">
                                Nomor Telepon / WhatsApp
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
                                    class="w-full pl-10 pr-4 py-2 text-xs bg-white border border-zinc-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-zinc-900 placeholder-zinc-400 transition-colors shadow-2xs"
                                >
                            </div>
                        </div>

                        <div>
                            <label for="address" class="block text-xs font-semibold text-zinc-700 mb-1">
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
                                    class="w-full pl-10 pr-4 py-2 text-xs bg-white border border-zinc-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-zinc-900 placeholder-zinc-400 transition-colors shadow-2xs"
                                >
                            </div>
                        </div>
                    </div>

                    <!-- Password & Confirmation -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label for="password" class="block text-xs font-semibold text-zinc-700 mb-1">
                                Kata Sandi <span class="text-red-500">*</span>
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
                                    placeholder="Minimal 6 karakter"
                                    class="w-full pl-10 pr-4 py-2 text-xs bg-white border border-zinc-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-zinc-900 placeholder-zinc-400 transition-colors shadow-2xs"
                                >
                            </div>
                            @error('password')
                                <p class="text-[11px] text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-xs font-semibold text-zinc-700 mb-1">
                                Ulangi Kata Sandi <span class="text-red-500">*</span>
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
                                    class="w-full pl-10 pr-4 py-2 text-xs bg-white border border-zinc-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-zinc-900 placeholder-zinc-400 transition-colors shadow-2xs"
                                >
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button
                        type="submit"
                        class="w-full py-2.5 px-4 bg-[#1860E8] hover:bg-[#1350C7] text-white text-xs font-bold rounded-xl shadow-md shadow-blue-600/25 transition-all active:scale-[0.99] flex items-center justify-center space-x-2 mt-4 cursor-pointer"
                    >
                        <i data-lucide="user-plus" class="w-4 h-4"></i>
                        <span>Selesaikan Pendaftaran</span>
                    </button>
                </form>
            </div>

            <div class="text-center pt-2">
                <p class="text-[11px] text-zinc-400">
                    Sudah memiliki akun?
                    <a href="{{ route('login') }}" class="font-bold text-blue-600 hover:underline">
                        Masuk di sini
                    </a>
                </p>
            </div>
        </div>

        <!-- RIGHT PANEL: HERO SHOWCASE -->
        <div class="lg:col-span-5 bg-gradient-to-b from-[#EBF3FE] via-[#F3F7FF] to-[#EAF2FE] rounded-[28px] border border-blue-100/90 p-6 sm:p-8 flex flex-col justify-between items-center text-center relative overflow-hidden shadow-inner min-h-[460px]">
            <!-- Top Headline -->
            <div class="space-y-1 z-10">
                <h3 class="text-xl font-extrabold text-zinc-900 tracking-tight">
                    Bergabung ke <span class="text-blue-600">SIPUS Digital</span>
                </h3>
                <p class="text-xs text-zinc-500 font-medium">
                    Katalog dan sirkulasi peminjaman buku dalam satu genggaman
                </p>
            </div>

            <!-- Illustration & Feature Benefits -->
            <div class="relative w-full max-w-xs my-6 space-y-3 z-10">
                <div class="p-3 bg-white/80 backdrop-blur-xs rounded-2xl border border-blue-100 shadow-xs flex items-center space-x-3 text-left">
                    <div class="w-9 h-9 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center flex-shrink-0">
                        <i data-lucide="book-open" class="w-4 h-4"></i>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-zinc-900">Akses Seluruh Koleksi</h4>
                        <p class="text-[10px] text-zinc-400">Pinjam buku fisik & pantau stok langsung</p>
                    </div>
                </div>

                <div class="p-3 bg-white/80 backdrop-blur-xs rounded-2xl border border-blue-100 shadow-xs flex items-center space-x-3 text-left">
                    <div class="w-9 h-9 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center flex-shrink-0">
                        <i data-lucide="clock" class="w-4 h-4"></i>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-zinc-900">Pengajuan Mandiri</h4>
                        <p class="text-[10px] text-zinc-400">Ajukan pinjaman dan lacak statusnya</p>
                    </div>
                </div>

                <div class="p-3 bg-white/80 backdrop-blur-xs rounded-2xl border border-blue-100 shadow-xs flex items-center space-x-3 text-left">
                    <div class="w-9 h-9 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center flex-shrink-0">
                        <i data-lucide="bell" class="w-4 h-4"></i>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-zinc-900">Notifikasi Otomatis</h4>
                        <p class="text-[10px] text-zinc-400">Pengingat jatuh tempo & konfirmasi</p>
                    </div>
                </div>
            </div>

            <!-- Bottom Note & Indicator -->
            <div class="space-y-3 z-10">
                <p class="text-xs text-zinc-500 leading-relaxed px-2">
                    Kartu anggota digital langsung aktif setelah pendaftaran selesai.
                </p>
                <div class="flex items-center justify-center space-x-1.5 pt-1">
                    <span class="w-6 h-1.5 bg-blue-600 rounded-full shadow-xs"></span>
                    <span class="w-1.5 h-1.5 bg-blue-200 rounded-full"></span>
                    <span class="w-1.5 h-1.5 bg-blue-200 rounded-full"></span>
                </div>
            </div>
        </div>
    </div>
</x-layouts.auth>
