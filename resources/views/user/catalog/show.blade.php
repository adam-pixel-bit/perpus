<x-layouts.app title="{{ $book->title }}">
    <x-slot:header>Rincian Koleksi Buku</x-slot:header>
    <x-slot:subheader>Informasi detail bibliografi dan form permohonan peminjaman buku</x-slot:subheader>

    <div class="space-y-6">
        <!-- Back Link -->
        <div>
            <a href="{{ route('user.catalog.index') }}" class="inline-flex items-center space-x-1.5 px-3.5 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:text-slate-900 bg-white border border-slate-200 transition-colors">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                <span>Kembali ke Katalog</span>
            </a>
        </div>

        <!-- Book Detail Main Card -->
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs p-6 sm:p-8">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-start">
                <!-- Cover Image (4 cols) -->
                <div class="md:col-span-4 lg:col-span-3">
                    <div class="rounded-2xl overflow-hidden border border-slate-200 shadow-xl aspect-[3/4] bg-slate-100">
                        <img src="{{ $book->cover_url }}" alt="{{ $book->title }}" class="w-full h-full object-cover">
                    </div>
                </div>

                <!-- Info & Borrow Form (8 cols) -->
                <div class="md:col-span-8 lg:col-span-9 space-y-6">
                    <div>
                        <div class="flex flex-wrap items-center gap-2 mb-2">
                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                {{ $book->category->name ?? 'Kategori Umum' }}
                            </span>
                            @if($book->is_available && $book->available_stock > 0)
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    Tersedia ({{ $book->available_stock }} dari {{ $book->stock }} eksemplar)
                                </span>
                            @else
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                    Stok Habis / Sedang Dipinjam
                                </span>
                            @endif
                        </div>
                        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">{{ $book->title }}</h1>
                        <p class="text-sm font-semibold text-slate-500 mt-1">Karya: {{ $book->author }} • Penerbit: {{ $book->publisher }} ({{ $book->publication_year }})</p>
                    </div>

                    <!-- Metadata Grid -->
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 p-4 rounded-2xl bg-slate-50 border border-slate-200 text-xs">
                        <div>
                            <span class="text-slate-400 block font-semibold uppercase text-[10px]">Nomor ISBN</span>
                            <span class="font-mono font-bold text-slate-800">{{ $book->isbn }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400 block font-semibold uppercase text-[10px]">Tahun Publikasi</span>
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
                        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700">Sinopsis & Ringkasan Buku</h3>
                        <p class="text-sm text-slate-600 leading-relaxed whitespace-pre-line">
                            {{ $book->description ?: 'Belum ada ringkasan yang tersedia untuk buku ini.' }}
                        </p>
                    </div>

                    <!-- Borrowing Action Form -->
                    <div class="pt-6 border-t border-slate-100">
                        @if($book->is_available && $book->available_stock > 0)
                            <div class="p-5 rounded-2xl bg-blue-50/60 border border-blue-200/80 space-y-4">
                                <div>
                                    <h4 class="font-extrabold text-sm text-blue-950">Ajukan Peminjaman Buku Ini</h4>
                                    <p class="text-xs text-blue-800 mt-0.5">Permohonan akan diverifikasi oleh staf perpustakaan. Maksimal pinjam 14 hari.</p>
                                </div>

                                <form method="POST" action="{{ route('user.borrowings.store') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-end">
                                    @csrf
                                    <input type="hidden" name="book_id" value="{{ $book->id }}">

                                    <div class="sm:col-span-4">
                                        <label for="duration_days" class="block text-[11px] font-bold uppercase tracking-wider text-slate-700 mb-1">
                                            Durasi Peminjaman
                                        </label>
                                        <select
                                            id="duration_days"
                                            name="duration_days"
                                            required
                                            class="w-full px-3 py-2 text-xs bg-white border border-slate-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-slate-800"
                                        >
                                            <option value="3">3 Hari</option>
                                            <option value="7" selected>7 Hari (Standar)</option>
                                            <option value="10">10 Hari</option>
                                            <option value="14">14 Hari (Maksimal)</option>
                                        </select>
                                    </div>

                                    <div class="sm:col-span-5">
                                        <label for="notes" class="block text-[11px] font-bold uppercase tracking-wider text-slate-700 mb-1">
                                            Catatan Keperluan
                                        </label>
                                        <input
                                            type="text"
                                            id="notes"
                                            name="notes"
                                            placeholder="Contoh: Keperluan tugas kuliah"
                                            class="w-full px-3 py-2 text-xs bg-white border border-slate-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-slate-900"
                                        >
                                    </div>

                                    <div class="sm:col-span-3">
                                        <button
                                            type="submit"
                                            class="w-full py-2 px-4 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-md shadow-blue-600/30 transition-all flex items-center justify-center space-x-1.5"
                                        >
                                            <i data-lucide="book-open" class="w-4 h-4"></i>
                                            <span>Ajukan Pinjam</span>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        @else
                            <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs flex items-center space-x-2">
                                <i data-lucide="alert-octagon" class="w-4 h-4 text-rose-600 flex-shrink-0"></i>
                                <span>Maaf, seluruh eksemplar buku ini sedang dipinjam oleh anggota lain. Silakan periksa kembali beberapa hari ke depan.</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Books in Same Category -->
        @if($relatedBooks->count() > 0)
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs p-6 space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div>
                        <h3 class="font-extrabold text-base text-slate-900">Buku Lainnya dalam Kategori Ini</h3>
                        <p class="text-xs text-slate-500">Koleksi terkait dalam bidang {{ $book->category->name ?? 'yang sama' }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    @foreach($relatedBooks as $rb)
                        <a href="{{ route('user.catalog.show', $rb->slug) }}" class="group block rounded-2xl border border-slate-200/80 p-3 hover:border-blue-500/50 hover:shadow-md transition-all">
                            <div class="aspect-[3/4] rounded-xl overflow-hidden bg-slate-100 mb-2">
                                <img src="{{ $rb->cover_url }}" alt="{{ $rb->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            </div>
                            <h4 class="font-bold text-xs text-slate-900 line-clamp-1 group-hover:text-blue-600 transition-colors">{{ $rb->title }}</h4>
                            <p class="text-[10px] text-slate-400 line-clamp-1 mt-0.5">{{ $rb->author }}</p>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</x-layouts.app>
