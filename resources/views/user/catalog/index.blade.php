<x-layouts.app title="Katalog Buku">
    <x-slot:header>Katalog Buku Perpustakaan</x-slot:header>
    <x-slot:subheader>Jelajahi ratusan judul buku, karya sastra, sains, teknologi, dan referensi akademik</x-slot:subheader>

    <!-- Search & Filter Controls -->
    <div class="bg-white rounded-3xl border border-slate-200/80 p-5 shadow-xs space-y-4">
        <form method="GET" action="{{ route('user.catalog.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
            <!-- Search Keyword -->
            <div class="relative lg:col-span-2">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <i data-lucide="search" class="w-4 h-4"></i>
                </div>
                <input
                    type="text"
                    name="search"
                    value="{{ $search }}"
                    placeholder="Ketik judul buku, pengarang, atau ISBN..."
                    class="w-full pl-9 pr-4 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-slate-900 placeholder:text-slate-400"
                >
            </div>

            <!-- Category Filter -->
            <div>
                <select name="category_id" class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-slate-700">
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
                <select name="availability" class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-slate-700">
                    <option value="all">Semua Status Koleksi</option>
                    <option value="available" {{ $availability === 'available' ? 'selected' : '' }}>Hanya yang Tersedia</option>
                </select>
            </div>

            <!-- Buttons -->
            <div class="flex items-center space-x-2">
                <button type="submit" class="flex-1 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl transition-colors">
                    Cari Koleksi
                </button>
                @if($search || ($categoryId && $categoryId !== 'all') || ($availability && $availability !== 'all'))
                    <a href="{{ route('user.catalog.index') }}" class="p-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl transition-colors" title="Reset Filter">
                        <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Category Pills Quick Filter -->
    <div class="flex items-center space-x-2 overflow-x-auto pb-1 text-xs">
        <a href="{{ route('user.catalog.index', array_merge(request()->except('category_id'), ['category_id' => 'all'])) }}" class="px-3.5 py-1.5 rounded-full font-bold transition-all {{ empty($categoryId) || $categoryId === 'all' ? 'bg-slate-900 text-white' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-100' }}">
            Semua ({{ $books->total() }})
        </a>
        @foreach($categories as $cat)
            <a href="{{ route('user.catalog.index', array_merge(request()->except('category_id'), ['category_id' => $cat->id])) }}" class="px-3.5 py-1.5 rounded-full font-semibold transition-all whitespace-nowrap {{ $categoryId == $cat->id ? 'bg-blue-600 text-white font-bold shadow-xs' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-100' }}">
                {{ $cat->name }}
            </a>
        @endforeach
    </div>

    <!-- Book Grid Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
        @forelse($books as $book)
            <div class="bg-white rounded-3xl border border-slate-200/80 p-4 shadow-xs hover:shadow-md hover:border-blue-500/40 transition-all flex flex-col justify-between group">
                <div class="space-y-3">
                    <!-- Cover Aspect Ratio 3:4 -->
                    <div class="aspect-[3/4] rounded-2xl overflow-hidden bg-slate-100 border border-slate-200 relative">
                        <img
                            src="{{ $book->cover_url }}"
                            alt="{{ $book->title }}"
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                        >
                        <!-- Stock Status Floating Badge -->
                        @if($book->is_available && $book->available_stock > 0)
                            <span class="absolute top-2.5 right-2.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-600 text-white shadow-md">
                                Tersedia: {{ $book->available_stock }}
                            </span>
                        @else
                            <span class="absolute top-2.5 right-2.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-600 text-white shadow-md">
                                Stok Habis
                            </span>
                        @endif

                        <span class="absolute bottom-2.5 left-2.5 px-2 py-0.5 rounded-md text-[9px] font-mono font-bold bg-slate-900/80 text-white backdrop-blur-xs">
                            {{ $book->shelf_location }}
                        </span>
                    </div>

                    <!-- Book Meta -->
                    <div>
                        <span class="text-[10px] font-bold text-blue-600 uppercase tracking-wider block">
                            {{ $book->category->name ?? 'Umum' }}
                        </span>
                        <h3 class="font-extrabold text-sm text-slate-900 line-clamp-2 mt-0.5 group-hover:text-blue-600 transition-colors" title="{{ $book->title }}">
                            {{ $book->title }}
                        </h3>
                        <p class="text-xs text-slate-500 line-clamp-1 mt-0.5">{{ $book->author }} ({{ $book->publication_year }})</p>
                    </div>
                </div>

                <!-- Action Button -->
                <div class="pt-4 border-t border-slate-100 flex items-center space-x-2">
                    <a
                        href="{{ route('user.catalog.show', $book->slug) }}"
                        class="flex-1 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl text-center transition-colors"
                    >
                        Rincian
                    </a>
                    @if($book->is_available && $book->available_stock > 0)
                        <button
                            type="button"
                            onclick="openBorrowModal({{ $book->id }}, '{{ addslashes($book->title) }}', '{{ $book->available_stock }}')"
                            class="py-2 px-3 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-xs transition-colors flex items-center justify-center space-x-1"
                            title="Ajukan Pinjam Sekarang"
                        >
                            <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                            <span class="hidden sm:inline">Pinjam</span>
                        </button>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-full py-16 text-center text-slate-400 bg-white rounded-3xl border border-dashed border-slate-200">
                <i data-lucide="book-x" class="w-10 h-10 mx-auto mb-3 text-slate-300"></i>
                <h3 class="font-extrabold text-base text-slate-700">Tidak ada buku yang cocok dengan pencarian</h3>
                <p class="text-xs text-slate-400 mt-1">Coba gunakan kata kunci lain atau bersihkan filter pencarian.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($books->hasPages())
        <div class="bg-white rounded-3xl p-4 border border-slate-200/80">
            {{ $books->links() }}
        </div>
    @endif

    <!-- Quick Borrow Modal -->
    <div id="quick-borrow-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-xs hidden">
        <div class="bg-white rounded-3xl border border-slate-200 shadow-xl max-w-md w-full p-6 space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div class="flex items-center space-x-2">
                    <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                        <i data-lucide="book-open" class="w-4 h-4"></i>
                    </div>
                    <h3 class="font-extrabold text-base text-slate-900">Ajukan Peminjaman Buku</h3>
                </div>
                <button type="button" onclick="closeBorrowModal()" class="text-slate-400 hover:text-slate-600 p-1">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <div>
                <p class="text-xs text-slate-500">Judul Buku:</p>
                <p id="modal-book-title" class="text-sm font-bold text-slate-900 mt-0.5"></p>
                <p class="text-[11px] text-emerald-600 font-semibold mt-0.5">Stok tersedia: <span id="modal-book-stock"></span> eksemplar</p>
            </div>

            <form method="POST" action="{{ route('user.borrowings.store') }}" class="space-y-3.5">
                @csrf
                <input type="hidden" id="modal-book-id" name="book_id" value="">

                <div>
                    <label for="duration_days" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                        Durasi Peminjaman <span class="text-rose-500">*</span>
                    </label>
                    <select
                        id="duration_days"
                        name="duration_days"
                        required
                        class="w-full px-3 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-slate-800"
                    >
                        <option value="3">3 Hari</option>
                        <option value="7" selected>7 Hari (Standar Perpustakaan)</option>
                        <option value="10">10 Hari</option>
                        <option value="14">14 Hari (Maksimal)</option>
                    </select>
                </div>

                <div>
                    <label for="notes" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                        Catatan Keperluan (Opsional)
                    </label>
                    <textarea
                        id="notes"
                        name="notes"
                        rows="2"
                        placeholder="Contoh: Untuk referensi tugas akhir atau praktikum mandiri..."
                        class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-slate-900 resize-none"
                    ></textarea>
                </div>

                <div class="pt-3 flex items-center justify-end space-x-2">
                    <button type="button" onclick="closeBorrowModal()" class="px-4 py-2 text-xs font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-md transition-colors">
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
