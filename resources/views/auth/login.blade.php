<x-layouts.auth title="Masuk — Uxerflow Library OS">
    <div class="space-y-5">
        <div>
            <h2 class="text-xl font-bold text-zinc-900 tracking-tight">Selamat Datang Kembali</h2>
            <p class="text-xs text-zinc-400 mt-0.5">Sign in to your library account</p>
        </div>

        <!-- Quick Demo Accounts Box -->
        <div class="p-3 rounded-xl bg-zinc-50 border border-zinc-200/80 text-xs space-y-2">
            <p class="font-semibold text-zinc-700 flex items-center space-x-1.5 text-[11px]">
                <i data-lucide="key" class="w-3.5 h-3.5 text-brand-orange"></i>
                <span>Demo Accounts (Click to autofill):</span>
            </p>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                <button type="button" onclick="fillCredentials('admin@perpustakaan.com', 'password123')" class="text-left p-2 rounded-lg bg-white border border-zinc-200/80 hover:border-zinc-400 transition-all cursor-pointer shadow-2xs">
                    <span class="font-semibold text-zinc-900 block text-xs">👤 Administrator</span>
                    <span class="text-[10px] text-zinc-400 block truncate">admin@perpustakaan.com</span>
                </button>
                <button type="button" onclick="fillCredentials('budi@perpustakaan.com', 'password123')" class="text-left p-2 rounded-lg bg-white border border-zinc-200/80 hover:border-zinc-400 transition-all cursor-pointer shadow-2xs">
                    <span class="font-semibold text-zinc-900 block text-xs">📖 Member (User)</span>
                    <span class="text-[10px] text-zinc-400 block truncate">budi@perpustakaan.com</span>
                </button>
            </div>
        </div>

        <form method="POST" action="{{ route('login.post') }}" class="space-y-3.5">
            @csrf

            <!-- Email Address -->
            <div>
                <label for="email" class="block text-[11px] font-semibold text-zinc-700 mb-1">
                    Email Address
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-zinc-400">
                        <i data-lucide="mail" class="w-3.5 h-3.5"></i>
                    </div>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        placeholder="name@example.com"
                        class="w-full pl-8.5 pr-3.5 py-1.5 text-xs bg-zinc-50/70 border border-zinc-200/80 rounded-xl focus:outline-none focus:ring-1 focus:ring-zinc-400 focus:bg-white text-zinc-900 placeholder-zinc-400"
                    >
                </div>
                @error('email')
                    <p class="text-[11px] text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <div class="flex items-center justify-between mb-1">
                    <label for="password" class="block text-[11px] font-semibold text-zinc-700">
                        Password
                    </label>
                </div>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-zinc-400">
                        <i data-lucide="lock" class="w-3.5 h-3.5"></i>
                    </div>
                    <input
                        id="password"
                        type="password"
                        name="password"
                        required
                        placeholder="••••••••"
                        class="w-full pl-8.5 pr-3.5 py-1.5 text-xs bg-zinc-50/70 border border-zinc-200/80 rounded-xl focus:outline-none focus:ring-1 focus:ring-zinc-400 focus:bg-white text-zinc-900 placeholder-zinc-400"
                    >
                </div>
                @error('password')
                    <p class="text-[11px] text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Remember Me -->
            <div class="flex items-center justify-between text-xs">
                <label class="flex items-center space-x-2 cursor-pointer">
                    <input type="checkbox" name="remember" class="w-3.5 h-3.5 rounded border-zinc-300 text-brand-orange focus:ring-0">
                    <span class="text-zinc-600 text-xs font-medium">Keep me signed in</span>
                </label>
            </div>

            <!-- Submit Button -->
            <button
                type="submit"
                class="w-full py-2 px-4 bg-zinc-950 hover:bg-zinc-800 text-white text-xs font-semibold rounded-xl shadow-2xs transition-all flex items-center justify-center space-x-1.5 cursor-pointer"
            >
                <i data-lucide="log-in" class="w-3.5 h-3.5"></i>
                <span>Sign in to Dashboard</span>
            </button>
        </form>

        <!-- Register Link -->
        <div class="text-center pt-2">
            <p class="text-xs text-zinc-400">
                Don't have a library card yet?
                <a href="{{ route('register') }}" class="font-semibold text-zinc-900 hover:text-brand-orange transition-colors">
                    Register Member
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
