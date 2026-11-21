<x-layouts.auth title="Pendaftaran Anggota — Sistem Informasi Perpustakaan">
    <div class="space-y-6">
        <div>
            <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">Pendaftaran Anggota Baru</h2>
            <p class="text-xs text-slate-500 mt-1">Lengkapi data diri Anda untuk membuat kartu anggota perpustakaan digital.</p>
        </div>

        <form method="POST" action="{{ route('register.post') }}" class="space-y-3.5">
            @csrf

            <!-- Name -->
            <div>
                <label for="name" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                    Nama Lengkap <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <i data-lucide="user" class="w-4 h-4"></i>
                    </div>
                    <input
                        id="name"
                        type="text"
                        name="name"
                        value="{{ old('name') }}"
                        required
                        autofocus
                        placeholder="e.g. Budi Santoso"
                        class="w-full pl-10 pr-4 py-2 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition-all text-slate-900 placeholder:text-slate-400"
                    >
                </div>
                @error('name')
                    <p class="text-xs text-rose-600 font-medium mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                    Alamat Email <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <i data-lucide="mail" class="w-4 h-4"></i>
                    </div>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        placeholder="budi@example.com"
                        class="w-full pl-10 pr-4 py-2 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition-all text-slate-900 placeholder:text-slate-400"
                    >
                </div>
                @error('email')
                    <p class="text-xs text-rose-600 font-medium mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Phone & Address -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label for="phone" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                        Nomor Telepon / WhatsApp
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i data-lucide="phone" class="w-4 h-4"></i>
                        </div>
                        <input
                            id="phone"
                            type="text"
                            name="phone"
                            value="{{ old('phone') }}"
                            placeholder="08123456789"
                            class="w-full pl-10 pr-4 py-2 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition-all text-slate-900 placeholder:text-slate-400"
                        >
                    </div>
                </div>

                <div>
                    <label for="address" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                        Alamat Domisili
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i data-lucide="map-pin" class="w-4 h-4"></i>
                        </div>
                        <input
                            id="address"
                            type="text"
                            name="address"
                            value="{{ old('address') }}"
                            placeholder="Kota / Daerah"
                            class="w-full pl-10 pr-4 py-2 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition-all text-slate-900 placeholder:text-slate-400"
                        >
                    </div>
                </div>
            </div>

            <!-- Password & Confirmation -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                        Kata Sandi <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i data-lucide="lock" class="w-4 h-4"></i>
                        </div>
                        <input
                            id="password"
                            type="password"
                            name="password"
                            required
                            placeholder="Min 6 karakter"
                            class="w-full pl-10 pr-4 py-2 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition-all text-slate-900 placeholder:text-slate-400"
                        >
                    </div>
                    @error('password')
                        <p class="text-xs text-rose-600 font-medium mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                        Konfirmasi Kata Sandi <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i data-lucide="shield-check" class="w-4 h-4"></i>
                        </div>
                        <input
                            id="password_confirmation"
                            type="password"
                            name="password_confirmation"
                            required
                            placeholder="Ulangi kata sandi"
                            class="w-full pl-10 pr-4 py-2 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition-all text-slate-900 placeholder:text-slate-400"
                        >
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <button
                type="submit"
                class="w-full py-2.5 px-4 bg-blue-600 hover:bg-blue-700 active:scale-[0.99] text-white text-xs font-bold uppercase tracking-wider rounded-xl shadow-lg shadow-blue-600/30 transition-all flex items-center justify-center space-x-2 mt-4"
            >
                <i data-lucide="user-plus" class="w-4 h-4"></i>
                <span>Daftar Sekarang</span>
            </button>
        </form>

        <div class="text-center pt-1">
            <p class="text-xs text-slate-500">
                Sudah memiliki akun anggota?
                <a href="{{ route('login') }}" class="font-bold text-blue-600 hover:underline">
                    Masuk di sini
                </a>
            </p>
        </div>
    </div>
</x-layouts.auth>
