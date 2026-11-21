<x-layouts.app title="Detail Pengguna">
    <x-slot:header>Profil & Riwayat Pengguna</x-slot:header>
    <x-slot:subheader>Rincian data keanggotaan dan seluruh rekam jejak transaksi sirkulasi</x-slot:subheader>

    <div class="space-y-6">
        <!-- Back Link & Action Bar -->
        <div class="flex items-center justify-between">
            <a href="{{ route('admin.users.index') }}" class="inline-flex items-center space-x-1.5 px-3.5 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:text-slate-900 bg-white border border-slate-200 transition-colors">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                <span>Kembali ke Daftar Pengguna</span>
            </a>
            <div class="flex items-center space-x-2">
                <a href="{{ route('admin.users.edit', $user->id) }}" class="inline-flex items-center space-x-1.5 px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-xl text-xs font-bold transition-all shadow-sm">
                    <i data-lucide="edit-3" class="w-4 h-4"></i>
                    <span>Edit Data</span>
                </a>
            </div>
        </div>

        <!-- User Profile Card -->
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs p-6 sm:p-8">
            <div class="flex flex-col sm:flex-row items-start sm:items-center space-y-4 sm:space-y-0 sm:space-x-6 pb-6 border-b border-slate-100">
                <div class="w-20 h-20 rounded-2xl bg-gradient-to-tr {{ $user->isAdmin() ? 'from-indigo-600 to-purple-600' : 'from-blue-600 to-teal-500' }} text-white font-extrabold flex items-center justify-center text-2xl shadow-md">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div class="space-y-1">
                    <div class="flex items-center space-x-2">
                        <h1 class="text-2xl font-extrabold text-slate-900">{{ $user->name }}</h1>
                        @if($user->isAdmin())
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-purple-50 text-purple-700 border border-purple-200 uppercase">
                                Administrator
                            </span>
                        @else
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200 uppercase">
                                Anggota
                            </span>
                        @endif

                        @if($user->is_active)
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                Aktif
                            </span>
                        @else
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                Nonaktif
                            </span>
                        @endif
                    </div>
                    <p class="text-xs text-slate-500 font-medium">{{ $user->email }} • Bergabung sejak {{ $user->created_at->format('d F Y') }}</p>
                </div>
            </div>

            <!-- Profile Details Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-6 text-xs">
                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 space-y-1">
                    <span class="text-slate-400 block font-semibold uppercase text-[10px]">Nomor Telepon</span>
                    <span class="font-bold text-slate-800 text-sm">{{ $user->phone ?: 'Tidak dicantumkan' }}</span>
                </div>
                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 space-y-1 sm:col-span-2">
                    <span class="text-slate-400 block font-semibold uppercase text-[10px]">Alamat Domisili</span>
                    <span class="font-medium text-slate-800 text-sm">{{ $user->address ?: 'Tidak dicantumkan' }}</span>
                </div>
            </div>
        </div>

        <!-- Borrowing History of this User -->
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="font-extrabold text-base text-slate-900">Riwayat Peminjaman Buku</h3>
                    <p class="text-xs text-slate-500">Seluruh catatan transaksi sirkulasi yang dilakukan oleh {{ $user->name }}</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-slate-50/75 border-b border-slate-200 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                            <th class="py-3 px-4">Kode Peminjaman</th>
                            <th class="py-3 px-4">Judul Buku</th>
                            <th class="py-3 px-4">Tanggal Pinjam</th>
                            <th class="py-3 px-4">Batas Kembali</th>
                            <th class="py-3 px-4">Tanggal Pengembalian</th>
                            <th class="py-3 px-4">Status</th>
                            <th class="py-3 px-4">Denda</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($borrowings as $b)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="py-3 px-4 font-mono font-bold text-slate-800">{{ $b->borrowing_code }}</td>
                                <td class="py-3 px-4 font-semibold text-slate-900 max-w-xs truncate">{{ $b->book->title ?? '-' }}</td>
                                <td class="py-3 px-4 text-slate-600">{{ $b->borrow_date->format('d M Y') }}</td>
                                <td class="py-3 px-4 text-slate-600">{{ $b->return_due_date->format('d M Y') }}</td>
                                <td class="py-3 px-4 text-slate-600">{{ $b->actual_return_date ? $b->actual_return_date->format('d M Y') : '-' }}</td>
                                <td class="py-3 px-4">
                                    <x-badge-status :status="$b->isOverdue() ? 'overdue' : $b->status" />
                                </td>
                                <td class="py-3 px-4 font-semibold {{ $b->fines > 0 ? 'text-rose-600 font-bold' : 'text-slate-400' }}">
                                    {{ $b->fines > 0 ? 'Rp ' . number_format($b->fines, 0, ',', '.') : '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-8 text-center text-slate-400">Pengguna belum memiliki riwayat peminjaman.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($borrowings->hasPages())
                <div class="p-4 border-t border-slate-200 bg-slate-50/50">
                    {{ $borrowings->links() }}
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>
