<x-layouts.app title="Portal Anggota">
    <x-slot:pageTitle>Dashboard Anggota</x-slot:pageTitle>

    <!-- Top Subheader Toolbar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-2 border-b border-zinc-200/70">
        <div class="flex items-center flex-wrap gap-2 text-xs text-zinc-600 font-medium">
            <div class="flex items-center space-x-1.5 px-3 py-1.5 bg-white border border-zinc-200/80 rounded-xl shadow-2xs">
                <i data-lucide="layout-grid" class="w-3.5 h-3.5 text-zinc-500"></i>
                <span class="font-semibold text-zinc-800">Ruang Anggota</span>
                <i data-lucide="chevron-down" class="w-3 h-3 text-zinc-400"></i>
            </div>
            
            <span class="text-xs text-zinc-400 font-normal">Nomor Anggota: #{{ str_pad(auth()->id(), 5, '0', STR_PAD_LEFT) }}</span>

            <div class="flex items-center space-x-2 pl-2">
                <span class="text-xs text-zinc-500 font-medium">Tampilkan Statistik</span>
                <button type="button" class="w-8 h-4.5 bg-brand-orange rounded-full relative cursor-pointer focus:outline-none transition-colors" id="stats-toggle">
                    <span class="w-3.5 h-3.5 bg-white rounded-full absolute right-0.5 top-0.5 shadow-xs transition-transform"></span>
                </button>
            </div>
        </div>

        <div class="flex items-center space-x-2">
            <a href="{{ route('user.catalog.index') }}" class="flex items-center space-x-1.5 px-3.5 py-1.5 text-xs font-semibold text-white bg-zinc-950 hover:bg-zinc-800 rounded-xl shadow-sm transition-all">
                <i data-lucide="compass" class="w-3.5 h-3.5"></i>
                <span>Jelajahi Katalog Buku</span>
            </a>
        </div>
    </div>

    <!-- Divided Horizontal KPI Statistics Strip -->
    <div id="stats-strip" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 divide-y sm:divide-y-0 sm:divide-x divide-zinc-200/80 bg-white border border-zinc-200/80 rounded-2xl p-4 sm:p-5 shadow-2xs">
        <!-- Metric 1: Sedang Dipinjam -->
        <div class="sm:px-4 first:pl-0 last:pr-0 py-3 sm:py-0">
            <div class="flex items-center space-x-1.5 text-xs text-zinc-400 font-medium">
                <span>Sedang Dipinjam</span>
                <i data-lucide="info" class="w-3.5 h-3.5 text-zinc-300"></i>
            </div>
            <div class="text-2xl font-bold tracking-tight text-zinc-900 mt-1">
                {{ $currentlyBorrowedCount }} <span class="text-xs text-zinc-400 font-normal">/ 3 buku</span>
            </div>
            <div class="flex items-center space-x-1.5 text-[11px] text-zinc-400 mt-1">
                <span>sisa kuota:</span>
                <span class="text-emerald-600 bg-emerald-50 px-1.5 py-0.2 rounded font-medium">{{ max(0, 3 - $currentlyBorrowedCount) }} buku tersisa</span>
            </div>
        </div>

        <!-- Metric 2: Menunggu Persetujuan -->
        <div class="sm:px-4 py-3 sm:py-0">
            <div class="flex items-center space-x-1.5 text-xs text-zinc-400 font-medium">
                <span>Menunggu Persetujuan</span>
                <i data-lucide="info" class="w-3.5 h-3.5 text-zinc-300"></i>
            </div>
            <div class="text-2xl font-bold tracking-tight text-zinc-900 mt-1">
                {{ $pendingApprovalCount }}
            </div>
            <div class="flex items-center space-x-1.5 text-[11px] text-zinc-400 mt-1">
                <span>status verifikasi</span>
                <span class="text-amber-600 bg-[#FEF6E8] px-1.5 py-0.2 rounded font-medium">Dalam Antrean</span>
            </div>
        </div>

        <!-- Metric 3: Buku Dikembalikan -->
        <div class="sm:px-4 py-3 sm:py-0">
            <div class="flex items-center space-x-1.5 text-xs text-zinc-400 font-medium">
                <span>Buku Selesai Dibaca</span>
                <i data-lucide="info" class="w-3.5 h-3.5 text-zinc-300"></i>
            </div>
            <div class="text-2xl font-bold tracking-tight text-zinc-900 mt-1">
                {{ $returnedCount }}
            </div>
            <div class="flex items-center space-x-1.5 text-[11px] text-zinc-400 mt-1">
                <span>riwayat transaksi</span>
                <span class="text-zinc-600 bg-zinc-100 px-1.5 py-0.2 rounded font-medium">Selesai</span>
            </div>
        </div>

        <!-- Metric 4: Kasus Terlambat -->
        <div class="sm:px-4 last:pr-0 py-3 sm:py-0">
            <div class="flex items-center space-x-1.5 text-xs text-zinc-400 font-medium">
                <span>Buku Terlambat</span>
                <i data-lucide="info" class="w-3.5 h-3.5 text-zinc-300"></i>
            </div>
            <div class="text-2xl font-bold tracking-tight text-zinc-900 mt-1">
                {{ $overdueCount }}
            </div>
            <div class="flex items-center space-x-1.5 text-[11px] text-zinc-400 mt-1">
                <span>status tempo</span>
                <span class="{{ $overdueCount > 0 ? 'text-red-600 bg-[#FEECEC]' : 'text-emerald-600 bg-emerald-50' }} px-1.5 py-0.2 rounded font-medium">
                    {{ $overdueCount > 0 ? 'Segera Kembalikan' : 'Tertib' }}
                </span>
            </div>
        </div>
    </div>

    <!-- Main Section: Peminjaman Saya & Buku Rekomendasi -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        <!-- Recent Personal Borrowing Activity (7 Cols) -->
        <div class="lg:col-span-7 bg-white rounded-2xl border border-zinc-200/80 shadow-2xs overflow-hidden flex flex-col">
            <div class="p-4 sm:p-5 border-b border-zinc-100 flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-sm text-zinc-900">Peminjaman Buku Saya</h3>
                    <p class="text-xs text-zinc-400 mt-0.5">Daftar buku yang baru-baru ini Anda ajukan atau pinjam</p>
                </div>
                <a href="{{ route('user.borrowings.index') }}" class="text-xs font-semibold text-zinc-600 hover:text-zinc-950 flex items-center space-x-1">
                    <span>Lihat Semua</span>
                    <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                </a>
            </div>

            <div class="divide-y divide-zinc-100/70 text-xs">
                @forelse($recentBorrowings as $b)
                    <div class="p-4 flex items-center justify-between space-x-3 hover:bg-zinc-50/60 transition-colors">
                        <div class="flex items-center space-x-3 min-w-0">
                            <div class="w-10 h-14 rounded-lg bg-zinc-100 overflow-hidden flex-shrink-0 border border-zinc-200/80">
                                <img src="{{ $b->book->cover_url }}" alt="{{ $b->book->title }}" class="w-full h-full object-cover" onerror="this.onerror=null; this.src=FALLBACK_BOOK_COVER;">
                            </div>
                            <div class="min-w-0">
                                <h4 class="font-semibold text-zinc-900 truncate">{{ $b->book->title ?? 'Buku' }}</h4>
                                <p class="text-[11px] text-zinc-400 truncate">{{ $b->book->author ?? '-' }}</p>
                                <div class="flex items-center space-x-2 mt-1">
                                    <span class="text-[10px] font-mono text-zinc-400">{{ $b->borrowing_code }}</span>
                                    <span class="text-zinc-300">•</span>
                                    <span class="text-[10px] {{ $b->isOverdue() ? 'text-red-600 font-bold' : 'text-zinc-400' }}">
                                        Batas: {{ $b->return_due_date->format('d M Y') }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="flex-shrink-0 text-right">
                            <x-badge-status :status="$b->isOverdue() ? 'overdue' : $b->status" />
                            @if($b->status === 'pending')
                                <form method="POST" action="{{ route('user.borrowings.cancel', $b->id) }}" class="mt-1.5 text-right">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Batalkan pengajuan peminjaman ini?')" class="text-[11px] text-red-500 hover:text-red-700 font-medium cursor-pointer">
                                        Batalkan
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="py-10 text-center text-zinc-400">
                        <i data-lucide="book-open" class="w-8 h-8 mx-auto mb-2 text-zinc-300"></i>
                        <p class="font-semibold text-zinc-700">Belum ada peminjaman aktif</p>
                        <p class="text-xs text-zinc-400 mt-1">Jelajahi koleksi kami dan ajukan peminjaman buku kapan saja.</p>
                        <a href="{{ route('user.catalog.index') }}" class="inline-flex items-center space-x-1.5 px-3 py-1.5 mt-3 bg-zinc-950 hover:bg-zinc-800 text-white rounded-xl text-xs font-semibold shadow-2xs">
                            <span>Buka Katalog Buku</span>
                        </a>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Recommended Books (5 Cols) -->
        <div class="lg:col-span-5 bg-white rounded-2xl border border-zinc-200/80 shadow-2xs p-4 sm:p-5 space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-zinc-100">
                <div>
                    <h3 class="font-bold text-sm text-zinc-900">Rekomendasi Buku</h3>
                    <p class="text-xs text-zinc-400 mt-0.5">Koleksi terpopuler yang siap dipinjam hari ini</p>
                </div>
                <div class="w-6 h-6 rounded-md bg-[#FFF7F2] text-brand-orange flex items-center justify-center">
                    <i data-lucide="sparkles" class="w-3.5 h-3.5"></i>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                @foreach($recommendedBooks as $rb)
                    <div class="rounded-xl border border-zinc-200/80 p-2.5 hover:border-zinc-300 transition-all flex flex-col justify-between bg-white group">
                        <div class="space-y-2">
                            <div class="aspect-[3/4] rounded-lg overflow-hidden bg-zinc-100 relative border border-zinc-200/60">
                                <img src="{{ $rb->cover_url }}" alt="{{ $rb->title }}" class="w-full h-full object-cover group-hover:scale-102 transition-transform duration-200" onerror="this.onerror=null; this.src=FALLBACK_BOOK_COVER;">
                                <span class="absolute top-1.5 right-1.5 px-1.5 py-0.2 rounded text-[9px] font-semibold bg-zinc-950/80 text-white backdrop-blur-xs">
                                    {{ $rb->available_stock }} eks
                                </span>
                            </div>
                            <div>
                                <span class="text-[10px] font-semibold text-brand-orange block uppercase tracking-tight">{{ $rb->category->name ?? 'Umum' }}</span>
                                <h4 class="font-semibold text-xs text-zinc-900 line-clamp-1 group-hover:text-brand-orange transition-colors" title="{{ $rb->title }}">
                                    {{ $rb->title }}
                                </h4>
                                <p class="text-[10px] text-zinc-400 line-clamp-1">{{ $rb->author }}</p>
                            </div>
                        </div>

                        <div class="pt-2">
                            <a href="{{ route('user.catalog.show', $rb->slug) }}" class="w-full py-1 bg-zinc-50 hover:bg-zinc-100 border border-zinc-200/80 text-zinc-800 text-[11px] font-semibold rounded-lg text-center block transition-colors">
                                Rincian & Pinjam
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-layouts.app>
