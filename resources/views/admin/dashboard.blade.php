<x-layouts.app title="Admin Dashboard">
    <x-slot:header>Dashboard Administrator</x-slot:header>
    <x-slot:subheader>Ikhtisar operasional, status ketersediaan koleksi, dan sirkulasi peminjaman</x-slot:subheader>

    <!-- Welcome Hero Banner -->
    <div class="relative bg-gradient-to-r from-slate-900 via-indigo-950 to-blue-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl overflow-hidden">
        <div class="relative z-10 max-w-2xl space-y-3">
            <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-blue-500/20 border border-blue-400/30 text-blue-300 text-xs font-bold tracking-wide uppercase">
                <i data-lucide="shield-check" class="w-3.5 h-3.5"></i>
                <span>Sistem Kontrol Perpustakaan Terpadu</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">
                Halo, {{ auth()->user()->name }}! 👋
            </h1>
            <p class="text-xs sm:text-sm text-slate-300 leading-relaxed">
                Kelola koleksi buku fisik, proses permohonan sirkulasi, dan pantau kedisiplinan pengembalian buku anggota dalam satu sistem manajemen terpusat.
            </p>
            <div class="pt-2 flex flex-wrap gap-2.5">
                <a href="{{ route('admin.books.create') }}" class="inline-flex items-center space-x-2 px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white text-xs font-bold rounded-xl shadow-md transition-all">
                    <i data-lucide="plus-circle" class="w-4 h-4"></i>
                    <span>Tambah Buku Baru</span>
                </a>
                <a href="{{ route('admin.borrowings.index', ['status' => 'pending']) }}" class="inline-flex items-center space-x-2 px-4 py-2 bg-white/10 hover:bg-white/20 text-white border border-white/20 text-xs font-bold rounded-xl backdrop-blur-md transition-all">
                    <i data-lucide="clock" class="w-4 h-4 text-amber-300"></i>
                    <span>Tinjau Peminjaman Pending ({{ $pendingBorrowings }})</span>
                </a>
            </div>
        </div>
        <div class="absolute -right-10 -bottom-10 w-72 h-72 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>
    </div>

    <!-- Primary KPI Stat Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <x-stat-card
            title="Total Judul Buku"
            value="{{ number_format($totalBooks) }}"
            subtitle="{{ number_format($availableBooksCount) }} eksemplar tersedia"
            icon="book"
            variant="blue"
        />
        <x-stat-card
            title="Kategori Buku"
            value="{{ number_format($totalCategories) }}"
            subtitle="Klasifikasi disiplin ilmu"
            icon="folder-kanban"
            variant="indigo"
        />
        <x-stat-card
            title="Total Anggota"
            value="{{ number_format($totalUsers) }}"
            subtitle="Pengguna aktif terdaftar"
            icon="users"
            variant="purple"
        />
        <x-stat-card
            title="Total Sirkulasi"
            value="{{ number_format($totalBorrowings) }}"
            subtitle="{{ $activeBorrowings }} buku sedang dipinjam"
            icon="arrow-left-right"
            variant="emerald"
        />
    </div>

    <!-- Secondary Status Alert Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="p-4 rounded-2xl bg-amber-50 border border-amber-200/80 flex items-center justify-between">
            <div class="space-y-0.5">
                <span class="text-[11px] font-bold uppercase tracking-wider text-amber-800">Menunggu Persetujuan</span>
                <p class="text-xl font-extrabold text-amber-900">{{ $pendingBorrowings }} Permintaan</p>
            </div>
            <a href="{{ route('admin.borrowings.index', ['status' => 'pending']) }}" class="p-2.5 rounded-xl bg-amber-200/60 hover:bg-amber-300 text-amber-900 text-xs font-bold transition-colors">
                Periksa →
            </a>
        </div>

        <div class="p-4 rounded-2xl bg-blue-50 border border-blue-200/80 flex items-center justify-between">
            <div class="space-y-0.5">
                <span class="text-[11px] font-bold uppercase tracking-wider text-blue-800">Sedang Dipinjam</span>
                <p class="text-xl font-extrabold text-blue-900">{{ $activeBorrowings }} Transaksi</p>
            </div>
            <a href="{{ route('admin.borrowings.index', ['status' => 'borrowed']) }}" class="p-2.5 rounded-xl bg-blue-200/60 hover:bg-blue-300 text-blue-900 text-xs font-bold transition-colors">
                Detail →
            </a>
        </div>

        <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200/80 flex items-center justify-between">
            <div class="space-y-0.5">
                <span class="text-[11px] font-bold uppercase tracking-wider text-rose-800">Terlambat Kembali</span>
                <p class="text-xl font-extrabold text-rose-900">{{ $overdueBorrowings }} Peminjaman</p>
            </div>
            <a href="{{ route('admin.borrowings.index', ['status' => 'overdue']) }}" class="p-2.5 rounded-xl bg-rose-200/60 hover:bg-rose-300 text-rose-900 text-xs font-bold transition-colors">
                Tindak Lanjut →
            </a>
        </div>
    </div>

    <!-- Main Section: Recent Activity & Popular Books -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Recent Transactions Table (8 Cols) -->
        <div class="lg:col-span-8 bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden flex flex-col">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="font-extrabold text-base text-slate-900">Aktivitas Transaksi Terbaru</h3>
                    <p class="text-xs text-slate-500">Permohonan dan sirkulasi peminjaman buku terakhir</p>
                </div>
                <a href="{{ route('admin.borrowings.index') }}" class="text-xs font-bold text-blue-600 hover:text-blue-700">
                    Lihat Semua →
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-slate-50/75 border-b border-slate-100 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                            <th class="py-3 px-4">Kode & Tanggal</th>
                            <th class="py-3 px-4">Peminjam</th>
                            <th class="py-3 px-4">Buku</th>
                            <th class="py-3 px-4">Status</th>
                            <th class="py-3 px-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($recentBorrowings as $b)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="py-3.5 px-4">
                                    <span class="font-bold font-mono text-slate-800 block">{{ $b->borrowing_code }}</span>
                                    <span class="text-[11px] text-slate-400">{{ $b->created_at->format('d M Y, H:i') }}</span>
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="font-semibold text-slate-900 block truncate max-w-[140px]">{{ $b->user->name ?? 'User Dihapus' }}</span>
                                    <span class="text-[11px] text-slate-400 block truncate max-w-[140px]">{{ $b->user->email ?? '-' }}</span>
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="font-medium text-slate-800 block truncate max-w-[180px]">{{ $b->book->title ?? 'Buku Dihapus' }}</span>
                                    <span class="text-[10px] text-slate-400">Jatuh Tempo: {{ $b->return_due_date->format('d M Y') }}</span>
                                </td>
                                <td class="py-3.5 px-4">
                                    <x-badge-status :status="$b->isOverdue() ? 'overdue' : $b->status" />
                                </td>
                                <td class="py-3.5 px-4 text-right">
                                    @if($b->status === 'pending')
                                        <div class="inline-flex items-center space-x-1.5">
                                            <form method="POST" action="{{ route('admin.borrowings.approve', $b->id) }}">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" title="Setujui" class="px-2.5 py-1 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-bold text-[11px] shadow-xs">
                                                    Setujui
                                                </button>
                                            </form>
                                        </div>
                                    @else
                                        <a href="{{ route('admin.borrowings.index', ['search' => $b->borrowing_code]) }}" class="text-blue-600 hover:underline font-bold text-[11px]">
                                            Detail
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-8 text-center text-slate-400">Belum ada aktivitas transaksi peminjaman.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Popular Books Widget (4 Cols) -->
        <div class="lg:col-span-4 bg-white rounded-3xl border border-slate-200/80 shadow-xs p-5 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                    <div>
                        <h3 class="font-extrabold text-base text-slate-900">Buku Terpopuler</h3>
                        <p class="text-xs text-slate-500">Paling sering dipinjam oleh anggota</p>
                    </div>
                    <i data-lucide="flame" class="w-5 h-5 text-amber-500"></i>
                </div>

                <div class="divide-y divide-slate-100 mt-2">
                    @forelse($popularBooks as $index => $pb)
                        <div class="py-3 flex items-center space-x-3">
                            <span class="w-6 h-6 rounded-full bg-slate-100 text-slate-600 font-extrabold text-xs flex items-center justify-center flex-shrink-0">
                                {{ $index + 1 }}
                            </span>
                            <div class="w-10 h-14 rounded-lg bg-slate-100 overflow-hidden flex-shrink-0 border border-slate-200">
                                <img src="{{ $pb->cover_url }}" alt="{{ $pb->title }}" class="w-full h-full object-cover">
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-bold text-slate-900 truncate">{{ $pb->title }}</p>
                                <p class="text-[11px] text-slate-400 truncate">{{ $pb->author }}</p>
                                <span class="inline-block mt-0.5 text-[10px] font-bold text-blue-600 bg-blue-50 px-2 py-0.5 rounded-full">
                                    {{ $pb->borrowings_count }}x dipinjam
                                </span>
                            </div>
                        </div>
                    @empty
                        <p class="py-6 text-center text-xs text-slate-400">Belum ada data peminjaman buku.</p>
                    @endforelse
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100">
                <a href="{{ route('admin.books.index') }}" class="w-full py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl text-center block transition-colors">
                    Kelola Seluruh Katalog Buku
                </a>
            </div>
        </div>
    </div>
</x-layouts.app>
