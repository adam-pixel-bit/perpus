<x-layouts.app title="Laporan Sistem">
    <x-slot:header>Laporan & Rekapitulasi Sirkulasi</x-slot:header>
    <x-slot:subheader>Analitik komprehensif transaksi peminjaman, buku terfavorit, dan keterlambatan pengembalian</x-slot:subheader>

    <!-- Filter & Export Toolbar -->
    <div class="bg-white rounded-3xl border border-slate-200/80 p-5 shadow-xs space-y-4 no-print">
        <form method="GET" action="{{ route('admin.reports.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 items-end">
            <!-- Start Date -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Dari Tanggal</label>
                <input
                    type="date"
                    name="start_date"
                    value="{{ $startDate }}"
                    class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-slate-700"
                >
            </div>

            <!-- End Date -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Sampai Tanggal</label>
                <input
                    type="date"
                    name="end_date"
                    value="{{ $endDate }}"
                    class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-slate-700"
                >
            </div>

            <!-- Report Type -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Tipe Laporan</label>
                <select name="type" class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-slate-700">
                    <option value="borrowings" {{ $reportType === 'borrowings' ? 'selected' : '' }}>Transaksi Sirkulasi</option>
                    <option value="popular" {{ $reportType === 'popular' ? 'selected' : '' }}>Buku Terfavorit</option>
                    <option value="users" {{ $reportType === 'users' ? 'selected' : '' }}>Aktivitas Anggota</option>
                </select>
            </div>

            <!-- Filter Button -->
            <div>
                <button type="submit" class="w-full py-2 bg-slate-800 hover:bg-slate-900 text-white text-xs font-bold rounded-xl transition-colors">
                    Terapkan Periode
                </button>
            </div>

            <!-- Export Buttons -->
            <div class="flex items-center space-x-2">
                <a
                    href="{{ route('admin.reports.export', ['start_date' => $startDate, 'end_date' => $endDate]) }}"
                    class="flex-1 py-2 px-3 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl text-center shadow-xs transition-colors flex items-center justify-center space-x-1"
                >
                    <i data-lucide="file-spreadsheet" class="w-3.5 h-3.5"></i>
                    <span>Ekspor CSV</span>
                </a>
                <button
                    type="button"
                    onclick="window.print()"
                    class="p-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition-colors"
                    title="Cetak Halaman"
                >
                    <i data-lucide="printer" class="w-4 h-4"></i>
                </button>
            </div>
        </form>
    </div>

    <!-- Summary KPI for this Report Period -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <x-stat-card
            title="Total Sirkulasi Periode Ini"
            value="{{ number_format($totalTransactions) }}"
            subtitle="Periode {{ date('d/m/y', strtotime($startDate)) }} - {{ date('d/m/y', strtotime($endDate)) }}"
            icon="arrow-left-right"
            variant="blue"
        />
        <x-stat-card
            title="Buku Berhasil Dikembalikan"
            value="{{ number_format($totalReturned) }}"
            subtitle="Sirkulasi selesai"
            icon="check-circle"
            variant="emerald"
        />
        <x-stat-card
            title="Kasus Keterlambatan"
            value="{{ number_format($totalOverdue) }}"
            subtitle="Melebihi batas tempo"
            icon="alert-octagon"
            variant="rose"
        />
        <x-stat-card
            title="Total Denda Diterima"
            value="Rp {{ number_format($totalFines, 0, ',', '.') }}"
            subtitle="Pemasukan kas denda"
            icon="circle-dollar-sign"
            variant="amber"
        />
    </div>

    <!-- Report Table Content based on Type -->
    @if($reportType === 'borrowings')
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="font-extrabold text-base text-slate-900">Laporan Sirkulasi & Transaksi Peminjaman</h3>
                    <p class="text-xs text-slate-500">Rentang waktu: {{ date('d F Y', strtotime($startDate)) }} s/d {{ date('d F Y', strtotime($endDate)) }}</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-slate-50/75 border-b border-slate-200 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                            <th class="py-3 px-4">No. Transaksi</th>
                            <th class="py-3 px-4">Peminjam</th>
                            <th class="py-3 px-4">Buku & ISBN</th>
                            <th class="py-3 px-4">Tgl Pinjam</th>
                            <th class="py-3 px-4">Jatuh Tempo</th>
                            <th class="py-3 px-4">Tgl Kembali</th>
                            <th class="py-3 px-4">Status</th>
                            <th class="py-3 px-4 text-right">Denda</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($borrowings as $b)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="py-3.5 px-4 font-mono font-bold text-slate-800">{{ $b->borrowing_code }}</td>
                                <td class="py-3.5 px-4 font-semibold text-slate-900">{{ $b->user->name ?? '-' }}</td>
                                <td class="py-3.5 px-4">
                                    <span class="font-medium text-slate-800 block truncate max-w-xs">{{ $b->book->title ?? '-' }}</span>
                                    <span class="text-[10px] text-slate-400 font-mono">ISBN: {{ $b->book->isbn ?? '-' }}</span>
                                </td>
                                <td class="py-3.5 px-4 text-slate-600">{{ $b->borrow_date->format('d/m/Y') }}</td>
                                <td class="py-3.5 px-4 text-slate-600">{{ $b->return_due_date->format('d/m/Y') }}</td>
                                <td class="py-3.5 px-4 text-slate-600">{{ $b->actual_return_date ? $b->actual_return_date->format('d/m/Y') : '-' }}</td>
                                <td class="py-3.5 px-4">
                                    <x-badge-status :status="$b->isOverdue() ? 'overdue' : $b->status" />
                                </td>
                                <td class="py-3.5 px-4 text-right font-bold {{ $b->fines > 0 ? 'text-rose-600' : 'text-slate-400' }}">
                                    {{ $b->fines > 0 ? 'Rp ' . number_format($b->fines, 0, ',', '.') : '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-8 text-center text-slate-400">Tidak ada data transaksi peminjaman pada rentang tanggal ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($borrowings->hasPages())
                <div class="p-4 border-t border-slate-200 bg-slate-50/50 no-print">
                    {{ $borrowings->links() }}
                </div>
            @endif
        </div>
    @elseif($reportType === 'popular')
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
            <div class="p-5 border-b border-slate-100">
                <h3 class="font-extrabold text-base text-slate-900">10 Buku Paling Sering Dipinjam</h3>
                <p class="text-xs text-slate-500">Berdasarkan frekuensi permohonan sirkulasi pada periode terpilih</p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-slate-50/75 border-b border-slate-200 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                            <th class="py-3 px-4">Peringkat</th>
                            <th class="py-3 px-4">Judul Buku</th>
                            <th class="py-3 px-4">Kategori</th>
                            <th class="py-3 px-4">Penulis & Penerbit</th>
                            <th class="py-3 px-4">Total Stok</th>
                            <th class="py-3 px-4 text-right">Frekuensi Dipinjam</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($popularBooks as $index => $book)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="py-3.5 px-4">
                                    <span class="w-6 h-6 rounded-full bg-slate-100 text-slate-700 font-extrabold flex items-center justify-center text-xs">
                                        {{ $index + 1 }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-9 h-12 rounded bg-slate-100 overflow-hidden flex-shrink-0 border border-slate-200">
                                            <img src="{{ $book->cover_url }}" alt="" class="w-full h-full object-cover">
                                        </div>
                                        <div>
                                            <span class="font-bold text-slate-900 block">{{ $book->title }}</span>
                                            <span class="font-mono text-[10px] text-slate-400">ISBN: {{ $book->isbn }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3.5 px-4 font-semibold text-slate-700">{{ $book->category->name ?? '-' }}</td>
                                <td class="py-3.5 px-4 text-slate-600">{{ $book->author }} ({{ $book->publisher }})</td>
                                <td class="py-3.5 px-4 font-semibold text-slate-700">{{ $book->stock }} eksemplar</td>
                                <td class="py-3.5 px-4 text-right">
                                    <span class="px-3 py-1 rounded-full text-xs font-extrabold bg-blue-50 text-blue-700 border border-blue-200">
                                        {{ $book->borrowings_count }} kali
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-8 text-center text-slate-400">Tidak ada catatan peminjaman pada periode ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
            <div class="p-5 border-b border-slate-100">
                <h3 class="font-extrabold text-base text-slate-900">Laporan Aktivitas Anggota Perpustakaan</h3>
                <p class="text-xs text-slate-500">Anggota dengan tingkat partisipasi peminjaman tertinggi</p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-slate-50/75 border-b border-slate-200 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                            <th class="py-3 px-4">Peringkat</th>
                            <th class="py-3 px-4">Nama Anggota</th>
                            <th class="py-3 px-4">Email & Kontak</th>
                            <th class="py-3 px-4">Alamat Domisili</th>
                            <th class="py-3 px-4 text-right">Jumlah Peminjaman</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($activeUsers as $index => $u)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="py-3.5 px-4">
                                    <span class="w-6 h-6 rounded-full bg-slate-100 text-slate-700 font-extrabold flex items-center justify-center text-xs">
                                        {{ $index + 1 }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 font-bold text-slate-900">{{ $u->name }}</td>
                                <td class="py-3.5 px-4">
                                    <span class="text-slate-800 block">{{ $u->email }}</span>
                                    <span class="text-slate-400 text-[10px] block">{{ $u->phone ?: '-' }}</span>
                                </td>
                                <td class="py-3.5 px-4 text-slate-600 max-w-xs truncate">{{ $u->address ?: '-' }}</td>
                                <td class="py-3.5 px-4 text-right">
                                    <span class="px-3 py-1 rounded-full text-xs font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        {{ $u->borrowings_count }} buku
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-8 text-center text-slate-400">Tidak ada catatan peminjaman anggota pada periode ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</x-layouts.app>
