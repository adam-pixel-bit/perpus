<x-layouts.auth title="Lupa Kata Sandi - SIPUS Digital">
    <div class="p-6 md:p-8 flex flex-col justify-between h-full bg-zinc-950 text-white">
        <div>
            <!-- Header Brand -->
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 rounded-xl bg-orange-500/10 border border-orange-500/30 flex items-center justify-center text-orange-500 font-bold shadow-inner">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-base font-semibold text-white tracking-tight">SIPUS Digital</h2>
                    <p class="text-xs text-zinc-400">Pemulihan Akses Akun</p>
                </div>
            </div>

            <div class="mb-6">
                <h1 class="text-2xl font-bold text-white tracking-tight mb-2">Lupa Kata Sandi?</h1>
                <p class="text-sm text-zinc-400 leading-relaxed">
                    Masukkan alamat email yang terdaftar. Sistem akan membuatkan token pemulihan aman untuk memperbarui kata sandi Anda.
                </p>
            </div>

            @if (session('status'))
                <div class="p-4 mb-5 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs">
                    <div class="font-medium flex items-center gap-2 mb-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>{{ session('status') }}</span>
                    </div>
                    @if (session('demo_reset_link'))
                        <div class="mt-2 pt-2 border-t border-emerald-500/20">
                            <p class="text-[11px] text-zinc-300 mb-1">Tautan Langsung Pemulihan (Simulasi Email):</p>
                            <a href="{{ session('demo_reset_link') }}" class="underline font-mono text-orange-400 break-all hover:text-orange-300">
                                Klik di sini untuk Reset Password Sekarang &rarr;
                            </a>
                        </div>
                    @endif
                </div>
            @endif

            <form action="{{ route('password.email') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-medium text-zinc-300 mb-1.5">Alamat Email Terdaftar <span class="text-orange-500">*</span></label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-zinc-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                            </svg>
                        </span>
                        <input type="email" name="email" value="{{ old('email') }}" required placeholder="nama@perpustakaan.com"
                            class="w-full pl-9 pr-3 py-2.5 bg-zinc-900 border border-zinc-800 rounded-xl text-sm text-white placeholder-zinc-500 focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500 transition-colors">
                    </div>
                    @error('email')
                        <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="w-full py-2.5 px-4 bg-orange-600 hover:bg-orange-500 text-white font-medium text-sm rounded-xl transition shadow-lg shadow-orange-600/20 flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    <span>Kirim Tautan Pemulihan</span>
                </button>
            </form>
        </div>

        <div class="mt-8 pt-4 border-t border-zinc-900 text-center text-xs text-zinc-500">
            Ingat kata sandi Anda? <a href="{{ route('login') }}" class="text-orange-400 hover:underline font-medium">Masuk kembali</a>
        </div>
    </div>
</x-layouts.auth>
