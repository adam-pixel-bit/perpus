<x-layouts.app title="Detail Buku">
    <x-slot:header>Rincian Informasi Buku</x-slot:header>
    <x-slot:subheader>Spesifikasi koleksi, ketersediaan fisik di rak, dan riwayat sirkulasi</x-slot:subheader>

    <div class="space-y-6">
        <!-- Back Link & Action Bar -->
        <div class="flex items-center justify-between">
            <a href="{{ route('admin.books.index') }}" class="inline-flex items-center space-x-1.5 px-3.5 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:text-slate-900 bg-white border border-slate-200 transition-colors">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                <span>Kembali ke Daftar Buku</span>
            </a>
            <div class="flex items-center space-x-2">
                <a href="{{ route('admin.books.edit', $book->id) }}" class="inline-flex items-center space-x-1.5 px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-xl text-xs font-bold transition-all shadow-sm">
                    <i data-lucide="edit-3" class="w-4 h-4"></i>
                    <span>Edit Buku</span>
                </a>
            </div>
        </div>

        <!-- Book Detail Card -->
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs p-6 sm:p-8">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-start">
                <!-- Cover Image (4 cols) -->
                <div class="md:col-span-4 lg:col-span-3">
                    <div class="rounded-2xl overflow-hidden border border-slate-200 shadow-lg aspect-[3/4] bg-slate-100">
                        <img src="{{ $book->cover_url }}" alt="{{ $book->title }}" class="w-full h-full object-cover" onerror="this.onerror=null; this.src=FALLBACK_BOOK_COVER;">
                    </div>
                </div>

                <!-- Specs & Description (8 cols) -->
                <div class="md:col-span-8 lg:col-span-9 space-y-6">
                    <div>
                        <div class="flex flex-wrap items-center gap-2 mb-2">
                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                {{ $book->category->name ?? 'Kategori Umum' }}
                            </span>
                            @if($book->is_available && $book->available_stock > 0)
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    Tersedia ({{ $book->available_stock }} Eksemplar)
                                </span>
                            @else
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                    Stok Habis Dipinjam
                                </span>
                            @endif
                        </div>
                        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">{{ $book->title }}</h1>
                        <p class="text-sm font-semibold text-slate-500 mt-1">Oleh: {{ $book->author }}</p>
                    </div>

                    <!-- Key Metadata Grid -->
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 p-4 rounded-2xl bg-slate-50 border border-slate-200 text-xs">
                        <div>
                            <span class="text-slate-400 block font-semibold uppercase text-[10px]">Nomor ISBN</span>
                            <span class="font-mono font-bold text-slate-800">{{ $book->isbn }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400 block font-semibold uppercase text-[10px]">Penerbit</span>
                            <span class="font-bold text-slate-800">{{ $book->publisher }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400 block font-semibold uppercase text-[10px]">Tahun Terbit</span>
                            <span class="font-bold text-slate-800">{{ $book->publication_year }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400 block font-semibold uppercase text-[10px]">Lokasi Rak</span>
                            <span class="font-mono font-bold text-slate-800 flex items-center space-x-1">
                                <i data-lucide="map-pin" class="w-3.5 h-3.5 text-blue-600"></i>
                                <span>{{ $book->shelf_location }}</span>
                            </span>
                        </div>
                    </div>

                    <!-- Synopsis -->
                    <div class="space-y-2">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700">Sinopsis / Deskripsi Buku</h3>
                        <p class="text-sm text-slate-600 leading-relaxed whitespace-pre-line">
                            {{ $book->description ?: 'Belum ada deskripsi yang ditambahkan untuk buku ini.' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Borrowing History for this Book -->
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="font-extrabold text-base text-slate-900">Riwayat Sirkulasi Peminjaman</h3>
                    <p class="text-xs text-slate-500">Daftar anggota yang pernah atau sedang meminjam buku ini</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-slate-50/75 border-b border-slate-200 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                            <th class="py-3 px-4">Kode Transaksi</th>
                            <th class="py-3 px-4">Peminjam</th>
                            <th class="py-3 px-4">Tanggal Pinjam</th>
                            <th class="py-3 px-4">Batas Pengembalian</th>
                            <th class="py-3 px-4">Tanggal Dikembalikan</th>
                            <th class="py-3 px-4">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($recentBorrowings as $rb)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="py-3 px-4 font-mono font-bold text-slate-800">{{ $rb->borrowing_code }}</td>
                                <td class="py-3 px-4 font-semibold text-slate-900">{{ $rb->user->name ?? '-' }}</td>
                                <td class="py-3 px-4 text-slate-600">{{ $rb->borrow_date->format('d M Y') }}</td>
                                <td class="py-3 px-4 text-slate-600">{{ $rb->return_due_date->format('d M Y') }}</td>
                                <td class="py-3 px-4 text-slate-600">{{ $rb->actual_return_date ? $rb->actual_return_date->format('d M Y') : '-' }}</td>
                                <td class="py-3 px-4">
                                    <x-badge-status :status="$rb->isOverdue() ? 'overdue' : $rb->status" />
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-8 text-center text-slate-400">Belum ada riwayat peminjaman untuk buku ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layouts.app>
