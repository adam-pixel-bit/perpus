<x-layouts.app title="Riwayat Peminjaman">
    <x-slot:pageTitle>Riwayat Peminjaman Saya</x-slot:pageTitle>

    <!-- Subheader Toolbar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-2 border-b border-zinc-200/70">
        <div class="flex items-center flex-wrap gap-2 text-xs text-zinc-600 font-medium">
            <div class="flex items-center space-x-1.5 px-3 py-1.5 bg-white border border-zinc-200/80 rounded-xl shadow-2xs">
                <i data-lucide="history" class="w-3.5 h-3.5 text-zinc-500"></i>
                <span class="font-semibold text-zinc-800">Riwayat Sirkulasi</span>
                <i data-lucide="chevron-down" class="w-3 h-3 text-zinc-400"></i>
            </div>

            <!-- Status Tabs -->
            <a href="{{ route('user.borrowings.index') }}" class="px-3 py-1.5 rounded-xl text-xs font-semibold transition-all {{ empty($status) || $status === 'all' ? 'bg-zinc-950 text-white shadow-2xs' : 'bg-white text-zinc-600 border border-zinc-200/80 hover:bg-zinc-50' }}">
                Semua Riwayat
            </a>
            <a href="{{ route('user.borrowings.index', ['status' => 'pending']) }}" class="px-3 py-1.5 rounded-xl text-xs font-semibold transition-all {{ $status === 'pending' ? 'bg-brand-orange text-white shadow-2xs' : 'bg-white text-zinc-600 border border-zinc-200/80 hover:bg-zinc-50' }}">
                Menunggu Verifikasi
            </a>
            <a href="{{ route('user.borrowings.index', ['status' => 'borrowed']) }}" class="px-3 py-1.5 rounded-xl text-xs font-semibold transition-all {{ $status === 'borrowed' ? 'bg-zinc-950 text-white shadow-2xs' : 'bg-white text-zinc-600 border border-zinc-200/80 hover:bg-zinc-50' }}">
                Sedang Dipinjam
            </a>
            <a href="{{ route('user.borrowings.index', ['status' => 'returned']) }}" class="px-3 py-1.5 rounded-xl text-xs font-semibold transition-all {{ $status === 'returned' ? 'bg-zinc-950 text-white shadow-2xs' : 'bg-white text-zinc-600 border border-zinc-200/80 hover:bg-zinc-50' }}">
                Selesai Dikembalikan
            </a>
            <a href="{{ route('user.borrowings.index', ['status' => 'overdue']) }}" class="px-3 py-1.5 rounded-xl text-xs font-semibold transition-all {{ $status === 'overdue' ? 'bg-red-600 text-white shadow-2xs' : 'bg-white text-zinc-600 border border-zinc-200/80 hover:bg-zinc-50' }}">
                Terlambat
            </a>
        </div>

        <div class="flex items-center space-x-2">
            <a href="{{ route('user.catalog.index') }}" class="flex items-center space-x-1.5 px-3.5 py-1.5 text-xs font-semibold text-white bg-zinc-950 hover:bg-zinc-800 rounded-xl shadow-2xs transition-all">
                <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                <span>Pinjam Buku Lain</span>
            </a>
        </div>
    </div>

    <!-- Borrowing Records Table -->
    <div class="bg-white rounded-2xl border border-zinc-200/80 shadow-2xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="border-b border-zinc-100 text-[11px] font-medium text-zinc-400">
                        <th class="py-3 px-4 w-10">
                            <input type="checkbox" class="w-3.5 h-3.5 rounded border-zinc-300 text-brand-orange focus:ring-0">
                        </th>
                        <th class="py-3 px-4">Judul Buku & Kode</th>
                        <th class="py-3 px-4">Kategori</th>
                        <th class="py-3 px-4">Tgl Pinjam</th>
                        <th class="py-3 px-4">Jatuh Tempo</th>
                        <th class="py-3 px-4">Tgl Kembali</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4">Denda</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100/70 text-zinc-700">
                    @forelse($borrowings as $b)
                        <tr class="hover:bg-zinc-50/60 transition-colors {{ $b->isOverdue() && in_array($b->status, ['borrowed', 'approved']) ? 'bg-[#FFF7F2]/40' : '' }}">
                            <td class="py-3.5 px-4">
                                <input type="checkbox" class="w-3.5 h-3.5 rounded border-zinc-300 text-brand-orange focus:ring-0">
                            </td>

                            <!-- Book & Code -->
                            <td class="py-3.5 px-4">
                                <div class="flex items-center space-x-2.5">
                                    <div class="w-9 h-12 rounded-lg bg-zinc-100 overflow-hidden flex-shrink-0 border border-zinc-200/80">
                                        <img src="{{ $b->book->cover_url }}" alt="{{ $b->book->title }}" class="w-full h-full object-cover" onerror="this.onerror=null; this.src=FALLBACK_BOOK_COVER;">
                                    </div>
                                    <div class="min-w-0 max-w-xs">
                                        <a href="{{ route('user.catalog.show', $b->book->slug) }}" class="font-semibold text-zinc-900 hover:text-brand-orange transition-colors block truncate">
                                            {{ $b->book->title ?? 'Buku' }}
                                        </a>
                                        <span class="font-mono text-[10px] text-zinc-400 block">{{ $b->borrowing_code }}</span>
                                        <span class="text-[10px] text-zinc-400 block truncate">{{ $b->book->author ?? '-' }}</span>
                                    </div>
                                </div>
                            </td>

                            <!-- Category -->
                            <td class="py-3.5 px-4">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-semibold bg-zinc-100 text-zinc-700">
                                    {{ $b->book->category->name ?? 'Umum' }}
                                </span>
                            </td>

                            <!-- Borrow Date -->
                            <td class="py-3.5 px-4 text-zinc-700 font-medium">
                                {{ $b->borrow_date->format('d M Y') }}
                            </td>

                            <!-- Return Due Date -->
                            <td class="py-3.5 px-4">
                                <span class="font-semibold block {{ $b->isOverdue() && in_array($b->status, ['borrowed', 'approved']) ? 'text-red-600' : 'text-zinc-700' }}">
                                    {{ $b->return_due_date->format('d M Y') }}
                                </span>
                                @if($b->isOverdue() && in_array($b->status, ['borrowed', 'approved']))
                                    <span class="text-[10px] text-red-500 font-semibold block mt-0.5">
                                        Terlambat {{ $b->daysOverdue() }} hari
                                    </span>
                                @endif
                            </td>

                            <!-- Actual Return Date -->
                            <td class="py-3.5 px-4 text-zinc-500">
                                {{ $b->actual_return_date ? $b->actual_return_date->format('d M Y') : '—' }}
                            </td>

                            <!-- Status -->
                            <td class="py-3.5 px-4">
                                <x-badge-status :status="$b->isOverdue() ? 'overdue' : $b->status" />
                                @if($b->status === 'rejected' && $b->admin_note)
                                    <span class="text-[10px] text-red-500 block mt-1" title="{{ $b->admin_note }}">
                                        Alasan: {{ Str::limit($b->admin_note, 22) }}
                                    </span>
                                @endif
                            </td>

                            <!-- Fines -->
                            <td class="py-3.5 px-4 font-semibold {{ $b->fines > 0 ? 'text-red-600' : 'text-zinc-400' }}">
                                {{ $b->fines > 0 ? 'Rp ' . number_format($b->fines, 0, ',', '.') : '—' }}
                            </td>

                            <!-- Actions -->
                            <td class="py-3.5 px-4 text-right">
                                <div class="flex items-center justify-end gap-1.5 flex-wrap">
                                    <!-- QR Pass Button -->
                                    <button
                                        type="button"
                                        onclick="showQrPass('{{ $b->borrowing_code }}', '{{ addslashes($b->book->title) }}', '{{ $b->status }}', '{{ $b->return_due_date->format('d M Y') }}')"
                                        class="px-2 py-1 bg-zinc-100 hover:bg-orange-50 hover:text-orange-600 border border-zinc-200 text-zinc-700 rounded-lg font-semibold text-[11px] transition-colors flex items-center gap-1 cursor-pointer"
                                        title="Lihat Tiket QR"
                                    >
                                        <i data-lucide="qr-code" class="w-3 h-3"></i>
                                        <span>QR</span>
                                    </button>

                                    @if($b->status === 'pending')
                                        <form method="POST" action="{{ route('user.borrowings.cancel', $b->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan permohonan peminjaman buku ini?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-2.5 py-1 bg-white hover:bg-red-50 text-red-600 border border-zinc-200/80 hover:border-red-200 rounded-lg font-semibold text-[11px] transition-colors cursor-pointer">
                                                Batalkan
                                            </button>
                                        </form>
                                    @elseif($b->status === 'borrowed' && ! $b->isOverdue() && ($b->renewed_count ?? 0) < 1)
                                        <form method="POST" action="{{ route('user.borrowings.renew', $b->id) }}" onsubmit="return confirm('Perpanjang masa peminjaman selama 7 hari ke depan?');" class="inline">
                                            @csrf
                                            <button type="submit" class="px-2.5 py-1 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200 rounded-lg font-semibold text-[11px] transition-colors cursor-pointer flex items-center gap-1">
                                                <i data-lucide="refresh-cw" class="w-3 h-3"></i>
                                                <span>+7 Hari</span>
                                            </button>
                                        </form>
                                    @elseif($b->status === 'returned')
                                        <a href="{{ route('user.catalog.show', $b->book->slug) }}#review-section" class="px-2 py-1 bg-amber-50 hover:bg-amber-100 text-amber-700 border border-amber-200 rounded-lg font-semibold text-[11px] transition-colors flex items-center gap-1">
                                            <i data-lucide="star" class="w-3 h-3"></i>
                                            <span>Ulas</span>
                                        </a>
                                    @endif

                                    <a href="{{ route('user.catalog.show', $b->book->slug) }}" class="px-2 py-1 bg-zinc-50 hover:bg-zinc-100 border border-zinc-200/80 text-zinc-700 rounded-lg font-semibold text-[11px] transition-colors">
                                        Detail
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="py-12 text-center text-zinc-400">
                                <i data-lucide="history" class="w-8 h-8 mx-auto mb-2 text-zinc-300"></i>
                                <p class="font-semibold text-zinc-700">Tidak ada riwayat peminjaman</p>
                                <p class="text-xs text-zinc-400 mt-1">Anda belum memiliki catatan sirkulasi pada status ini.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($borrowings->hasPages())
            <div class="p-3.5 border-t border-zinc-100 bg-white">
                {{ $borrowings->links() }}
            </div>
        @endif
    </div>

    <!-- QR Pass Modal -->
    <div id="qrModal" class="fixed inset-0 z-50 hidden bg-zinc-950/60 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-sm w-full p-6 text-center shadow-2xl border border-zinc-100 transform transition-all">
            <div class="w-12 h-12 bg-orange-50 text-orange-600 rounded-2xl flex items-center justify-center mx-auto mb-3">
                <i data-lucide="qr-code" class="w-6 h-6"></i>
            </div>
            <h3 class="text-base font-bold text-zinc-900">Tiket Sirkulasi Buku</h3>
            <p id="qrBookTitle" class="text-xs text-zinc-500 font-medium mt-1 truncate px-4"></p>

            <!-- QR Container -->
            <div class="my-5 p-4 bg-zinc-50 rounded-2xl border border-zinc-200/80 inline-block shadow-inner">
                <img id="qrImage" src="" alt="QR Code Peminjaman" class="w-44 h-44 mx-auto rounded-lg">
                <p id="qrCodeText" class="mt-2.5 font-mono font-bold text-xs text-zinc-800 tracking-wider"></p>
            </div>

            <div class="text-[11px] text-zinc-400 bg-zinc-50 p-2.5 rounded-xl border border-zinc-100 mb-4">
                Tunjukkan QR Code ini kepada petugas perpustakaan di meja layanan untuk serah terima buku.
            </div>

            <button type="button" onclick="closeQrPass()" class="w-full py-2.5 bg-zinc-950 hover:bg-black text-white text-xs font-bold rounded-xl transition cursor-pointer">
                Tutup Tiket
            </button>
        </div>
    </div>

    <script>
        function showQrPass(code, title, status, dueDate) {
            document.getElementById('qrCodeText').textContent = code;
            document.getElementById('qrBookTitle').textContent = title;
            // Use reliable qr server API for crisp vector QR code
            document.getElementById('qrImage').src = 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' + encodeURIComponent(code);
            document.getElementById('qrModal').classList.remove('hidden');
        }

        function closeQrPass() {
            document.getElementById('qrModal').classList.add('hidden');
        }
    </script>
</x-layouts.app>
