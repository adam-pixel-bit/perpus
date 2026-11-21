<x-layouts.app title="Profil Saya - SIPUS Digital">
    <x-slot:pageTitle>Pengaturan Profil & Akun</x-slot:pageTitle>

    <!-- Subheader Toolbar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-2 border-b border-zinc-200/70">
        <div class="flex items-center space-x-2 text-xs text-zinc-600 font-medium">
            <div class="flex items-center space-x-1.5 px-3 py-1.5 bg-white border border-zinc-200/80 rounded-xl shadow-2xs">
                <i data-lucide="user-cog" class="w-3.5 h-3.5 text-zinc-500"></i>
                <span class="font-semibold text-zinc-800">Profil & Keamanan Akun</span>
            </div>
            <span class="text-xs text-zinc-400">Kelola identitas, kata sandi, dan kartu anggota</span>
        </div>

        @if($user->isUser())
            <a href="{{ route('profile.card') }}" target="_blank" class="flex items-center space-x-1.5 px-3 py-1.5 text-xs font-semibold text-white bg-zinc-950 hover:bg-black rounded-xl shadow-2xs transition-all">
                <i data-lucide="printer" class="w-3.5 h-3.5 text-orange-400"></i>
                <span>Cetak Kartu Anggota</span>
            </a>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        <!-- Left Column: Profile Card & Digital Membership Card (5 cols) -->
        <div class="lg:col-span-5 space-y-6">
            <!-- Digital Member ID Card (Apple Wallet / Physical Card Aesthetic) -->
            @if($user->isUser())
                <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-zinc-900 via-zinc-950 to-black p-6 text-white shadow-xl border border-zinc-800">
                    <!-- Background Watermark / Accent Glow -->
                    <div class="absolute -right-8 -bottom-8 w-40 h-40 bg-orange-600/20 rounded-full blur-2xl pointer-events-none"></div>
                    <div class="absolute -left-8 -top-8 w-40 h-40 bg-blue-600/10 rounded-full blur-2xl pointer-events-none"></div>

                    <!-- Header -->
                    <div class="flex items-center justify-between pb-4 border-b border-zinc-800/80 relative z-10">
                        <div class="flex items-center space-x-2.5">
                            <div class="w-8 h-8 rounded-xl bg-orange-500/20 border border-orange-500/30 flex items-center justify-center text-orange-500 font-bold text-xs">
                                S
                            </div>
                            <div>
                                <h3 class="font-bold text-xs text-white tracking-wide">SIPUS DIGITAL</h3>
                                <p class="text-[9px] text-zinc-400 uppercase tracking-widest font-mono">KARTU ANGGOTA PERPUSTAKAAN</p>
                            </div>
                        </div>
                        <span class="px-2 py-0.5 rounded-full text-[9px] font-bold bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">
                            AKTIF
                        </span>
                    </div>

                    <!-- Body -->
                    <div class="my-5 flex items-center gap-4 relative z-10">
                        <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-16 h-16 rounded-2xl object-cover border-2 border-zinc-700 shadow-md">
                        <div class="min-w-0">
                            <h4 class="font-bold text-base text-white truncate">{{ $user->name }}</h4>
                            <p class="text-xs font-mono text-orange-400 font-semibold mt-0.5">{{ $user->member_code }}</p>
                            <p class="text-[10px] text-zinc-400 truncate mt-0.5">{{ $user->email }}</p>
                        </div>
                    </div>

                    <!-- Card Footer: Barcode + Details -->
                    <div class="pt-4 border-t border-zinc-800/80 flex items-end justify-between relative z-10">
                        <div>
                            <span class="text-[9px] text-zinc-500 block uppercase font-mono">Tgl Bergabung</span>
                            <span class="text-xs text-zinc-300 font-semibold">{{ $user->created_at->format('d/m/Y') }}</span>
                        </div>
                        <div class="text-right">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=65x65&data={{ urlencode($user->member_code) }}" alt="QR ID" class="w-12 h-12 rounded-lg bg-white p-1 ml-auto shadow-xs">
                        </div>
                    </div>
                </div>
            @endif

            <!-- Account Summary Stats -->
            <div class="bg-white rounded-2xl border border-zinc-200/80 p-5 shadow-2xs space-y-4">
                <div class="flex items-center space-x-3.5 pb-4 border-b border-zinc-100">
                    <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-14 h-14 rounded-2xl object-cover border border-zinc-200 shadow-xs">
                    <div>
                        <h3 class="font-bold text-sm text-zinc-900">{{ $user->name }}</h3>
                        <p class="text-xs text-zinc-400 font-mono">{{ $user->email }}</p>
                        <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-bold mt-1 {{ $user->isAdmin() ? 'bg-orange-100 text-orange-700' : 'bg-zinc-100 text-zinc-700' }}">
                            {{ $user->isAdmin() ? 'Administrator Sistem' : 'Anggota Resmi' }}
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3 text-xs">
                    <div class="p-3 bg-zinc-50 rounded-xl border border-zinc-100">
                        <span class="text-[10px] text-zinc-400 block font-medium">Buku Sedang Dipinjam</span>
                        <span class="text-base font-bold text-zinc-900">{{ $activeBorrowings }} Buku</span>
                    </div>
                    <div class="p-3 bg-zinc-50 rounded-xl border border-zinc-100">
                        <span class="text-[10px] text-zinc-400 block font-medium">Total Ulasan Buku</span>
                        <span class="text-base font-bold text-zinc-900">{{ $user->reviews_count }} Ulasan</span>
                    </div>
                </div>

                <div class="text-[11px] text-zinc-500 space-y-1.5 pt-1">
                    <div class="flex justify-between">
                        <span class="text-zinc-400">Nomor Telepon:</span>
                        <span class="font-medium text-zinc-800">{{ $user->phone ?? 'Belum diisi' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-zinc-400">Alamat:</span>
                        <span class="font-medium text-zinc-800 text-right truncate max-w-[180px]">{{ $user->address ?? 'Belum diisi' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Profile & Security Edit Forms (7 cols) -->
        <div class="lg:col-span-7 space-y-6">
            <!-- 1. Edit Profile Form -->
            <div class="bg-white rounded-2xl border border-zinc-200/80 p-5 sm:p-6 shadow-2xs space-y-4">
                <div class="pb-3 border-b border-zinc-100">
                    <h3 class="text-sm font-bold text-zinc-900 flex items-center gap-2">
                        <i data-lucide="user" class="w-4 h-4 text-orange-600"></i>
                        <span>Informasi Data Diri</span>
                    </h3>
                    <p class="text-xs text-zinc-400 mt-0.5">Perbarui nama lengkap, email, nomor kontak, dan foto profil akun.</p>
                </div>

                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-4 text-xs">
                    @csrf
                    @method('PUT')

                    <!-- Photo Upload -->
                    <div>
                        <label class="block text-xs font-semibold text-zinc-700 mb-1.5">Foto Profil / Avatar</label>
                        <div class="flex items-center space-x-3.5">
                            <img id="avatar-preview" src="{{ $user->avatar_url }}" alt="Preview" class="w-12 h-12 rounded-xl object-cover border border-zinc-200 shadow-2xs">
                            <div class="flex-1">
                                <input
                                    type="file"
                                    name="avatar"
                                    accept="image/png,image/jpeg,image/webp"
                                    onchange="previewAvatar(event)"
                                    class="w-full text-xs text-zinc-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-zinc-100 file:text-zinc-700 hover:file:bg-zinc-200 cursor-pointer"
                                >
                                <p class="text-[10px] text-zinc-400 mt-1">Format JPG, PNG, atau WEBP. Maksimal 2MB.</p>
                            </div>
                        </div>
                        @error('avatar')
                            <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                        <!-- Name -->
                        <div>
                            <label class="block text-xs font-semibold text-zinc-700 mb-1.5">Nama Lengkap <span class="text-rose-500">*</span></label>
                            <input
                                type="text"
                                name="name"
                                value="{{ old('name', $user->name) }}"
                                required
                                class="w-full px-3 py-2 text-xs bg-zinc-50 border border-zinc-200/80 rounded-xl focus:outline-none focus:ring-1 focus:ring-orange-500 focus:bg-white text-zinc-900"
                            >
                            @error('name')
                                <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label class="block text-xs font-semibold text-zinc-700 mb-1.5">Alamat Email <span class="text-rose-500">*</span></label>
                            <input
                                type="email"
                                name="email"
                                value="{{ old('email', $user->email) }}"
                                required
                                class="w-full px-3 py-2 text-xs bg-zinc-50 border border-zinc-200/80 rounded-xl focus:outline-none focus:ring-1 focus:ring-orange-500 focus:bg-white text-zinc-900"
                            >
                            @error('email')
                                <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Phone -->
                        <div>
                            <label class="block text-xs font-semibold text-zinc-700 mb-1.5">Nomor Telepon / WhatsApp</label>
                            <input
                                type="text"
                                name="phone"
                                value="{{ old('phone', $user->phone) }}"
                                placeholder="08xxxxxxxxxx"
                                class="w-full px-3 py-2 text-xs bg-zinc-50 border border-zinc-200/80 rounded-xl focus:outline-none focus:ring-1 focus:ring-orange-500 focus:bg-white text-zinc-900"
                            >
                        </div>

                        <!-- Member Code (Read-Only) -->
                        <div>
                            <label class="block text-xs font-semibold text-zinc-700 mb-1.5">Kode Anggota</label>
                            <input
                                type="text"
                                value="{{ $user->member_code }}"
                                readonly
                                class="w-full px-3 py-2 text-xs bg-zinc-100 border border-zinc-200/80 rounded-xl text-zinc-500 font-mono font-semibold cursor-not-allowed"
                            >
                        </div>
                    </div>

                    <!-- Address -->
                    <div>
                        <label class="block text-xs font-semibold text-zinc-700 mb-1.5">Alamat Domisili</label>
                        <textarea
                            name="address"
                            rows="2"
                            placeholder="Alamat lengkap tempat tinggal..."
                            class="w-full px-3 py-2 text-xs bg-zinc-50 border border-zinc-200/80 rounded-xl focus:outline-none focus:ring-1 focus:ring-orange-500 focus:bg-white text-zinc-900"
                        >{{ old('address', $user->address) }}</textarea>
                    </div>

                    <div class="pt-2 flex justify-end">
                        <button type="submit" class="px-4 py-2 bg-zinc-950 hover:bg-black text-white text-xs font-bold rounded-xl shadow-xs transition cursor-pointer">
                            Simpan Perubahan Profil
                        </button>
                    </div>
                </form>
            </div>

            <!-- 2. Change Password Form -->
            <div class="bg-white rounded-2xl border border-zinc-200/80 p-5 sm:p-6 shadow-2xs space-y-4">
                <div class="pb-3 border-b border-zinc-100">
                    <h3 class="text-sm font-bold text-zinc-900 flex items-center gap-2">
                        <i data-lucide="lock" class="w-4 h-4 text-orange-600"></i>
                        <span>Keamanan & Kata Sandi</span>
                    </h3>
                    <p class="text-xs text-zinc-400 mt-0.5">Ganti kata sandi secara berkala untuk menjaga keamanan akun Anda.</p>
                </div>

                <form method="POST" action="{{ route('profile.password') }}" class="space-y-3.5 text-xs">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-xs font-semibold text-zinc-700 mb-1.5">Kata Sandi Saat Ini <span class="text-rose-500">*</span></label>
                        <input
                            type="password"
                            name="current_password"
                            required
                            placeholder="••••••••••••"
                            class="w-full px-3 py-2 text-xs bg-zinc-50 border border-zinc-200/80 rounded-xl focus:outline-none focus:ring-1 focus:ring-orange-500 focus:bg-white text-zinc-900"
                        >
                        @error('current_password')
                            <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                        <div>
                            <label class="block text-xs font-semibold text-zinc-700 mb-1.5">Kata Sandi Baru <span class="text-rose-500">*</span></label>
                            <input
                                type="password"
                                name="password"
                                required
                                placeholder="Minimal 6 karakter"
                                class="w-full px-3 py-2 text-xs bg-zinc-50 border border-zinc-200/80 rounded-xl focus:outline-none focus:ring-1 focus:ring-orange-500 focus:bg-white text-zinc-900"
                            >
                            @error('password')
                                <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-zinc-700 mb-1.5">Konfirmasi Kata Sandi Baru <span class="text-rose-500">*</span></label>
                            <input
                                type="password"
                                name="password_confirmation"
                                required
                                placeholder="Ulangi kata sandi baru"
                                class="w-full px-3 py-2 text-xs bg-zinc-50 border border-zinc-200/80 rounded-xl focus:outline-none focus:ring-1 focus:ring-orange-500 focus:bg-white text-zinc-900"
                            >
                        </div>
                    </div>

                    <div class="pt-2 flex justify-end">
                        <button type="submit" class="px-4 py-2 bg-zinc-950 hover:bg-black text-white text-xs font-bold rounded-xl shadow-xs transition cursor-pointer">
                            Perbarui Kata Sandi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function previewAvatar(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('avatar-preview').src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        }
    </script>
</x-layouts.app>
