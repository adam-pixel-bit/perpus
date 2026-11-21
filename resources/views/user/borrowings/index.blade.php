<x-layouts.app title="Riwayat Peminjaman">
    <x-slot:header>Riwayat & Status Peminjaman Saya</x-slot:header>
    <x-slot:subheader>Daftar lengkap permohonan sirkulasi, buku yang sedang Anda baca, dan arsip pengembalian</x-slot:subheader>

    <!-- Status Tabs -->
    <div class="flex flex-wrap gap-2">
        <a href="{{ route('user.borrowings.index') }}" class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ empty($status) || $status === 'all' ? 'bg-slate-900 text-white shadow-md' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }}">
            Semua Riwayat
        </a>
        <a href="{{ route('user.borrowings.index', ['status' => 'pending']) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ $status === 'pending' ? 'bg-amber-500 text-white shadow-md' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }}">
            Menunggu Verifikasi
        </a>
        <a href="{{ route('user.borrowings.index', ['status' => 'borrowed']) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ $status === 'borrowed' ? 'bg-blue-600 text-white shadow-md' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }}">
            Sedang Dipinjam
        </a>
        <a href="{{ route('user.borrowings.index', ['status' => 'returned']) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ $status === 'returned' ? 'bg-emerald-600 text-white shadow-md' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }}">
            Selesai Dikembalikan
        </a>
        <a href="{{ route('user.borrowings.index', ['status' => 'overdue']) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ $status === 'overdue' ? 'bg-rose-600 text-white shadow-md' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }}">
            Terlambat
        </a>
    </div>

    <!-- Borrowing Records Table -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50/75 border-b border-slate-200 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                        <th class="py-3 px-4">Buku & Kode Transaksi</th>
                        <th class="py-3 px-4">Kategori</th>
                        <th class="py-3 px-4">Tgl Pinjam</th>
                        <th class="py-3 px-4">Jatuh Tempo</th>
                        <th class="py-3 px-4">Tgl Pengembalian</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4">Denda</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($borrowings as $b)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <!-- Book & Code -->
                            <td class="py-3.5 px-4">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-14 rounded-lg bg-slate-100 overflow-hidden flex-shrink-0 border border-slate-200 shadow-2xs">
                                        <img src="{{ $b->book->cover_url }}" alt="" class="w-full h-full object-cover">
                                    </div>
                                    <div class="min-w-0 max-w-xs">
                                        <a href="{{ route('user.catalog.show', $b->book->slug) }}" class="font-bold text-slate-900 hover:text-blue-600 transition-colors block truncate">
                                            {{ $b->book->title ?? 'Buku' }}
                                        </a>
                                        <span class="font-mono text-[10px] text-slate-400 block">Kode: {{ $b->borrowing_code }}</span>
                                        <span class="text-[10px] text-slate-500 block truncate">Penulis: {{ $b->book->author ?? '-' }}</span>
                                    </div>
                                </div>
                            </td>

                            <!-- Category -->
                            <td class="py-3.5 px-4">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-slate-100 text-slate-700">
                                    {{ $b->book->category->name ?? 'Umum' }}
                                </span>
                            </td>

                            <!-- Borrow Date -->
                            <td class="py-3.5 px-4 text-slate-700 font-medium">
                                {{ $b->borrow_date->format('d M Y') }}
                            </td>

                            <!-- Return Due Date -->
                            <td class="py-3.5 px-4">
                                <span class="font-semibold block {{ $b->isOverdue() && in_array($b->status, ['borrowed', 'approved']) ? 'text-rose-600' : 'text-slate-700' }}">
                                    {{ $b->return_due_date->format('d M Y') }}
                                </span>
                                @if($b->isOverdue() && in_array($b->status, ['borrowed', 'approved']))
                                    <span class="text-[10px] text-rose-500 font-bold block mt-0.5">
                                        Terlambat {{ $b->daysOverdue() }} hari
                                    </span>
                                @endif
                            </td>

                            <!-- Actual Return Date -->
                            <td class="py-3.5 px-4 text-slate-600 font-medium">
                                {{ $b->actual_return_date ? $b->actual_return_date->format('d M Y') : '-' }}
                            </td>

                            <!-- Status -->
                            <td class="py-3.5 px-4">
                                <x-badge-status :status="$b->isOverdue() ? 'overdue' : $b->status" />
                                @if($b->status === 'rejected' && $b->admin_note)
                                    <span class="text-[10px] text-rose-500 block mt-1" title="{{ $b->admin_note }}">
                                        Alasan: {{ Str::limit($b->admin_note, 25) }}
                                    </span>
                                @endif
                            </td>

                            <!-- Fines -->
                            <td class="py-3.5 px-4 font-bold {{ $b->fines > 0 ? 'text-rose-600' : 'text-slate-400' }}">
                                {{ $b->fines > 0 ? 'Rp ' . number_format($b->fines, 0, ',', '.') : '-' }}
                            </td>

                            <!-- Actions -->
                            <td class="py-3.5 px-4 text-right">
                                @if($b->status === 'pending')
                                    <form method="POST" action="{{ route('user.borrowings.cancel', $b->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan permohonan peminjaman buku ini?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 rounded-xl font-bold text-xs transition-colors">
                                            Batalkan
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ route('user.catalog.show', $b->book->slug) }}" class="p-1.5 text-blue-600 hover:underline font-bold text-xs">
                                        Lihat Buku
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-12 text-center text-slate-400">
                                <i data-lucide="history" class="w-8 h-8 mx-auto mb-2 text-slate-300"></i>
                                <p class="font-bold text-slate-700">Tidak ada riwayat peminjaman</p>
                                <p class="text-xs text-slate-400 mt-1">Anda belum memiliki catatan sirkulasi pada kategori status ini.</p>
                            </td>
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
</x-layouts.app>
