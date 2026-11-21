<x-layouts.app title="Kelola Pengguna">
    <x-slot:header>Manajemen Pengguna & Anggota</x-slot:header>
    <x-slot:subheader>Daftar seluruh pengguna sistem, penetapan hak akses role, dan status keanggotaan</x-slot:subheader>

    <div class="bg-white rounded-3xl border border-slate-200/80 p-5 shadow-xs space-y-4">
        <!-- Search & Filter Bar -->
        <form method="GET" action="{{ route('admin.users.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
            <!-- Search Keyword -->
            <div class="relative lg:col-span-2">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <i data-lucide="search" class="w-4 h-4"></i>
                </div>
                <input
                    type="text"
                    name="search"
                    value="{{ $search }}"
                    placeholder="Cari nama, email, atau telepon..."
                    class="w-full pl-9 pr-4 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-slate-900 placeholder:text-slate-400"
                >
            </div>

            <!-- Role Filter -->
            <div>
                <select name="role" class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-slate-700">
                    <option value="all">Semua Peran (Role)</option>
                    <option value="admin" {{ $role === 'admin' ? 'selected' : '' }}>Administrator</option>
                    <option value="user" {{ $role === 'user' ? 'selected' : '' }}>Anggota Perpustakaan</option>
                </select>
            </div>

            <!-- Status Filter -->
            <div>
                <select name="status" class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-slate-700">
                    <option value="all">Semua Status Akun</option>
                    <option value="active" {{ $status === 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ $status === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>

            <!-- Submit Filter & Reset -->
            <div class="flex items-center space-x-2">
                <button type="submit" class="flex-1 py-2 bg-slate-800 hover:bg-slate-900 text-white text-xs font-bold rounded-xl transition-colors">
                    Filter
                </button>
                @if($search || ($role && $role !== 'all') || ($status && $status !== 'all'))
                    <a href="{{ route('admin.users.index') }}" class="p-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl transition-colors" title="Reset Filter">
                        <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50/75 border-b border-slate-200 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                        <th class="py-3 px-4">Pengguna</th>
                        <th class="py-3 px-4">Kontak & Alamat</th>
                        <th class="py-3 px-4">Peran (Role)</th>
                        <th class="py-3 px-4">Status Akun</th>
                        <th class="py-3 px-4">Total Pinjaman</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($users as $u)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="py-3.5 px-4">
                                <div class="flex items-center space-x-3">
                                    <div class="w-9 h-9 rounded-xl bg-gradient-to-tr {{ $u->isAdmin() ? 'from-indigo-600 to-purple-600' : 'from-blue-600 to-teal-500' }} text-white font-bold flex items-center justify-center text-xs flex-shrink-0 shadow-xs">
                                        {{ strtoupper(substr($u->name, 0, 1)) }}
                                    </div>
                                    <div class="min-w-0 max-w-xs">
                                        <a href="{{ route('admin.users.show', $u->id) }}" class="font-bold text-slate-900 hover:text-blue-600 transition-colors block truncate">
                                            {{ $u->name }}
                                        </a>
                                        <span class="text-[11px] text-slate-400 block truncate">{{ $u->email }}</span>
                                    </div>
                                </div>
                            </td>

                            <td class="py-3.5 px-4">
                                <span class="font-medium text-slate-800 block">{{ $u->phone ?: '-' }}</span>
                                <span class="text-[11px] text-slate-400 block truncate max-w-xs">{{ $u->address ?: '-' }}</span>
                            </td>

                            <td class="py-3.5 px-4">
                                @if($u->isAdmin())
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-purple-50 text-purple-700 border border-purple-200 uppercase tracking-wider">
                                        Admin
                                    </span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-700 uppercase tracking-wider">
                                        Anggota
                                    </span>
                                @endif
                            </td>

                            <td class="py-3.5 px-4">
                                @if($u->is_active)
                                    <span class="inline-flex items-center space-x-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        <span>Aktif</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center space-x-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                        <span>Nonaktif</span>
                                    </span>
                                @endif
                            </td>

                            <td class="py-3.5 px-4">
                                <span class="font-bold text-slate-800">{{ $u->borrowings_count }} Transaksi</span>
                            </td>

                            <td class="py-3.5 px-4 text-right">
                                <div class="inline-flex items-center space-x-1">
                                    <a href="{{ route('admin.users.show', $u->id) }}" class="p-1.5 text-slate-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Lihat Rincian & Riwayat">
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                    </a>
                                    <a href="{{ route('admin.users.edit', $u->id) }}" class="p-1.5 text-slate-500 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-colors" title="Edit Profil">
                                        <i data-lucide="edit-3" class="w-4 h-4"></i>
                                    </a>

                                    @if($u->id !== auth()->id())
                                        <form method="POST" action="{{ route('admin.users.toggleStatus', $u->id) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button
                                                type="submit"
                                                class="p-1.5 {{ $u->is_active ? 'text-slate-500 hover:text-rose-600 hover:bg-rose-50' : 'text-slate-500 hover:text-emerald-600 hover:bg-emerald-50' }} rounded-lg transition-colors"
                                                title="{{ $u->is_active ? 'Nonaktifkan Akun' : 'Aktifkan Akun' }}"
                                            >
                                                <i data-lucide="{{ $u->is_active ? 'user-x' : 'user-check' }}" class="w-4 h-4"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400">Tidak ada data pengguna yang sesuai.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="p-4 border-t border-slate-200 bg-slate-50/50">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</x-layouts.app>
