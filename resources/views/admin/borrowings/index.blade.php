<x-layouts.app title="Manajemen Peminjaman">
    <x-slot:header>Sirkulasi Peminjaman & Pengembalian Buku</x-slot:header>
    <x-slot:subheader>Verifikasi permintaan, pemrosesan peminjaman fisik, dan penerimaan pengembalian buku</x-slot:subheader>

    <!-- Status Tabs -->
    <div class="flex flex-wrap gap-2">
        <a href="{{ route('admin.borrowings.index') }}" class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ empty($status) || $status === 'all' ? 'bg-slate-900 text-white shadow-md' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }}">
            Semua Transaksi
        </a>
        <a href="{{ route('admin.borrowings.index', ['status' => 'pending']) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center space-x-1.5 {{ $status === 'pending' ? 'bg-amber-500 text-white shadow-md' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }}">
            <span>Menunggu Persetujuan</span>
            @if($pendingCount > 0)
                <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ $status === 'pending' ? 'bg-white text-amber-700' : 'bg-amber-100 text-amber-800' }}">
                    {{ $pendingCount }}
                </span>
            @endif
        </a>
        <a href="{{ route('admin.borrowings.index', ['status' => 'borrowed']) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center space-x-1.5 {{ $status === 'borrowed' ? 'bg-blue-600 text-white shadow-md' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }}">
            <span>Sedang Dipinjam</span>
            <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ $status === 'borrowed' ? 'bg-white text-blue-700' : 'bg-blue-100 text-blue-800' }}">
                {{ $borrowedCount }}
            </span>
        </a>
        <a href="{{ route('admin.borrowings.index', ['status' => 'overdue']) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center space-x-1.5 {{ $status === 'overdue' ? 'bg-rose-600 text-white shadow-md' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }}">
            <span>Terlambat Kembali</span>
            @if($overdueCount > 0)
                <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ $status === 'overdue' ? 'bg-white text-rose-700' : 'bg-rose-100 text-rose-800' }}">
                    {{ $overdueCount }}
                </span>
            @endif
        </a>
        <a href="{{ route('admin.borrowings.index', ['status' => 'returned']) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ $status === 'returned' ? 'bg-emerald-600 text-white shadow-md' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }}">
            <span>Selesai Dikembalikan ({{ $returnedCount }})</span>
        </a>
    </div>

    <!-- Search & Date Filter Bar -->
    <div class="bg-white rounded-3xl border border-slate-200/80 p-5 shadow-xs">
        <form method="GET" action="{{ route('admin.borrowings.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
            @if($status && $status !== 'all')
                <input type="hidden" name="status" value="{{ $status }}">
            @endif

            <!-- Keyword -->
            <div class="relative lg:col-span-2">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <i data-lucide="search" class="w-4 h-4"></i>
                </div>
                <input
                    type="text"
                    name="search"
                    value="{{ $search }}"
                    placeholder="Kode pinjam, nama anggota, judul buku..."
                    class="w-full pl-9 pr-4 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-slate-900"
                >
            </div>

            <!-- Start Date -->
            <div>
                <input
                    type="date"
                    name="start_date"
                    value="{{ $startDate }}"
                    placeholder="Dari tanggal"
                    class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-slate-700"
                >
            </div>

            <!-- End Date -->
            <div>
                <input
                    type="date"
                    name="end_date"
                    value="{{ $endDate }}"
                    placeholder="Sampai tanggal"
                    class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-slate-700"
                >
            </div>

            <!-- Buttons -->
            <div class="flex items-center space-x-2">
                <button type="submit" class="flex-1 py-2 bg-slate-800 hover:bg-slate-900 text-white text-xs font-bold rounded-xl transition-colors">
                    Filter
                </button>
                @if($search || $startDate || $endDate)
                    <a href="{{ route('admin.borrowings.index', ['status' => $status]) }}" class="p-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl transition-colors" title="Reset Filter">
                        <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Borrowings Table -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50/75 border-b border-slate-200 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                        <th class="py-3 px-4">Kode Transaksi</th>
                        <th class="py-3 px-4">Peminjam</th>
                        <th class="py-3 px-4">Buku & ISBN</th>
                        <th class="py-3 px-4">Tgl Pinjam & Jatuh Tempo</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4">Denda / Catatan</th>
                        <th class="py-3 px-4 text-right">Aksi Sirkulasi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($borrowings as $b)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <!-- Code -->
                            <td class="py-3.5 px-4 font-mono font-bold text-slate-800">
                                {{ $b->borrowing_code }}
                                <span class="text-[10px] text-slate-400 block font-sans">{{ $b->created_at->format('d/m/Y H:i') }}</span>
                            </td>

                            <!-- User -->
                            <td class="py-3.5 px-4">
                                <span class="font-bold text-slate-900 block truncate max-w-xs">{{ $b->user->name ?? 'User Dihapus' }}</span>
                                <span class="text-[11px] text-slate-400 block truncate max-w-xs">{{ $b->user->email ?? '-' }}</span>
                            </td>

                            <!-- Book -->
                            <td class="py-3.5 px-4">
                                <span class="font-semibold text-slate-800 block truncate max-w-xs">{{ $b->book->title ?? 'Buku Dihapus' }}</span>
                                <span class="text-[11px] font-mono text-slate-400 block">ISBN: {{ $b->book->isbn ?? '-' }}</span>
                            </td>

                            <!-- Dates -->
                            <td class="py-3.5 px-4">
                                <div class="space-y-0.5">
                                    <span class="text-slate-600 block">Pinjam: <strong>{{ $b->borrow_date->format('d M Y') }}</strong></span>
                                    <span class="block {{ $b->isOverdue() ? 'text-rose-600 font-bold' : 'text-slate-500' }}">
                                        Jatuh Tempo: <strong>{{ $b->return_due_date->format('d M Y') }}</strong>
                                    </span>
                                    @if($b->actual_return_date)
                                        <span class="text-[10px] text-emerald-600 font-semibold block">
                                            Kembali: {{ $b->actual_return_date->format('d M Y') }}
                                        </span>
                                    @endif
                                </div>
                            </td>

                            <!-- Status Badge -->
                            <td class="py-3.5 px-4">
                                <x-badge-status :status="$b->isOverdue() ? 'overdue' : $b->status" />
                                @if($b->isOverdue() && $b->status === 'borrowed')
                                    <span class="text-[10px] text-rose-600 font-bold block mt-1">
                                        Terlambat {{ $b->daysOverdue() }} hari
                                    </span>
                                @endif
                            </td>

                            <!-- Fines / Note -->
                            <td class="py-3.5 px-4 max-w-xs">
                                @if($b->fines > 0)
                                    <span class="font-bold text-rose-600 block">Rp {{ number_format($b->fines, 0, ',', '.') }}</span>
                                @endif
                                <span class="text-[11px] text-slate-500 block truncate" title="{{ $b->notes }}">
                                    Catatan: {{ $b->notes ?: '-' }}
                                </span>
                            </td>

                            <!-- Actions -->
                            <td class="py-3.5 px-4 text-right">
                                <div class="inline-flex items-center justify-end space-x-1.5">
                                    @if($b->status === 'pending')
                                        <!-- Approve -->
                                        <form method="POST" action="{{ route('admin.borrowings.approve', $b->id) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold text-xs shadow-xs transition-colors flex items-center space-x-1">
                                                <i data-lucide="check" class="w-3.5 h-3.5"></i>
                                                <span>Setujui</span>
                                            </button>
                                        </form>

                                        <!-- Reject Trigger -->
                                        <button
                                            type="button"
                                            onclick="openRejectModal({{ $b->id }}, '{{ $b->borrowing_code }}')"
                                            class="px-2.5 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 rounded-xl font-bold text-xs transition-colors"
                                        >
                                            Tolak
                                        </button>
                                    @elseif(in_array($b->status, ['borrowed', 'approved']))
                                        <!-- Process Return Trigger -->
                                        <button
                                            type="button"
                                            onclick="openReturnModal({{ $b->id }}, '{{ $b->borrowing_code }}', '{{ addslashes($b->book->title ?? '') }}', {{ $b->isOverdue() ? $b->daysOverdue() * 2000 : 0 }})"
                                            class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold text-xs shadow-xs transition-colors flex items-center space-x-1"
                                        >
                                            <i data-lucide="corner-down-left" class="w-3.5 h-3.5"></i>
                                            <span>Kembalikan Buku</span>
                                        </button>
                                    @else
                                        <span class="text-[11px] text-slate-400 italic">Selesai</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-slate-400">
                                <i data-lucide="inbox" class="w-8 h-8 mx-auto mb-2 text-slate-300"></i>
                                <p class="font-bold text-slate-700">Tidak ada transaksi yang cocok</p>
                                <p class="text-xs text-slate-400 mt-1">Gunakan tab status lain atau ubah kriteria filter.</p>
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

    <!-- Reject Modal -->
    <div id="reject-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-xs hidden">
        <div class="bg-white rounded-3xl border border-slate-200 shadow-xl max-w-md w-full p-6 space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div class="flex items-center space-x-2">
                    <div class="w-8 h-8 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center">
                        <i data-lucide="x-circle" class="w-4 h-4"></i>
                    </div>
                    <h3 class="font-extrabold text-base text-slate-900">Tolak Permohonan Pinjam</h3>
                </div>
                <button type="button" onclick="closeRejectModal()" class="text-slate-400 hover:text-slate-600 p-1">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <p class="text-xs text-slate-600">
                Anda akan menolak permohonan peminjaman dengan kode <strong id="reject-code" class="text-slate-900"></strong>. Anggota akan menerima notifikasi penolakan.
            </p>

            <form id="reject-form" method="POST" action="">
                @csrf
                @method('PATCH')
                <div>
                    <label for="admin_note" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                        Alasan Penolakan <span class="text-rose-500">*</span>
                    </label>
                    <textarea
                        name="admin_note"
                        rows="3"
                        required
                        placeholder="Contoh: Pengguna masih memiliki tanggungan peminjaman lain atau data belum lengkap..."
                        class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-600 text-slate-900 resize-none"
                    ></textarea>
                </div>

                <div class="pt-4 flex items-center justify-end space-x-2">
                    <button type="button" onclick="closeRejectModal()" class="px-4 py-2 text-xs font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold rounded-xl shadow-md transition-colors">
                        Konfirmasi Tolak
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Return Modal -->
    <div id="return-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-xs hidden">
        <div class="bg-white rounded-3xl border border-slate-200 shadow-xl max-w-md w-full p-6 space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div class="flex items-center space-x-2">
                    <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                        <i data-lucide="corner-down-left" class="w-4 h-4"></i>
                    </div>
                    <h3 class="font-extrabold text-base text-slate-900">Proses Pengembalian Buku</h3>
                </div>
                <button type="button" onclick="closeReturnModal()" class="text-slate-400 hover:text-slate-600 p-1">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <div>
                <p class="text-xs text-slate-500">Buku yang dikembalikan:</p>
                <p id="return-book-title" class="text-sm font-bold text-slate-900 mt-0.5"></p>
                <p class="text-[11px] text-slate-400 font-mono mt-0.5">Kode: <span id="return-code"></span></p>
            </div>

            <form id="return-form" method="POST" action="" class="space-y-3">
                @csrf
                @method('PATCH')

                <div>
                    <label for="fines" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                        Denda Keterlambatan / Kerusakan (Rp)
                    </label>
                    <input
                        type="number"
                        id="return-fines"
                        name="fines"
                        value="0"
                        min="0"
                        step="500"
                        class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-slate-900 font-bold"
                    >
                    <p class="text-[10px] text-slate-400 mt-0.5">Otomatis dihitung Rp 2.000/hari jika melewati batas waktu.</p>
                </div>

                <div>
                    <label for="return_note" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                        Catatan Kondisi Buku
                    </label>
                    <textarea
                        id="return_note"
                        name="admin_note"
                        rows="2"
                        placeholder="Contoh: Buku diterima dalam kondisi baik, halaman lengkap..."
                        class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-slate-900 resize-none"
                    >Buku diterima dalam kondisi baik dan lengkap.</textarea>
                </div>

                <div class="pt-3 flex items-center justify-end space-x-2">
                    <button type="button" onclick="closeReturnModal()" class="px-4 py-2 text-xs font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-md transition-colors">
                        Selesaikan Pengembalian
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- JavaScript for Modals -->
    <script>
        function openRejectModal(id, code) {
            document.getElementById('reject-code').innerText = code;
            document.getElementById('reject-form').action = '/admin/borrowings/' + id + '/reject';
            document.getElementById('reject-modal').classList.remove('hidden');
        }

        function closeRejectModal() {
            document.getElementById('reject-modal').classList.add('hidden');
        }

        function openReturnModal(id, code, title, suggestedFines) {
            document.getElementById('return-code').innerText = code;
            document.getElementById('return-book-title').innerText = title;
            document.getElementById('return-fines').value = suggestedFines;
            document.getElementById('return-form').action = '/admin/borrowings/' + id + '/return';
            document.getElementById('return-modal').classList.remove('hidden');
        }

        function closeReturnModal() {
            document.getElementById('return-modal').classList.add('hidden');
        }
    </script>
</x-layouts.app>
