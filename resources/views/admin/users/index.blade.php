<x-layouts.app title="Kelola Pengguna">
    <x-slot:pageTitle>Data Pengguna & Anggota</x-slot:pageTitle>

    <!-- Subheader Toolbar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-2 border-b border-zinc-200/70">
        <div class="flex items-center space-x-2 text-xs text-zinc-600 font-medium">
            <div class="flex items-center space-x-1.5 px-3 py-1.5 bg-white border border-zinc-200/80 rounded-xl shadow-2xs">
                <i data-lucide="users" class="w-3.5 h-3.5 text-zinc-500"></i>
                <span class="font-semibold text-zinc-800">Direktori Anggota</span>
            </div>
            <span class="text-xs text-zinc-400">Total {{ $users->total() }} akun terdaftar</span>
        </div>
    </div>

    <!-- Filter & Search Bar -->
    <div class="bg-white rounded-2xl border border-zinc-200/80 p-3 sm:p-4 shadow-2xs">
        <form method="GET" action="{{ route('admin.users.index') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-2.5">
            <!-- Search Keyword -->
            <div class="relative lg:col-span-2">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-zinc-400">
                    <i data-lucide="search" class="w-3.5 h-3.5"></i>
                </div>
                <input
                    type="text"
                    name="search"
                    value="{{ $search }}"
                    placeholder="Cari nama, email, atau no. telepon..."
                    class="w-full pl-8.5 pr-4 py-1.5 text-xs bg-zinc-50/70 border border-zinc-200/80 rounded-xl focus:outline-none focus:ring-1 focus:ring-zinc-400 focus:bg-white text-zinc-900 placeholder-zinc-400"
                >
            </div>

            <!-- Role Filter -->
            <div>
                <select name="role" class="w-full px-3 py-1.5 text-xs bg-zinc-50/70 border border-zinc-200/80 rounded-xl focus:outline-none focus:ring-1 focus:ring-zinc-400 focus:bg-white text-zinc-700">
                    <option value="all">Semua Peran (Role)</option>
                    <option value="admin" {{ $role === 'admin' ? 'selected' : '' }}>Administrator</option>
                    <option value="user" {{ $role === 'user' ? 'selected' : '' }}>Anggota Perpustakaan</option>
                </select>
            </div>

            <!-- Status Filter -->
            <div>
                <select name="status" class="w-full px-3 py-1.5 text-xs bg-zinc-50/70 border border-zinc-200/80 rounded-xl focus:outline-none focus:ring-1 focus:ring-zinc-400 focus:bg-white text-zinc-700">
                    <option value="all">Semua Status Akun</option>
                    <option value="active" {{ $status === 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ $status === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>

            <!-- Submit Filter & Reset -->
            <div class="flex items-center space-x-2">
                <button type="submit" class="flex-1 py-1.5 bg-zinc-950 hover:bg-zinc-800 text-white text-xs font-semibold rounded-xl transition-colors shadow-2xs cursor-pointer">
                    Filter
                </button>
                @if($search || ($role && $role !== 'all') || ($status && $status !== 'all'))
                    <a href="{{ route('admin.users.index') }}" class="p-1.5 bg-zinc-100 hover:bg-zinc-200 text-zinc-600 rounded-xl transition-colors" title="Reset Filter">
                        <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-2xl border border-zinc-200/80 shadow-2xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="border-b border-zinc-100 text-[11px] font-medium text-zinc-400">
                        <th class="py-3 px-4 w-10">
                            <input type="checkbox" class="w-3.5 h-3.5 rounded border-zinc-300 text-brand-orange focus:ring-0">
                        </th>
                        <th class="py-3 px-4">Pengguna</th>
                        <th class="py-3 px-4">Kontak & Alamat</th>
                        <th class="py-3 px-4">Peran</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4">Total Pinjaman</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100/70 text-zinc-700">
                    @forelse($users as $u)
                        <tr class="hover:bg-zinc-50/60 transition-colors">
                            <td class="py-3.5 px-4">
                                <input type="checkbox" class="w-3.5 h-3.5 rounded border-zinc-300 text-brand-orange focus:ring-0">
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="flex items-center space-x-2.5">
                                    <div class="w-7 h-7 rounded-full {{ $u->isAdmin() ? 'bg-zinc-950 text-white' : 'bg-zinc-100 text-zinc-700' }} border border-zinc-200 font-bold flex items-center justify-center text-[10px] flex-shrink-0">
                                        {{ strtoupper(substr($u->name, 0, 1)) }}
                                    </div>
                                    <div class="min-w-0 max-w-xs">
                                        <a href="{{ route('admin.users.show', $u->id) }}" class="font-semibold text-zinc-900 hover:text-brand-orange transition-colors block truncate">
                                            {{ $u->name }}
                                        </a>
                                        <span class="text-[10px] text-zinc-400 block truncate">{{ $u->email }}</span>
                                    </div>
                                </div>
                            </td>

                            <td class="py-3.5 px-4">
                                <span class="font-medium text-zinc-800 block text-[11px]">{{ $u->phone ?: '-' }}</span>
                                <span class="text-[10px] text-zinc-400 block truncate max-w-xs">{{ $u->address ?: '-' }}</span>
                            </td>

                            <td class="py-3.5 px-4">
                                @if($u->isAdmin())
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-semibold bg-zinc-950 text-white">
                                        Admin
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-semibold bg-zinc-100 text-zinc-700">
                                        Anggota
                                    </span>
                                @endif
                            </td>

                            <td class="py-3.5 px-4">
                                @if($u->is_active)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-semibold bg-[#F0FDF4] text-[#16A34A]">
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-semibold bg-[#FEECEC] text-[#DC2626]">
                                        Nonaktif
                                    </span>
                                @endif
                            </td>

                            <td class="py-3.5 px-4">
                                <span class="font-semibold text-zinc-800">{{ $u->borrowings_count }} transaksi</span>
                            </td>

                            <td class="py-3.5 px-4 text-right">
                                <div class="inline-flex items-center space-x-1">
                                    <a href="{{ route('admin.users.show', $u->id) }}" class="p-1 text-zinc-400 hover:text-zinc-800 hover:bg-zinc-100 rounded-lg transition-colors cursor-pointer" title="Lihat Profil">
                                        <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                                    </a>
                                    <a href="{{ route('admin.users.edit', $u->id) }}" class="p-1 text-zinc-400 hover:text-zinc-800 hover:bg-zinc-100 rounded-lg transition-colors cursor-pointer" title="Edit Profil">
                                        <i data-lucide="edit-3" class="w-3.5 h-3.5"></i>
                                    </a>

                                    @if($u->id !== auth()->id())
                                        <form method="POST" action="{{ route('admin.users.toggleStatus', $u->id) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button
                                                type="submit"
                                                class="p-1 text-zinc-400 hover:text-zinc-800 hover:bg-zinc-100 rounded-lg transition-colors cursor-pointer"
                                                title="{{ $u->is_active ? 'Nonaktifkan Akun' : 'Aktifkan Akun' }}"
                                            >
                                                <i data-lucide="{{ $u->is_active ? 'user-x' : 'user-check' }}" class="w-3.5 h-3.5"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-10 text-center text-zinc-400">Tidak ada data pengguna yang sesuai.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="p-3.5 border-t border-zinc-100 bg-white">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</x-layouts.app>
