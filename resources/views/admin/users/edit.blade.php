<x-layouts.app title="Edit Pengguna">
    <x-slot:pageTitle>Edit Data Pengguna</x-slot:pageTitle>

    <div class="max-w-2xl mx-auto space-y-4">
        <div class="flex items-center justify-between pb-2 border-b border-zinc-200/70">
            <div>
                <h3 class="font-bold text-sm text-zinc-900">Perbarui Profil Pengguna</h3>
                <p class="text-xs text-zinc-400 mt-0.5">{{ $user->email }}</p>
            </div>
            <a href="{{ route('admin.users.index') }}" class="inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold text-zinc-600 hover:text-zinc-900 bg-white border border-zinc-200/80 shadow-2xs transition-colors">
                <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                <span>Kembali</span>
            </a>
        </div>

        <div class="bg-white rounded-2xl border border-zinc-200/80 shadow-2xs p-5 sm:p-6">
            <form method="POST" action="{{ route('admin.users.update', $user->id) }}" class="space-y-4">
                @csrf
                @method('PUT')

                <!-- Name & Email -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                    <div>
                        <label for="name" class="block text-[11px] font-semibold text-zinc-700 mb-1">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name', $user->name) }}"
                            required
                            class="w-full px-3 py-1.5 text-xs bg-zinc-50/70 border border-zinc-200/80 rounded-xl focus:outline-none focus:ring-1 focus:ring-zinc-400 focus:bg-white text-zinc-900"
                        >
                        @error('name')
                            <p class="text-[11px] text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-[11px] font-semibold text-zinc-700 mb-1">
                            Alamat Email <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email', $user->email) }}"
                            required
                            class="w-full px-3 py-1.5 text-xs bg-zinc-50/70 border border-zinc-200/80 rounded-xl focus:outline-none focus:ring-1 focus:ring-zinc-400 focus:bg-white text-zinc-900"
                        >
                        @error('email')
                            <p class="text-[11px] text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Phone & Address -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                    <div>
                        <label for="phone" class="block text-[11px] font-semibold text-zinc-700 mb-1">
                            Nomor Telepon / WA
                        </label>
                        <input
                            type="text"
                            id="phone"
                            name="phone"
                            value="{{ old('phone', $user->phone) }}"
                            class="w-full px-3 py-1.5 text-xs bg-zinc-50/70 border border-zinc-200/80 rounded-xl focus:outline-none focus:ring-1 focus:ring-zinc-400 focus:bg-white text-zinc-900"
                        >
                    </div>

                    <div>
                        <label for="address" class="block text-[11px] font-semibold text-zinc-700 mb-1">
                            Alamat Domisili
                        </label>
                        <input
                            type="text"
                            id="address"
                            name="address"
                            value="{{ old('address', $user->address) }}"
                            class="w-full px-3 py-1.5 text-xs bg-zinc-50/70 border border-zinc-200/80 rounded-xl focus:outline-none focus:ring-1 focus:ring-zinc-400 focus:bg-white text-zinc-900"
                        >
                    </div>
                </div>

                <!-- Role & Status -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 pt-1">
                    <div>
                        <label for="role" class="block text-[11px] font-semibold text-zinc-700 mb-1">
                            Peran Pengguna (Role) <span class="text-red-500">*</span>
                        </label>
                        <select
                            id="role"
                            name="role"
                            required
                            class="w-full px-3 py-1.5 text-xs bg-zinc-50/70 border border-zinc-200/80 rounded-xl focus:outline-none focus:ring-1 focus:ring-zinc-400 focus:bg-white text-zinc-800"
                        >
                            <option value="user" {{ old('role', $user->role) === 'user' ? 'selected' : '' }}>Anggota Perpustakaan</option>
                            <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Administrator</option>
                        </select>
                    </div>

                    <div>
                        <label for="is_active" class="block text-[11px] font-semibold text-zinc-700 mb-1">
                            Status Akun <span class="text-red-500">*</span>
                        </label>
                        <select
                            id="is_active"
                            name="is_active"
                            required
                            class="w-full px-3 py-1.5 text-xs bg-zinc-50/70 border border-zinc-200/80 rounded-xl focus:outline-none focus:ring-1 focus:ring-zinc-400 focus:bg-white text-zinc-800"
                        >
                            <option value="1" {{ old('is_active', $user->is_active) ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{ !old('is_active', $user->is_active) ? 'selected' : '' }}>Nonaktif (Dibekukan)</option>
                        </select>
                    </div>
                </div>

                <!-- Password (Optional) -->
                <div>
                    <label for="password" class="block text-[11px] font-semibold text-zinc-700 mb-1">
                        Ganti Kata Sandi (Kosongkan jika tidak ingin mengubah)
                    </label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="••••••••"
                        class="w-full px-3 py-1.5 text-xs bg-zinc-50/70 border border-zinc-200/80 rounded-xl focus:outline-none focus:ring-1 focus:ring-zinc-400 focus:bg-white text-zinc-900"
                    >
                </div>

                <!-- Submit Buttons -->
                <div class="pt-4 border-t border-zinc-100 flex items-center justify-end space-x-2">
                    <a href="{{ route('admin.users.index') }}" class="px-3.5 py-1.5 text-xs font-semibold text-zinc-600 hover:text-zinc-900 bg-zinc-100 hover:bg-zinc-200 rounded-xl transition-colors">
                        Batal
                    </a>
                    <button
                        type="submit"
                        class="inline-flex items-center space-x-1.5 px-4 py-1.5 bg-zinc-950 hover:bg-zinc-800 text-white text-xs font-semibold rounded-xl shadow-2xs transition-all cursor-pointer"
                    >
                        <i data-lucide="check" class="w-3.5 h-3.5"></i>
                        <span>Simpan Perubahan</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
