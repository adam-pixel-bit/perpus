<x-layouts.auth title="Masuk — Sistem Informasi Perpustakaan">
    <div class="space-y-6">
        <div>
            <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">Selamat Datang Kembali</h2>
            <p class="text-xs text-slate-500 mt-1">Masukkan kredensial akun Anda untuk mengakses sistem perpustakaan.</p>
        </div>

        <!-- Quick Demo Accounts Box -->
        <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200 text-xs space-y-2">
            <p class="font-bold text-slate-700 flex items-center space-x-1.5">
                <i data-lucide="key" class="w-3.5 h-3.5 text-blue-600"></i>
                <span>Akun Uji Coba Demo (Klik untuk mengisi cepat):</span>
            </p>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                <button type="button" onclick="fillCredentials('admin@perpustakaan.com', 'password123')" class="text-left p-2 rounded-xl bg-white border border-slate-200 hover:border-blue-500 hover:bg-blue-50/50 transition-all cursor-pointer">
                    <span class="font-bold text-blue-700 block">👤 Akun Admin</span>
                    <span class="text-[11px] text-slate-500 block truncate">admin@perpustakaan.com</span>
                </button>
                <button type="button" onclick="fillCredentials('budi@perpustakaan.com', 'password123')" class="text-left p-2 rounded-xl bg-white border border-slate-200 hover:border-emerald-500 hover:bg-emerald-50/50 transition-all cursor-pointer">
                    <span class="font-bold text-emerald-700 block">📖 Akun User (Anggota)</span>
                    <span class="text-[11px] text-slate-500 block truncate">budi@perpustakaan.com</span>
                </button>
            </div>
        </div>

        <form method="POST" action="{{ route('login.post') }}" class="space-y-4">
            @csrf

            <!-- Email Address -->
            <div>
                <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                    Alamat Email
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
                        autofocus
                        placeholder="nama@perpustakaan.com"
                        class="w-full pl-10 pr-4 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition-all text-slate-900 placeholder:text-slate-400"
                    >
                </div>
                @error('email')
                    <p class="text-xs text-rose-600 font-medium mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                        Kata Sandi
                    </label>
                </div>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <i data-lucide="lock" class="w-4 h-4"></i>
                    </div>
                    <input
                        id="password"
                        type="password"
                        name="password"
                        required
                        placeholder="••••••••"
                        class="w-full pl-10 pr-4 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition-all text-slate-900 placeholder:text-slate-400"
                    >
                </div>
                @error('password')
                    <p class="text-xs text-rose-600 font-medium mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Remember Me -->
            <div class="flex items-center justify-between text-xs">
                <label class="flex items-center space-x-2 cursor-pointer">
                    <input type="checkbox" name="remember" class="w-4 h-4 rounded text-blue-600 border-slate-300 focus:ring-blue-500">
                    <span class="text-slate-600 font-medium">Ingat saya di perangkat ini</span>
                </label>
            </div>

            <!-- Submit Button -->
            <button
                type="submit"
                class="w-full py-3 px-4 bg-blue-600 hover:bg-blue-700 active:scale-[0.99] text-white text-xs font-bold uppercase tracking-wider rounded-xl shadow-lg shadow-blue-600/30 transition-all flex items-center justify-center space-x-2"
            >
                <i data-lucide="log-in" class="w-4 h-4"></i>
                <span>Masuk ke Akun</span>
            </button>
        </form>

        <!-- Register Link -->
        <div class="text-center pt-2">
            <p class="text-xs text-slate-500">
                Belum terdaftar sebagai anggota perpustakaan?
                <a href="{{ route('register') }}" class="font-bold text-blue-600 hover:underline">
                    Daftar Akun Baru
                </a>
            </p>
        </div>
    </div>

    <script>
        function fillCredentials(email, password) {
            document.getElementById('email').value = email;
            document.getElementById('password').value = password;
        }
    </script>
</x-layouts.auth>
