<x-layouts.app title="Edit Pengguna">
    <x-slot:header>Edit Data Pengguna</x-slot:header>
    <x-slot:subheader>Perbarui informasi akun, hak akses role, atau status aktif pengguna</x-slot:subheader>

    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs p-6 sm:p-8">
            <div class="flex items-center justify-between pb-6 mb-6 border-b border-slate-100">
                <div>
                    <h3 class="font-extrabold text-lg text-slate-900">Perbarui Profil: {{ $user->name }}</h3>
                    <p class="text-xs text-slate-500">{{ $user->email }}</p>
                </div>
                <a href="{{ route('admin.users.index') }}" class="inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold text-slate-600 hover:text-slate-900 bg-slate-100 hover:bg-slate-200 transition-colors">
                    <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                    <span>Kembali</span>
                </a>
            </div>

            <form method="POST" action="{{ route('admin.users.update', $user->id) }}" class="space-y-4">
                @csrf
                @method('PUT')

                <!-- Name & Email -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="name" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Nama Lengkap <span class="text-rose-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name', $user->name) }}"
                            required
                            class="w-full px-4 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-slate-900"
                        >
                        @error('name')
                            <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Alamat Email <span class="text-rose-500">*</span>
                        </label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email', $user->email) }}"
                            required
                            class="w-full px-4 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-slate-900"
                        >
                        @error('email')
                            <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Phone & Address -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="phone" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Nomor Telepon
                        </label>
                        <input
                            type="text"
                            id="phone"
                            name="phone"
                            value="{{ old('phone', $user->phone) }}"
                            class="w-full px-4 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-slate-900"
                        >
                    </div>

                    <div>
                        <label for="address" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Alamat Domisili
                        </label>
                        <input
                            type="text"
                            id="address"
                            name="address"
                            value="{{ old('address', $user->address) }}"
                            class="w-full px-4 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-slate-900"
                        >
                    </div>
                </div>

                <!-- Role & Status -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                    <div>
                        <label for="role" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Peran Pengguna (Role) <span class="text-rose-500">*</span>
                        </label>
                        <select
                            id="role"
                            name="role"
                            required
                            class="w-full px-4 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-slate-800"
                        >
                            <option value="user" {{ old('role', $user->role) === 'user' ? 'selected' : '' }}>Anggota Perpustakaan</option>
                            <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Administrator</option>
                        </select>
                    </div>

                    <div>
                        <label for="is_active" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Status Akun <span class="text-rose-500">*</span>
                        </label>
                        <select
                            id="is_active"
                            name="is_active"
                            required
                            class="w-full px-4 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-slate-800"
                        >
                            <option value="1" {{ old('is_active', $user->is_active) ? 'selected' : '' }}>Aktif (Dapat Login & Meminjam)</option>
                            <option value="0" {{ !old('is_active', $user->is_active) ? 'selected' : '' }}>Nonaktif (Dibekukan)</option>
                        </select>
                    </div>
                </div>

                <!-- Password (Optional) -->
                <div class="pt-2">
                    <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                        Ganti Kata Sandi (Kosongkan jika tidak ingin mengubah)
                    </label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="••••••••"
                        class="w-full px-4 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-slate-900"
                    >
                </div>

                <!-- Submit Buttons -->
                <div class="pt-6 border-t border-slate-100 flex items-center justify-end space-x-3">
                    <a href="{{ route('admin.users.index') }}" class="px-5 py-2.5 text-xs font-bold text-slate-600 hover:text-slate-900 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors">
                        Batal
                    </a>
                    <button
                        type="submit"
                        class="inline-flex items-center space-x-2 px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-lg shadow-blue-600/30 transition-all"
                    >
                        <i data-lucide="check" class="w-4 h-4"></i>
                        <span>Simpan Perubahan Pengguna</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
