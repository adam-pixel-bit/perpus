<x-layouts.auth title="Pendaftaran Anggota — Uxerflow Library OS">
    <div class="space-y-4">
        <div>
            <h2 class="text-xl font-bold text-zinc-900 tracking-tight">Create Member Account</h2>
            <p class="text-xs text-zinc-400 mt-0.5">Register for your personal digital library membership</p>
        </div>

        <form method="POST" action="{{ route('register.post') }}" class="space-y-3">
            @csrf

            <!-- Name -->
            <div>
                <label for="name" class="block text-[11px] font-semibold text-zinc-700 mb-1">
                    Full Name <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-zinc-400">
                        <i data-lucide="user" class="w-3.5 h-3.5"></i>
                    </div>
                    <input
                        id="name"
                        type="text"
                        name="name"
                        value="{{ old('name') }}"
                        required
                        autofocus
                        placeholder="e.g. Budi Santoso"
                        class="w-full pl-8.5 pr-3.5 py-1.5 text-xs bg-zinc-50/70 border border-zinc-200/80 rounded-xl focus:outline-none focus:ring-1 focus:ring-zinc-400 focus:bg-white text-zinc-900 placeholder-zinc-400"
                    >
                </div>
                @error('name')
                    <p class="text-[11px] text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-[11px] font-semibold text-zinc-700 mb-1">
                    Email Address <span class="text-red-500">*</span>
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
                        placeholder="budi@example.com"
                        class="w-full pl-8.5 pr-3.5 py-1.5 text-xs bg-zinc-50/70 border border-zinc-200/80 rounded-xl focus:outline-none focus:ring-1 focus:ring-zinc-400 focus:bg-white text-zinc-900 placeholder-zinc-400"
                    >
                </div>
                @error('email')
                    <p class="text-[11px] text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Phone & Address -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                <div>
                    <label for="phone" class="block text-[11px] font-semibold text-zinc-700 mb-1">
                        Phone Number
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-zinc-400">
                            <i data-lucide="phone" class="w-3.5 h-3.5"></i>
                        </div>
                        <input
                            id="phone"
                            type="text"
                            name="phone"
                            value="{{ old('phone') }}"
                            placeholder="08123456789"
                            class="w-full pl-8.5 pr-3.5 py-1.5 text-xs bg-zinc-50/70 border border-zinc-200/80 rounded-xl focus:outline-none focus:ring-1 focus:ring-zinc-400 focus:bg-white text-zinc-900 placeholder-zinc-400"
                        >
                    </div>
                </div>

                <div>
                    <label for="address" class="block text-[11px] font-semibold text-zinc-700 mb-1">
                        Home City / Address
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-zinc-400">
                            <i data-lucide="map-pin" class="w-3.5 h-3.5"></i>
                        </div>
                        <input
                            id="address"
                            type="text"
                            name="address"
                            value="{{ old('address') }}"
                            placeholder="Jakarta Selatan"
                            class="w-full pl-8.5 pr-3.5 py-1.5 text-xs bg-zinc-50/70 border border-zinc-200/80 rounded-xl focus:outline-none focus:ring-1 focus:ring-zinc-400 focus:bg-white text-zinc-900 placeholder-zinc-400"
                        >
                    </div>
                </div>
            </div>

            <!-- Password & Confirmation -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                <div>
                    <label for="password" class="block text-[11px] font-semibold text-zinc-700 mb-1">
                        Password <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-zinc-400">
                            <i data-lucide="lock" class="w-3.5 h-3.5"></i>
                        </div>
                        <input
                            id="password"
                            type="password"
                            name="password"
                            required
                            placeholder="Min 6 characters"
                            class="w-full pl-8.5 pr-3.5 py-1.5 text-xs bg-zinc-50/70 border border-zinc-200/80 rounded-xl focus:outline-none focus:ring-1 focus:ring-zinc-400 focus:bg-white text-zinc-900 placeholder-zinc-400"
                        >
                    </div>
                    @error('password')
                        <p class="text-[11px] text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-[11px] font-semibold text-zinc-700 mb-1">
                        Confirm Password <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-zinc-400">
                            <i data-lucide="shield-check" class="w-3.5 h-3.5"></i>
                        </div>
                        <input
                            id="password_confirmation"
                            type="password"
                            name="password_confirmation"
                            required
                            placeholder="Repeat password"
                            class="w-full pl-8.5 pr-3.5 py-1.5 text-xs bg-zinc-50/70 border border-zinc-200/80 rounded-xl focus:outline-none focus:ring-1 focus:ring-zinc-400 focus:bg-white text-zinc-900 placeholder-zinc-400"
                        >
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <button
                type="submit"
                class="w-full py-2 px-4 bg-zinc-950 hover:bg-zinc-800 text-white text-xs font-semibold rounded-xl shadow-2xs transition-all flex items-center justify-center space-x-1.5 mt-3 cursor-pointer"
            >
                <i data-lucide="user-plus" class="w-3.5 h-3.5"></i>
                <span>Complete Registration</span>
            </button>
        </form>

        <div class="text-center pt-1">
            <p class="text-xs text-zinc-400">
                Already registered?
                <a href="{{ route('login') }}" class="font-semibold text-zinc-900 hover:text-brand-orange transition-colors">
                    Sign in here
                </a>
            </p>
        </div>
    </div>
</x-layouts.auth>
