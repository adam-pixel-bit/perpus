<x-layouts.auth title="Buat Kata Sandi Baru - SIPUS Digital">
    <div class="p-6 md:p-8 flex flex-col justify-between h-full bg-zinc-950 text-white">
        <div>
            <!-- Header Brand -->
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 rounded-xl bg-orange-500/10 border border-orange-500/30 flex items-center justify-center text-orange-500 font-bold shadow-inner">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-base font-semibold text-white tracking-tight">SIPUS Digital</h2>
                    <p class="text-xs text-zinc-400">Atur Ulang Kata Sandi</p>
                </div>
            </div>

            <div class="mb-6">
                <h1 class="text-2xl font-bold text-white tracking-tight mb-2">Buat Kata Sandi Baru</h1>
                <p class="text-sm text-zinc-400 leading-relaxed">
                    Silakan masukkan kata sandi baru yang kuat untuk akun Anda.
                </p>
            </div>

            <form action="{{ route('password.update') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <div>
                    <label class="block text-xs font-medium text-zinc-300 mb-1.5">Alamat Email Terdaftar <span class="text-orange-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email', $email) }}" required
                        class="w-full px-3 py-2.5 bg-zinc-900 border border-zinc-800 rounded-xl text-sm text-white placeholder-zinc-500 focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500 transition-colors">
                    @error('email')
                        <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-medium text-zinc-300 mb-1.5">Kata Sandi Baru <span class="text-orange-500">*</span></label>
                    <input type="password" name="password" required placeholder="Minimal 6 karakter"
                        class="w-full px-3 py-2.5 bg-zinc-900 border border-zinc-800 rounded-xl text-sm text-white placeholder-zinc-500 focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500 transition-colors">
                    @error('password')
                        <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-medium text-zinc-300 mb-1.5">Konfirmasi Kata Sandi Baru <span class="text-orange-500">*</span></label>
                    <input type="password" name="password_confirmation" required placeholder="Ulangi kata sandi baru"
                        class="w-full px-3 py-2.5 bg-zinc-900 border border-zinc-800 rounded-xl text-sm text-white placeholder-zinc-500 focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500 transition-colors">
                </div>

                <button type="submit" class="w-full py-2.5 px-4 bg-orange-600 hover:bg-orange-500 text-white font-medium text-sm rounded-xl transition shadow-lg shadow-orange-600/20 flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span>Simpan Kata Sandi Baru</span>
                </button>
            </form>
        </div>

        <div class="mt-8 pt-4 border-t border-zinc-900 text-center text-xs text-zinc-500">
            Batal ubah kata sandi? <a href="{{ route('login') }}" class="text-orange-400 hover:underline font-medium">Kembali ke login</a>
        </div>
    </div>
</x-layouts.auth>
