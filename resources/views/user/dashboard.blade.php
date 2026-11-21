<x-layouts.app title="Portal Anggota">
    <x-slot:header>Selamat Datang, {{ auth()->user()->name }} 👋</x-slot:header>
    <x-slot:subheader>Pantau buku yang sedang Anda pinjam dan temukan koleksi literatur terbaru</x-slot:subheader>

    <!-- Hero Card for User -->
    <div class="relative bg-gradient-to-r from-blue-700 via-indigo-700 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl overflow-hidden">
        <div class="relative z-10 max-w-2xl space-y-3">
            <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-blue-500/20 border border-blue-400/30 text-blue-200 text-xs font-bold tracking-wide uppercase">
                <i data-lucide="book-marked" class="w-3.5 h-3.5"></i>
                <span>Kartu Anggota Digital #{{ str_pad(auth()->id(), 5, '0', STR_PAD_LEFT) }}</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">
                Membaca Membuka Cakrawala Dunia
            </h1>
            <p class="text-xs sm:text-sm text-blue-100 leading-relaxed">
                Anda dapat meminjam hingga maksimal 3 buku secara bersamaan. Pastikan untuk mengembalikan buku sebelum tanggal jatuh tempo agar tidak terkena denda keterlambatan.
            </p>
            <div class="pt-2 flex flex-wrap gap-2.5">
                <a href="{{ route('user.catalog.index') }}" class="inline-flex items-center space-x-2 px-4 py-2 bg-white text-blue-900 hover:bg-blue-50 text-xs font-bold rounded-xl shadow-md transition-all">
                    <i data-lucide="compass" class="w-4 h-4"></i>
                    <span>Jelajahi Katalog Buku</span>
                </a>
                <a href="{{ route('user.borrowings.index') }}" class="inline-flex items-center space-x-2 px-4 py-2 bg-white/10 hover:bg-white/20 text-white border border-white/20 text-xs font-bold rounded-xl backdrop-blur-md transition-all">
                    <i data-lucide="history" class="w-4 h-4"></i>
                    <span>Riwayat Peminjaman Saya</span>
                </a>
            </div>
        </div>
        <div class="absolute -right-10 -bottom-10 w-72 h-72 bg-white/10 rounded-full blur-3xl pointer-events-none"></div>
    </div>

    <!-- User KPI Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <x-stat-card
            title="Sedang Dipinjam"
            value="{{ $currentlyBorrowedCount }}"
            subtitle="Maksimal kuota: 3 buku"
            icon="book-open"
            variant="blue"
        />
        <x-stat-card
            title="Menunggu Persetujuan"
            value="{{ $pendingApprovalCount }}"
            subtitle="Dalam antrean verifikasi Admin"
            icon="clock"
            variant="amber"
        />
        <x-stat-card
            title="Telah Dikembalikan"
            value="{{ $returnedCount }}"
            subtitle="Selesai dibaca"
            icon="check-circle-2"
            variant="emerald"
        />
        <x-stat-card
            title="Terlambat Kembali"
            value="{{ $overdueCount }}"
            subtitle="{{ $overdueCount > 0 ? 'Segera kembalikan!' : 'Status disiplin' }}"
            icon="alert-triangle"
            variant="{{ $overdueCount > 0 ? 'rose' : 'purple' }}"
        />
    </div>

    <!-- Main Section: Active Borrowings & Recommended Books -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        <!-- Recent Personal Borrowing Activity (7 Cols) -->
        <div class="lg:col-span-7 bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden flex flex-col">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="font-extrabold text-base text-slate-900">Aktivitas Peminjaman Saya</h3>
                    <p class="text-xs text-slate-500">Daftar buku yang baru-baru ini Anda ajukan atau pinjam</p>
                </div>
                <a href="{{ route('user.borrowings.index') }}" class="text-xs font-bold text-blue-600 hover:text-blue-700">
                    Lihat Semua →
                </a>
            </div>

            <div class="divide-y divide-slate-100">
                @forelse($recentBorrowings as $b)
                    <div class="p-4 sm:p-5 flex items-center justify-between space-x-4 hover:bg-slate-50/70 transition-colors">
                        <div class="flex items-center space-x-3.5 min-w-0">
                            <div class="w-12 h-16 rounded-xl bg-slate-100 overflow-hidden flex-shrink-0 border border-slate-200 shadow-2xs">
                                <img src="{{ $b->book->cover_url }}" alt="{{ $b->book->title }}" class="w-full h-full object-cover">
                            </div>
                            <div class="min-w-0">
                                <h4 class="font-bold text-sm text-slate-900 truncate">{{ $b->book->title ?? 'Buku' }}</h4>
                                <p class="text-xs text-slate-500 truncate">{{ $b->book->author ?? '-' }}</p>
                                <div class="flex items-center space-x-2 mt-1">
                                    <span class="text-[10px] font-mono text-slate-400">Kode: {{ $b->borrowing_code }}</span>
                                    <span class="text-slate-300">•</span>
                                    <span class="text-[10px] {{ $b->isOverdue() ? 'text-rose-600 font-bold' : 'text-slate-500' }}">
                                        Batas: {{ $b->return_due_date->format('d M Y') }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="flex-shrink-0 text-right">
                            <x-badge-status :status="$b->isOverdue() ? 'overdue' : $b->status" />
                            @if($b->status === 'pending')
                                <form method="POST" action="{{ route('user.borrowings.cancel', $b->id) }}" class="mt-2 text-right">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Batalkan pengajuan peminjaman ini?')" class="text-[10px] text-rose-600 font-semibold hover:underline">
                                        Batalkan
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="py-12 text-center text-slate-400">
                        <i data-lucide="book-open" class="w-8 h-8 mx-auto mb-2 text-slate-300"></i>
                        <p class="font-bold text-slate-700">Belum ada riwayat peminjaman</p>
                        <p class="text-xs text-slate-400 mt-1">Temukan buku yang Anda sukai di katalog dan ajukan peminjaman pertama Anda.</p>
                        <a href="{{ route('user.catalog.index') }}" class="inline-flex items-center space-x-1.5 px-4 py-2 mt-3 bg-blue-600 text-white rounded-xl text-xs font-bold shadow-xs">
                            <span>Buka Katalog Buku</span>
                        </a>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Recommended Books Carousel / Grid (5 Cols) -->
        <div class="lg:col-span-5 bg-white rounded-3xl border border-slate-200/80 shadow-xs p-5 space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div>
                    <h3 class="font-extrabold text-base text-slate-900">Rekomendasi Koleksi</h3>
                    <p class="text-xs text-slate-500">Buku berkualitas yang siap dipinjam hari ini</p>
                </div>
                <i data-lucide="sparkles" class="w-5 h-5 text-amber-500"></i>
            </div>

            <div class="grid grid-cols-2 gap-3">
                @foreach($recommendedBooks as $rb)
                    <div class="group rounded-2xl border border-slate-200/80 p-3 hover:border-blue-500/50 hover:shadow-md transition-all flex flex-col justify-between bg-slate-50/50">
                        <div class="space-y-2">
                            <div class="aspect-[3/4] rounded-xl overflow-hidden bg-slate-200 relative">
                                <img src="{{ $rb->cover_url }}" alt="{{ $rb->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                <span class="absolute top-2 right-2 px-2 py-0.5 rounded-full text-[9px] font-bold bg-emerald-600 text-white shadow-xs">
                                    {{ $rb->available_stock }} Eks
                                </span>
                            </div>
                            <div>
                                <span class="text-[10px] font-semibold text-blue-600 block uppercase tracking-wider">{{ $rb->category->name ?? 'Umum' }}</span>
                                <h4 class="font-bold text-xs text-slate-900 line-clamp-1 group-hover:text-blue-600 transition-colors" title="{{ $rb->title }}">
                                    {{ $rb->title }}
                                </h4>
                                <p class="text-[10px] text-slate-400 line-clamp-1">{{ $rb->author }}</p>
                            </div>
                        </div>

                        <div class="pt-2">
                            <a href="{{ route('user.catalog.show', $rb->slug) }}" class="w-full py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-[11px] font-bold rounded-lg text-center block transition-colors">
                                Rincian & Pinjam
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-layouts.app>
