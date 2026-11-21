<x-layouts.app title="Katalog Buku">
    <x-slot:pageTitle>Katalog Buku Perpustakaan</x-slot:pageTitle>

    <!-- Subheader Toolbar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-2 border-b border-zinc-200/70">
        <div class="flex items-center space-x-2 text-xs text-zinc-600 font-medium">
            <div class="flex items-center space-x-1.5 px-3 py-1.5 bg-white border border-zinc-200/80 rounded-xl shadow-2xs">
                <i data-lucide="book" class="w-3.5 h-3.5 text-zinc-500"></i>
                <span class="font-semibold text-zinc-800">Katalog Perpustakaan</span>
            </div>
            <span class="text-xs text-zinc-400">Total {{ $books->total() }} judul buku</span>
        </div>
    </div>

    <!-- Search & Filter Controls -->
    <div class="bg-white rounded-2xl border border-zinc-200/80 p-3 sm:p-4 shadow-2xs">
        <form method="GET" action="{{ route('user.catalog.index') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-2.5">
            <!-- Search Keyword -->
            <div class="relative lg:col-span-2">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-zinc-400">
                    <i data-lucide="search" class="w-3.5 h-3.5"></i>
                </div>
                <input
                    type="text"
                    name="search"
                    value="{{ $search }}"
                    placeholder="Ketik judul buku, pengarang, atau ISBN..."
                    class="w-full pl-8.5 pr-4 py-1.5 text-xs bg-zinc-50/70 border border-zinc-200/80 rounded-xl focus:outline-none focus:ring-1 focus:ring-zinc-400 focus:bg-white text-zinc-900 placeholder-zinc-400"
                >
            </div>

            <!-- Category Filter -->
            <div>
                <select name="category_id" class="w-full px-3 py-1.5 text-xs bg-zinc-50/70 border border-zinc-200/80 rounded-xl focus:outline-none focus:ring-1 focus:ring-zinc-400 focus:bg-white text-zinc-700">
                    <option value="all">Semua Kategori</option>
                    @foreach($categories as $c)
                        <option value="{{ $c->id }}" {{ $categoryId == $c->id ? 'selected' : '' }}>
                            {{ $c->name }} ({{ $c->books_count }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Availability -->
            <div>
                <select name="availability" class="w-full px-3 py-1.5 text-xs bg-zinc-50/70 border border-zinc-200/80 rounded-xl focus:outline-none focus:ring-1 focus:ring-zinc-400 focus:bg-white text-zinc-700">
                    <option value="all">Semua Ketersediaan</option>
                    <option value="available" {{ $availability === 'available' ? 'selected' : '' }}>Hanya yang Tersedia</option>
                </select>
            </div>

            <!-- Buttons -->
            <div class="flex items-center space-x-2">
                <button type="submit" class="flex-1 py-1.5 bg-zinc-950 hover:bg-zinc-800 text-white text-xs font-semibold rounded-xl transition-colors shadow-2xs cursor-pointer">
                    Cari Buku
                </button>
                @if($search || ($categoryId && $categoryId !== 'all') || ($availability && $availability !== 'all'))
                    <a href="{{ route('user.catalog.index') }}" class="p-1.5 bg-zinc-100 hover:bg-zinc-200 text-zinc-600 rounded-xl transition-colors" title="Reset Filter">
                        <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Category Pills Quick Filter -->
    <div class="flex items-center space-x-1.5 overflow-x-auto pb-1 text-xs">
        <a href="{{ route('user.catalog.index', array_merge(request()->except('category_id'), ['category_id' => 'all'])) }}" class="px-3 py-1 rounded-lg text-xs font-semibold transition-all {{ empty($categoryId) || $categoryId === 'all' ? 'bg-zinc-950 text-white' : 'bg-white border border-zinc-200/80 text-zinc-600 hover:bg-zinc-50' }}">
            Semua ({{ $books->total() }})
        </a>
        @foreach($categories as $cat)
            <a href="{{ route('user.catalog.index', array_merge(request()->except('category_id'), ['category_id' => $cat->id])) }}" class="px-3 py-1 rounded-lg text-xs font-semibold transition-all whitespace-nowrap {{ $categoryId == $cat->id ? 'bg-zinc-950 text-white' : 'bg-white border border-zinc-200/80 text-zinc-600 hover:bg-zinc-50' }}">
                {{ $cat->name }}
            </a>
        @endforeach
    </div>

    <!-- Book Grid Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3.5 sm:gap-4.5">
        @forelse($books as $book)
            <div class="bg-white rounded-2xl border border-zinc-200/80 p-3.5 shadow-2xs hover:border-zinc-300 transition-all flex flex-col justify-between group">
                <div class="space-y-2.5">
                    <!-- Cover Aspect Ratio 3:4 -->
                    <div class="aspect-[3/4] rounded-xl overflow-hidden bg-zinc-100 border border-zinc-200/70 relative">
                        <img
                            src="{{ $book->cover_url }}"
                            alt="{{ $book->title }}"
                            class="w-full h-full object-cover group-hover:scale-102 transition-transform duration-200"
                            onerror="this.onerror=null; this.src=FALLBACK_BOOK_COVER;"
                        >
                        <!-- Stock Status Floating Badge -->
                        @if($book->is_available && $book->available_stock > 0)
                            <span class="absolute top-2 right-2 px-2 py-0.5 rounded-md text-[10px] font-semibold bg-[#F0FDF4] text-[#16A34A] shadow-xs">
                                Tersedia: {{ $book->available_stock }}
                            </span>
                        @else
                            <span class="absolute top-2 right-2 px-2 py-0.5 rounded-md text-[10px] font-semibold bg-[#FEECEC] text-[#DC2626] shadow-xs">
                                Stok Habis
                            </span>
                        @endif

                        <span class="absolute bottom-2 left-2 px-1.5 py-0.2 rounded text-[9px] font-mono font-medium bg-zinc-950/80 text-white backdrop-blur-xs">
                            {{ $book->shelf_location }}
                        </span>
                    </div>

                    <!-- Book Meta -->
                    <div>
                        <div class="flex items-center justify-between">
                            <span class="text-[10px] font-semibold text-brand-orange uppercase tracking-tight">
                                {{ $book->category->name ?? 'Umum' }}
                            </span>
                            <span class="text-[10px] font-medium text-amber-500 flex items-center space-x-0.5">
                                <span>★</span> <span>5.0</span>
                            </span>
                        </div>
                        <h3 class="font-semibold text-xs text-zinc-900 line-clamp-2 mt-0.5 group-hover:text-brand-orange transition-colors" title="{{ $book->title }}">
                            {{ $book->title }}
                        </h3>
                        <p class="text-[11px] text-zinc-400 line-clamp-1 mt-0.5">{{ $book->author }} ({{ $book->publication_year }})</p>
                    </div>
                </div>

                <!-- Action Button -->
                <div class="pt-3 border-t border-zinc-100 flex items-center space-x-2">
                    <a
                        href="{{ route('user.catalog.show', $book->slug) }}"
                        class="flex-1 py-1.5 bg-zinc-50 hover:bg-zinc-100 border border-zinc-200/80 text-zinc-700 text-xs font-semibold rounded-xl text-center transition-colors"
                    >
                        Rincian
                    </a>
                    @if($book->is_available && $book->available_stock > 0)
                        <button
                            type="button"
                            onclick="openBorrowModal({{ $book->id }}, '{{ addslashes($book->title) }}', '{{ $book->available_stock }}')"
                            class="py-1.5 px-3 bg-zinc-950 hover:bg-zinc-800 text-white text-xs font-semibold rounded-xl shadow-2xs transition-colors flex items-center justify-center space-x-1 cursor-pointer"
                            title="Ajukan Peminjaman"
                        >
                            <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                            <span class="hidden sm:inline">Pinjam</span>
                        </button>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-full py-16 text-center text-zinc-400 bg-white rounded-2xl border border-dashed border-zinc-200">
                <i data-lucide="book-x" class="w-8 h-8 mx-auto mb-2 text-zinc-300"></i>
                <h3 class="font-semibold text-sm text-zinc-700">Tidak ada buku yang sesuai dengan pencarian</h3>
                <p class="text-xs text-zinc-400 mt-1">Coba gunakan kata kunci lain atau bersihkan filter.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($books->hasPages())
        <div class="bg-white rounded-2xl p-3.5 border border-zinc-200/80">
            {{ $books->links() }}
        </div>
    @endif

    <!-- Quick Borrow Modal -->
    <div id="quick-borrow-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-zinc-900/40 backdrop-blur-xs hidden">
        <div class="bg-white rounded-2xl border border-zinc-200 shadow-xl max-w-md w-full p-5 space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-zinc-100">
                <div class="flex items-center space-x-2">
                    <div class="w-7 h-7 rounded-lg bg-zinc-100 text-zinc-800 flex items-center justify-center">
                        <i data-lucide="book-open" class="w-4 h-4"></i>
                    </div>
                    <h3 class="font-bold text-sm text-zinc-900">Ajukan Peminjaman Buku</h3>
                </div>
                <button type="button" onclick="closeBorrowModal()" class="text-zinc-400 hover:text-zinc-600 p-1 cursor-pointer">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>

            <div>
                <p class="text-xs text-zinc-400">Judul Buku:</p>
                <p id="modal-book-title" class="text-xs font-bold text-zinc-900 mt-0.5"></p>
                <p class="text-[11px] text-[#16A34A] font-semibold mt-0.5">Stok tersedia: <span id="modal-book-stock"></span> eksemplar</p>
            </div>

            <form method="POST" action="{{ route('user.borrowings.store') }}" class="space-y-3">
                @csrf
                <input type="hidden" id="modal-book-id" name="book_id" value="">

                <div>
                    <label for="duration_days" class="block text-[11px] font-semibold text-zinc-700 mb-1">
                        Durasi Peminjaman <span class="text-red-500">*</span>
                    </label>
                    <select
                        id="duration_days"
                        name="duration_days"
                        required
                        class="w-full px-3 py-1.5 text-xs bg-zinc-50/70 border border-zinc-200/80 rounded-xl focus:outline-none focus:ring-1 focus:ring-zinc-400 focus:bg-white text-zinc-800"
                    >
                        <option value="3">3 Hari</option>
                        <option value="7" selected>7 Hari (Standar Perpustakaan)</option>
                        <option value="10">10 Hari</option>
                        <option value="14">14 Hari (Maksimal)</option>
                    </select>
                </div>

                <div>
                    <label for="notes" class="block text-[11px] font-semibold text-zinc-700 mb-1">
                        Catatan Keperluan (Opsional)
                    </label>
                    <textarea
                        id="notes"
                        name="notes"
                        rows="2"
                        placeholder="Contoh: Untuk referensi tugas akhir atau praktikum mandiri..."
                        class="w-full px-3 py-1.5 text-xs bg-zinc-50/70 border border-zinc-200/80 rounded-xl focus:outline-none focus:ring-1 focus:ring-zinc-400 focus:bg-white text-zinc-900 resize-none"
                    ></textarea>
                </div>

                <div class="pt-2 flex items-center justify-end space-x-2">
                    <button type="button" onclick="closeBorrowModal()" class="px-3.5 py-1.5 text-xs font-semibold text-zinc-600 bg-zinc-100 hover:bg-zinc-200 rounded-xl transition-colors cursor-pointer">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-1.5 bg-zinc-950 hover:bg-zinc-800 text-white text-xs font-semibold rounded-xl shadow-2xs transition-colors cursor-pointer">
                        Kirim Permohonan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- JavaScript for Quick Borrow Modal -->
    <script>
        function openBorrowModal(bookId, title, stock) {
            document.getElementById('modal-book-id').value = bookId;
            document.getElementById('modal-book-title').innerText = title;
            document.getElementById('modal-book-stock').innerText = stock;
            document.getElementById('quick-borrow-modal').classList.remove('hidden');
        }

        function closeBorrowModal() {
            document.getElementById('quick-borrow-modal').classList.add('hidden');
        }
    </script>
</x-layouts.app>
