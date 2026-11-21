<x-layouts.app title="{{ $book->title }}">
    <x-slot:pageTitle>Rincian Koleksi Buku</x-slot:pageTitle>

    <div class="space-y-6">
        <!-- Back Link -->
        <div class="pb-2 border-b border-zinc-200/70">
            <a href="{{ route('user.catalog.index') }}" class="inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold text-zinc-600 hover:text-zinc-900 bg-white border border-zinc-200/80 shadow-2xs transition-colors">
                <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                <span>Kembali ke Katalog</span>
            </a>
        </div>

        <!-- Book Detail Main Card -->
        <div class="bg-white rounded-2xl border border-zinc-200/80 shadow-2xs p-5 sm:p-7">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-6 sm:gap-8 items-start">
                <!-- Cover Image (4 cols) -->
                <div class="md:col-span-4 lg:col-span-3">
                    <div class="rounded-xl overflow-hidden border border-zinc-200/80 shadow-xs aspect-[3/4] bg-zinc-100">
                        <img src="{{ $book->cover_url }}" alt="{{ $book->title }}" class="w-full h-full object-cover" onerror="this.onerror=null; this.src=FALLBACK_BOOK_COVER;">
                    </div>
                </div>

                <!-- Info & Borrow Form (8 cols) -->
                <div class="md:col-span-8 lg:col-span-9 space-y-5">
                    <div>
                        <div class="flex flex-wrap items-center gap-2 mb-2">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-semibold bg-[#EEF2FF] text-[#4F46E5]">
                                {{ $book->category->name ?? 'Kategori Umum' }}
                            </span>
                            @if($book->is_available && $book->available_stock > 0)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-semibold bg-[#F0FDF4] text-[#16A34A]">
                                    Tersedia ({{ $book->available_stock }} dari {{ $book->stock }} eksemplar)
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-semibold bg-[#FEECEC] text-[#DC2626]">
                                    Stok Habis / Sedang Dipinjam
                                </span>
                            @endif
                        </div>
                        <h1 class="text-xl sm:text-2xl font-bold text-zinc-900 tracking-tight">{{ $book->title }}</h1>
                        <p class="text-xs font-medium text-zinc-500 mt-1">Penulis: {{ $book->author }} • Penerbit: {{ $book->publisher }} ({{ $book->publication_year }})</p>
                    </div>

                    <!-- Metadata Grid -->
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 p-3.5 rounded-xl bg-zinc-50/70 border border-zinc-200/80 text-xs">
                        <div>
                            <span class="text-zinc-400 block font-medium text-[10px]">Nomor ISBN</span>
                            <span class="font-mono font-semibold text-zinc-800 text-xs">{{ $book->isbn }}</span>
                        </div>
                        <div>
                            <span class="text-zinc-400 block font-medium text-[10px]">Tahun Terbit</span>
                            <span class="font-semibold text-zinc-800 text-xs">{{ $book->publication_year }}</span>
                        </div>
                        <div>
                            <span class="text-zinc-400 block font-medium text-[10px]">Lokasi Rak</span>
                            <span class="font-mono font-semibold text-zinc-800 text-xs flex items-center space-x-1">
                                <i data-lucide="map-pin" class="w-3 h-3 text-brand-orange"></i>
                                <span>{{ $book->shelf_location }}</span>
                            </span>
                        </div>
                    </div>

                    <!-- Synopsis -->
                    <div class="space-y-1.5">
                        <h3 class="text-[11px] font-semibold text-zinc-700">Sinopsis & Ringkasan Buku</h3>
                        <p class="text-xs text-zinc-600 leading-relaxed whitespace-pre-line">
                            {{ $book->description ?: 'Belum ada ringkasan yang tersedia untuk buku ini.' }}
                        </p>
                    </div>

                    <!-- Borrowing Action Form -->
                    <div class="pt-4 border-t border-zinc-100">
                        @if($book->is_available && $book->available_stock > 0)
                            <div class="p-4 rounded-xl bg-zinc-50/70 border border-zinc-200/80 space-y-3">
                                <div>
                                    <h4 class="font-bold text-xs text-zinc-900">Ajukan Peminjaman Buku Ini</h4>
                                    <p class="text-[11px] text-zinc-500 mt-0.5">Permohonan akan diverifikasi oleh staf perpustakaan. Maksimal pinjam 14 hari.</p>
                                </div>

                                <form method="POST" action="{{ route('user.borrowings.store') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-2.5 items-end">
                                    @csrf
                                    <input type="hidden" name="book_id" value="{{ $book->id }}">

                                    <div class="sm:col-span-4">
                                        <label for="duration_days" class="block text-[10px] font-semibold text-zinc-700 mb-1">
                                            Durasi Peminjaman
                                        </label>
                                        <select
                                            id="duration_days"
                                            name="duration_days"
                                            required
                                            class="w-full px-2.5 py-1.5 text-xs bg-white border border-zinc-200/80 rounded-xl focus:outline-none focus:ring-1 focus:ring-zinc-400 text-zinc-800"
                                        >
                                            <option value="3">3 Hari</option>
                                            <option value="7" selected>7 Hari (Standar)</option>
                                            <option value="10">10 Hari</option>
                                            <option value="14">14 Hari (Maksimal)</option>
                                        </select>
                                    </div>

                                    <div class="sm:col-span-5">
                                        <label for="notes" class="block text-[10px] font-semibold text-zinc-700 mb-1">
                                            Catatan Keperluan
                                        </label>
                                        <input
                                            type="text"
                                            id="notes"
                                            name="notes"
                                            placeholder="Contoh: Keperluan tugas akhir / skripsi"
                                            class="w-full px-2.5 py-1.5 text-xs bg-white border border-zinc-200/80 rounded-xl focus:outline-none focus:ring-1 focus:ring-zinc-400 text-zinc-900"
                                        >
                                    </div>

                                    <div class="sm:col-span-3">
                                        <button
                                            type="submit"
                                            class="w-full py-1.5 px-3 bg-zinc-950 hover:bg-zinc-800 text-white text-xs font-semibold rounded-xl shadow-2xs transition-all flex items-center justify-center space-x-1.5 cursor-pointer"
                                        >
                                            <i data-lucide="book-open" class="w-3.5 h-3.5"></i>
                                            <span>Ajukan Pinjam</span>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        @else
                            <div class="p-3.5 rounded-xl bg-[#FEECEC] border border-red-200 text-red-700 text-xs flex items-center space-x-2">
                                <i data-lucide="alert-octagon" class="w-4 h-4 text-red-500 flex-shrink-0"></i>
                                <span>Maaf, semua eksemplar buku ini sedang dipinjam oleh anggota lain.</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Reviews & Ratings Section -->
        <div id="review-section" class="bg-white rounded-2xl border border-zinc-200/80 shadow-2xs p-5 sm:p-7 space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-zinc-100 gap-3">
                <div>
                    <h3 class="font-bold text-base text-zinc-900 flex items-center gap-2">
                        <span class="text-amber-500">★</span>
                        <span>Ulasan & Rating Pembaca</span>
                    </h3>
                    <p class="text-xs text-zinc-400 mt-0.5">Pendapat dan evaluasi dari anggota yang telah membaca buku ini.</p>
                </div>
                <div class="flex items-center space-x-3 bg-zinc-50 px-4 py-2 rounded-2xl border border-zinc-200/70">
                    <span class="text-2xl font-bold text-zinc-900">{{ $book->average_rating }}</span>
                    <div class="text-xs">
                        <div class="flex text-amber-400 text-xs">
                            @for($i = 1; $i <= 5; $i++)
                                <span>{{ $i <= round($book->average_rating) ? '★' : '☆' }}</span>
                            @endfor
                        </div>
                        <span class="text-[10px] text-zinc-400">{{ $book->reviews_count }} Ulasan</span>
                    </div>
                </div>
            </div>

            <!-- Review Form for eligible users -->
            @if($canReview)
                <div class="p-4 rounded-2xl bg-zinc-50 border border-zinc-200/80 space-y-3">
                    <h4 class="font-bold text-xs text-zinc-900 flex items-center gap-1.5">
                        <i data-lucide="edit-3" class="w-3.5 h-3.5 text-orange-600"></i>
                        <span>{{ $userReview ? 'Perbarui Ulasan Anda' : 'Tulis Ulasan & Beri Rating' }}</span>
                    </h4>

                    <form method="POST" action="{{ route('user.reviews.store', $book) }}" class="space-y-3">
                        @csrf
                        <div>
                            <label class="block text-[11px] font-semibold text-zinc-700 mb-1">Pilih Bintang Rating</label>
                            <div class="flex items-center space-x-2">
                                <select name="rating" required class="px-3 py-1.5 text-xs bg-white border border-zinc-300 rounded-xl focus:outline-none focus:ring-1 focus:ring-orange-500 text-zinc-800 font-semibold">
                                    <option value="5" {{ ($userReview?->rating ?? 5) == 5 ? 'selected' : '' }}>★★★★★ (5 - Sangat Direkomendasikan)</option>
                                    <option value="4" {{ ($userReview?->rating ?? 5) == 4 ? 'selected' : '' }}>★★★★☆ (4 - Bagus & Bermanfaat)</option>
                                    <option value="3" {{ ($userReview?->rating ?? 5) == 3 ? 'selected' : '' }}>★★★☆☆ (3 - Cukup Baik)</option>
                                    <option value="2" {{ ($userReview?->rating ?? 5) == 2 ? 'selected' : '' }}>★★☆☆☆ (2 - Kurang Sesuai)</option>
                                    <option value="1" {{ ($userReview?->rating ?? 5) == 1 ? 'selected' : '' }}>★☆☆☆☆ (1 - Tidak Direkomendasikan)</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-[11px] font-semibold text-zinc-700 mb-1">Komentar / Pengalaman Membaca</label>
                            <textarea
                                name="comment"
                                rows="3"
                                required
                                placeholder="Bagikan kesan atau wawasan yang Anda peroleh setelah membaca buku ini..."
                                class="w-full px-3 py-2 text-xs bg-white border border-zinc-300 rounded-xl focus:outline-none focus:ring-1 focus:ring-orange-500 text-zinc-900 placeholder-zinc-400"
                            >{{ old('comment', $userReview?->comment) }}</textarea>
                            @error('comment')
                                <p class="text-[10px] text-rose-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit" class="px-4 py-2 bg-zinc-950 hover:bg-black text-white text-xs font-bold rounded-xl shadow-xs transition flex items-center gap-1.5 cursor-pointer">
                            <i data-lucide="send" class="w-3.5 h-3.5"></i>
                            <span>{{ $userReview ? 'Simpan Perubahan Ulasan' : 'Kirim Ulasan' }}</span>
                        </button>
                    </form>
                </div>
            @endif

            <!-- Review Items List -->
            <div class="space-y-4">
                @forelse($book->reviews as $rev)
                    <div class="p-4 rounded-xl border border-zinc-100 bg-white hover:bg-zinc-50/50 transition-colors">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center space-x-2.5">
                                <div class="w-7 h-7 rounded-full bg-zinc-900 text-white font-bold flex items-center justify-center text-[11px]">
                                    {{ strtoupper(substr($rev->user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-bold text-xs text-zinc-900">{{ $rev->user->name }}</p>
                                    <p class="text-[10px] text-zinc-400">{{ $rev->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            <div class="flex text-amber-400 text-xs">
                                @for($i = 1; $i <= 5; $i++)
                                    <span>{{ $i <= $rev->rating ? '★' : '☆' }}</span>
                                @endfor
                            </div>
                        </div>
                        <p class="text-xs text-zinc-600 leading-relaxed pl-9">
                            {{ $rev->comment }}
                        </p>
                    </div>
                @empty
                    <div class="py-8 text-center text-zinc-400 bg-zinc-50/50 rounded-xl border border-dashed border-zinc-200">
                        <i data-lucide="message-square" class="w-6 h-6 mx-auto mb-1.5 text-zinc-300"></i>
                        <p class="text-xs font-semibold text-zinc-700">Belum ada ulasan untuk buku ini</p>
                        <p class="text-[11px] text-zinc-400 mt-0.5">Jadilah anggota pertama yang meminjam dan mengulas buku ini!</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Related Books in Same Category -->
        @if($relatedBooks->count() > 0)
            <div class="bg-white rounded-2xl border border-zinc-200/80 shadow-2xs p-5 space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-zinc-100">
                    <div>
                        <h3 class="font-bold text-sm text-zinc-900">Buku Lainnya dalam Kategori Ini</h3>
                        <p class="text-xs text-zinc-400 mt-0.5">Koleksi terkait dalam bidang {{ $book->category->name ?? 'yang sama' }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3.5">
                    @foreach($relatedBooks as $rb)
                        <a href="{{ route('user.catalog.show', $rb->slug) }}" class="group block rounded-xl border border-zinc-200/80 p-2.5 hover:border-zinc-300 transition-all">
                            <div class="aspect-[3/4] rounded-lg overflow-hidden bg-zinc-100 mb-2 border border-zinc-200/60">
                                <img src="{{ $rb->cover_url }}" alt="{{ $rb->title }}" class="w-full h-full object-cover group-hover:scale-102 transition-transform duration-200" onerror="this.onerror=null; this.src=FALLBACK_BOOK_COVER;">
                            </div>
                            <h4 class="font-semibold text-xs text-zinc-900 line-clamp-1 group-hover:text-brand-orange transition-colors">{{ $rb->title }}</h4>
                            <p class="text-[10px] text-zinc-400 line-clamp-1 mt-0.5">{{ $rb->author }}</p>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</x-layouts.app>
