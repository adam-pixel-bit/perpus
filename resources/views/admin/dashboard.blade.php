<x-layouts.app title="Admin Dashboard">
    <x-slot:pageTitle>Dashboard Administrator</x-slot:pageTitle>

    <!-- Subheader Toolbar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-2 border-b border-zinc-200/70">
        <div class="flex items-center flex-wrap gap-2 text-xs text-zinc-600 font-medium">
            <div class="flex items-center space-x-1.5 px-3 py-1.5 bg-white border border-zinc-200/80 rounded-xl shadow-2xs">
                <i data-lucide="layout-grid" class="w-3.5 h-3.5 text-zinc-500"></i>
                <span class="font-semibold text-zinc-800">Ringkasan Sistem</span>
            </div>
            
            <a href="{{ route('admin.borrowings.index', ['status' => 'pending']) }}" class="flex items-center space-x-1.5 px-3 py-1.5 bg-white border border-zinc-200/80 rounded-xl shadow-2xs hover:bg-zinc-50 transition-colors">
                <i data-lucide="clock" class="w-3.5 h-3.5 text-amber-500"></i>
                <span>Menunggu Persetujuan ({{ $pendingBorrowings }})</span>
            </a>

            @if($overdueBorrowings > 0)
                <a href="{{ route('admin.borrowings.index', ['status' => 'overdue']) }}" class="flex items-center space-x-1.5 px-3 py-1.5 bg-[#FEECEC] border border-red-200 text-red-600 rounded-xl shadow-2xs transition-colors">
                    <i data-lucide="alert-triangle" class="w-3.5 h-3.5 text-red-500"></i>
                    <span class="font-semibold">Terlambat ({{ $overdueBorrowings }})</span>
                </a>
            @endif

            <label class="inline-flex items-center space-x-2 pl-2 text-xs font-semibold text-zinc-700 cursor-pointer select-none">
                <span>Tampilkan Statistik</span>
                <div class="relative inline-block w-8 h-4 transition duration-200 ease-in-out align-middle select-none">
                    <input
                        type="checkbox"
                        id="toggle-dash-stats"
                        checked
                        onchange="toggleStatistics(this)"
                        class="toggle-checkbox absolute block w-4 h-4 rounded-full bg-white border-2 border-zinc-300 appearance-none cursor-pointer transition-all duration-200 checked:right-0 checked:bg-orange-600 checked:border-orange-600"
                    />
                    <label for="toggle-dash-stats" class="toggle-label block overflow-hidden h-4 rounded-full bg-zinc-200 cursor-pointer"></label>
                </div>
            </label>
        </div>

        <div class="flex items-center space-x-2">
            <a href="{{ route('admin.reports.export') }}" class="flex items-center space-x-1.5 px-3 py-1.5 text-xs font-semibold text-zinc-700 bg-white border border-zinc-200/80 rounded-xl hover:bg-zinc-50 shadow-2xs transition-all">
                <i data-lucide="download" class="w-3.5 h-3.5 text-zinc-500"></i>
                <span>Ekspor CSV</span>
            </a>
            <a href="{{ route('admin.books.create') }}" class="flex items-center space-x-1.5 px-3.5 py-1.5 text-xs font-semibold text-white bg-zinc-950 hover:bg-zinc-800 rounded-xl shadow-sm transition-all">
                <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                <span>+ Tambah Buku Baru</span>
            </a>
        </div>
    </div>

    <!-- Divided Horizontal KPI Statistics Strip -->
    <div id="stats-strip" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 divide-y sm:divide-y-0 sm:divide-x divide-zinc-200/80 bg-white border border-zinc-200/80 rounded-2xl p-4 sm:p-5 shadow-2xs">
        <!-- Metrik 1: Total Judul Buku -->
        <div class="sm:px-4 first:pl-0 last:pr-0 py-3 sm:py-0">
            <div class="flex items-center space-x-1.5 text-xs text-zinc-400 font-medium">
                <span>Total Judul Buku</span>
                <i data-lucide="info" class="w-3.5 h-3.5 text-zinc-300"></i>
            </div>
            <div class="text-2xl font-bold tracking-tight text-zinc-900 mt-1">
                {{ number_format($totalBooks) }}
            </div>
            <div class="flex items-center space-x-1.5 text-[11px] text-zinc-400 mt-1">
                <span>klasifikasi koleksi</span>
                <span class="text-emerald-600 bg-emerald-50 px-1.5 py-0.2 rounded font-medium">+{{ $totalCategories }} kategori</span>
            </div>
        </div>

        <!-- Metrik 2: Eksemplar Tersedia -->
        <div class="sm:px-4 py-3 sm:py-0">
            <div class="flex items-center space-x-1.5 text-xs text-zinc-400 font-medium">
                <span>Eksemplar Tersedia</span>
                <i data-lucide="info" class="w-3.5 h-3.5 text-zinc-300"></i>
            </div>
            <div class="text-2xl font-bold tracking-tight text-zinc-900 mt-1">
                {{ number_format($availableBooksCount) }}
            </div>
            <div class="flex items-center space-x-1.5 text-[11px] text-zinc-400 mt-1">
                <span>siap dipinjam</span>
                <span class="text-emerald-600 bg-emerald-50 px-1.5 py-0.2 rounded font-medium">Tersedia di Rak</span>
            </div>
        </div>

        <!-- Metrik 3: Sedang Dipinjam -->
        <div class="sm:px-4 py-3 sm:py-0">
            <div class="flex items-center space-x-1.5 text-xs text-zinc-400 font-medium">
                <span>Buku Dipinjam</span>
                <i data-lucide="info" class="w-3.5 h-3.5 text-zinc-300"></i>
            </div>
            <div class="text-2xl font-bold tracking-tight text-zinc-900 mt-1">
                {{ number_format($activeBorrowings) }}
            </div>
            <div class="flex items-center space-x-1.5 text-[11px] text-zinc-400 mt-1">
                <span>total transaksi</span>
                <span class="text-zinc-600 bg-zinc-100 px-1.5 py-0.2 rounded font-medium">{{ $totalBorrowings }} sirkulasi</span>
            </div>
        </div>

        <!-- Metrik 4: Anggota Aktif -->
        <div class="sm:px-4 last:pr-0 py-3 sm:py-0">
            <div class="flex items-center space-x-1.5 text-xs text-zinc-400 font-medium">
                <span>Anggota Terdaftar</span>
                <i data-lucide="info" class="w-3.5 h-3.5 text-zinc-300"></i>
            </div>
            <div class="text-2xl font-bold tracking-tight text-zinc-900 mt-1">
                {{ number_format($totalUsers) }}
            </div>
            <div class="flex items-center space-x-1.5 text-[11px] text-zinc-400 mt-1">
                <span>anggota aktif</span>
                <span class="text-emerald-600 bg-emerald-50 px-1.5 py-0.2 rounded font-medium">100% Terverifikasi</span>
            </div>
        </div>
    </div>

    <!-- Main Section: Aktivitas Transaksi & Buku Populer -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Recent Transactions Table (8 Cols) -->
        <div class="lg:col-span-8 bg-white rounded-2xl border border-zinc-200/80 shadow-2xs overflow-hidden flex flex-col">
            <div class="p-4 sm:p-5 border-b border-zinc-100 flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-sm text-zinc-900">Aktivitas Transaksi Terbaru</h3>
                    <p class="text-xs text-zinc-400 mt-0.5">Sirkulasi dan permohonan peminjaman buku terkini</p>
                </div>
                <a href="{{ route('admin.borrowings.index') }}" class="text-xs font-semibold text-zinc-600 hover:text-zinc-950 flex items-center space-x-1">
                    <span>Lihat Semua</span>
                    <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="border-b border-zinc-100 text-[11px] font-medium text-zinc-400">
                            <th class="py-3 px-4">Kode Transaksi</th>
                            <th class="py-3 px-4">Peminjam</th>
                            <th class="py-3 px-4">Judul Buku</th>
                            <th class="py-3 px-4">Status</th>
                            <th class="py-3 px-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100/70 text-zinc-700">
                        @forelse($recentBorrowings as $b)
                            <tr class="hover:bg-zinc-50/70 transition-colors">
                                <td class="py-3.5 px-4 font-mono font-medium text-zinc-900">
                                    {{ $b->borrowing_code }}
                                    <span class="block text-[10px] font-sans text-zinc-400 font-normal">{{ $b->created_at->format('d M Y, H:i') }}</span>
                                </td>
                                <td class="py-3.5 px-4">
                                    <div class="flex items-center space-x-2">
                                        <div class="w-6 h-6 rounded-full bg-zinc-100 flex items-center justify-center text-[10px] font-bold text-zinc-600 border border-zinc-200">
                                            {{ strtoupper(substr($b->user->name ?? 'U', 0, 1)) }}
                                        </div>
                                        <div class="truncate max-w-[130px]">
                                            <span class="font-semibold text-zinc-900 block truncate">{{ $b->user->name ?? 'User Dihapus' }}</span>
                                            <span class="text-[10px] text-zinc-400 block truncate">{{ $b->user->email ?? '-' }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="font-medium text-zinc-800 block truncate max-w-[180px]">{{ $b->book->title ?? 'Buku Dihapus' }}</span>
                                    <span class="text-[10px] text-zinc-400">Jatuh Tempo: {{ $b->return_due_date->format('d M Y') }}</span>
                                </td>
                                <td class="py-3.5 px-4">
                                    <x-badge-status :status="$b->isOverdue() ? 'overdue' : $b->status" />
                                </td>
                                <td class="py-3.5 px-4 text-right">
                                    @if($b->status === 'pending')
                                        <form method="POST" action="{{ route('admin.borrowings.approve', $b->id) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="px-2.5 py-1 bg-zinc-950 hover:bg-zinc-800 text-white rounded-lg font-semibold text-[11px] shadow-2xs transition-colors cursor-pointer">
                                                Setujui
                                            </button>
                                        </form>
                                    @else
                                        <a href="{{ route('admin.borrowings.index', ['search' => $b->borrowing_code]) }}" class="px-2.5 py-1 bg-white border border-zinc-200/80 hover:bg-zinc-50 text-zinc-700 rounded-lg font-semibold text-[11px] transition-colors">
                                            Detail
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-10 text-center text-zinc-400">Belum ada aktivitas transaksi peminjaman.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Popular Books Widget (4 Cols) -->
        <div class="lg:col-span-4 bg-white rounded-2xl border border-zinc-200/80 shadow-2xs p-5 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between pb-3.5 border-b border-zinc-100">
                    <div>
                        <h3 class="font-bold text-sm text-zinc-900">Buku Paling Populer</h3>
                        <p class="text-xs text-zinc-400 mt-0.5">Koleksi yang paling sering dipinjam</p>
                    </div>
                    <div class="w-7 h-7 rounded-lg bg-[#FEF6E8] flex items-center justify-center text-amber-500">
                        <i data-lucide="flame" class="w-4 h-4"></i>
                    </div>
                </div>

                <div class="divide-y divide-zinc-100 mt-2">
                    @forelse($popularBooks as $index => $pb)
                        <div class="py-3 flex items-center space-x-3">
                            <span class="w-5 h-5 rounded-full bg-zinc-100 text-zinc-500 font-bold text-[10px] flex items-center justify-center flex-shrink-0">
                                {{ $index + 1 }}
                            </span>
                            <div class="w-9 h-12 rounded-md bg-zinc-100 overflow-hidden flex-shrink-0 border border-zinc-200/80">
                                <img src="{{ $pb->cover_url }}" alt="{{ $pb->title }}" class="w-full h-full object-cover" onerror="this.onerror=null; this.src=FALLBACK_BOOK_COVER;">
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-semibold text-zinc-900 truncate">{{ $pb->title }}</p>
                                <p class="text-[11px] text-zinc-400 truncate">{{ $pb->author }}</p>
                                <div class="flex items-center space-x-2 mt-1">
                                    <span class="text-[10px] font-semibold text-brand-orange bg-[#FFF7F2] px-1.5 py-0.2 rounded">
                                        {{ $pb->borrowings_count }}x dipinjam
                                    </span>
                                    <span class="text-[10px] text-zinc-400">
                                        Stok: {{ $pb->available_stock }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="py-8 text-center text-xs text-zinc-400">Belum ada data peminjaman buku.</p>
                    @endforelse
                </div>
            </div>

            <div class="pt-4 border-t border-zinc-100">
                <a href="{{ route('admin.books.index') }}" class="w-full py-2 bg-zinc-50 hover:bg-zinc-100 border border-zinc-200/80 text-zinc-700 text-xs font-semibold rounded-xl text-center block transition-colors">
                    Kelola Seluruh Katalog Buku →
                </a>
            </div>
        </div>
    </div>
</x-layouts.app>
