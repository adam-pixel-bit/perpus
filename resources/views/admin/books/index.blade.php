<x-layouts.app title="Manajemen Buku">
    <x-slot:header>Manajemen Buku Perpustakaan</x-slot:header>
    <x-slot:subheader>Kelola katalog buku, stok fisik, nomor ISBN, dan lokasi penataan rak</x-slot:subheader>

    <!-- Top Action & Search Bar -->
    <div class="bg-white rounded-3xl border border-slate-200/80 p-5 shadow-xs space-y-4">
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3">
            <div>
                <h3 class="font-extrabold text-base text-slate-900">Daftar Koleksi Buku</h3>
                <p class="text-xs text-slate-500">Total {{ $books->total() }} buku terdaftar dalam basis data perpustakaan</p>
            </div>
            <a href="{{ route('admin.books.create') }}" class="inline-flex items-center justify-center space-x-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-md shadow-blue-600/20 transition-all">
                <i data-lucide="plus" class="w-4 h-4"></i>
                <span>Tambah Buku Baru</span>
            </a>
        </div>

        <!-- Filter & Search Form -->
        <form method="GET" action="{{ route('admin.books.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 pt-2 border-t border-slate-100">
            <!-- Search Keyword -->
            <div class="relative lg:col-span-2">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <i data-lucide="search" class="w-4 h-4"></i>
                </div>
                <input
                    type="text"
                    name="search"
                    value="{{ $search }}"
                    placeholder="Cari judul, ISBN, atau penulis..."
                    class="w-full pl-9 pr-4 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-slate-900 placeholder:text-slate-400"
                >
            </div>

            <!-- Category Filter -->
            <div>
                <select name="category_id" class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-slate-700">
                    <option value="all">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ $categoryId == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Availability Status -->
            <div>
                <select name="status" class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-slate-700">
                    <option value="all">Semua Ketersediaan</option>
                    <option value="available" {{ $status === 'available' ? 'selected' : '' }}>Tersedia untuk Dipinjam</option>
                    <option value="unavailable" {{ $status === 'unavailable' ? 'selected' : '' }}>Stok Habis / Tidak Aktif</option>
                </select>
            </div>

            <!-- Submit Filter & Reset -->
            <div class="flex items-center space-x-2">
                <button type="submit" class="flex-1 py-2 bg-slate-800 hover:bg-slate-900 text-white text-xs font-bold rounded-xl transition-colors">
                    Filter
                </button>
                @if($search || ($categoryId && $categoryId !== 'all') || ($status && $status !== 'all'))
                    <a href="{{ route('admin.books.index') }}" class="p-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl transition-colors" title="Reset Filter">
                        <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Books Data Table -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50/75 border-b border-slate-200 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                        <th class="py-3 px-4">Buku & ISBN</th>
                        <th class="py-3 px-4">Kategori</th>
                        <th class="py-3 px-4">Penulis & Penerbit</th>
                        <th class="py-3 px-4">Stok (Tersedia / Total)</th>
                        <th class="py-3 px-4">Lokasi Rak</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($books as $book)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <!-- Cover & Title -->
                            <td class="py-3 px-4">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-14 rounded-lg bg-slate-100 overflow-hidden flex-shrink-0 border border-slate-200 shadow-2xs">
                                        <img src="{{ $book->cover_url }}" alt="{{ $book->title }}" class="w-full h-full object-cover">
                                    </div>
                                    <div class="min-w-0 max-w-xs">
                                        <a href="{{ route('admin.books.show', $book->id) }}" class="font-bold text-slate-900 hover:text-blue-600 transition-colors block truncate">
                                            {{ $book->title }}
                                        </a>
                                        <span class="text-[11px] font-mono text-slate-500 block">ISBN: {{ $book->isbn }}</span>
                                        <span class="text-[10px] text-slate-400">Tahun: {{ $book->publication_year }}</span>
                                    </div>
                                </div>
                            </td>

                            <!-- Category -->
                            <td class="py-3 px-4">
                                <span class="px-2.5 py-1 rounded-full text-[11px] font-semibold bg-slate-100 text-slate-700">
                                    {{ $book->category->name ?? '-' }}
                                </span>
                            </td>

                            <!-- Author & Publisher -->
                            <td class="py-3 px-4">
                                <span class="font-semibold text-slate-800 block">{{ $book->author }}</span>
                                <span class="text-[11px] text-slate-400 block">{{ $book->publisher }}</span>
                            </td>

                            <!-- Stock -->
                            <td class="py-3 px-4">
                                <div class="flex items-center space-x-1.5">
                                    <span class="font-extrabold text-sm {{ $book->available_stock > 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                                        {{ $book->available_stock }}
                                    </span>
                                    <span class="text-slate-400">/</span>
                                    <span class="font-semibold text-slate-600">{{ $book->stock }} eks</span>
                                </div>
                            </td>

                            <!-- Shelf Location -->
                            <td class="py-3 px-4">
                                <span class="inline-flex items-center space-x-1 px-2 py-0.5 rounded-md bg-slate-50 border border-slate-200 font-mono text-slate-700 text-[11px]">
                                    <i data-lucide="map-pin" class="w-3 h-3 text-slate-400"></i>
                                    <span>{{ $book->shelf_location }}</span>
                                </span>
                            </td>

                            <!-- Availability Status -->
                            <td class="py-3 px-4">
                                @if($book->is_available && $book->available_stock > 0)
                                    <span class="inline-flex items-center space-x-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        <span>Tersedia</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center space-x-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                        <span>Tidak Tersedia</span>
                                    </span>
                                @endif
                            </td>

                            <!-- Actions -->
                            <td class="py-3 px-4 text-right">
                                <div class="inline-flex items-center space-x-1">
                                    <a href="{{ route('admin.books.show', $book->id) }}" class="p-1.5 text-slate-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Lihat Rincian">
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                    </a>
                                    <a href="{{ route('admin.books.edit', $book->id) }}" class="p-1.5 text-slate-500 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-colors" title="Edit Buku">
                                        <i data-lucide="edit-3" class="w-4 h-4"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.books.destroy', $book->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus buku \'{{ addslashes($book->title) }}\'?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-slate-500 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors" title="Hapus Buku">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-slate-400">
                                <i data-lucide="book-x" class="w-8 h-8 mx-auto mb-2 text-slate-300"></i>
                                <p class="font-bold text-slate-700">Tidak ada buku yang ditemukan</p>
                                <p class="text-xs text-slate-400 mt-1">Coba ubah kata kunci pencarian atau bersihkan filter.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($books->hasPages())
            <div class="p-4 border-t border-slate-200 bg-slate-50/50">
                {{ $books->links() }}
            </div>
        @endif
    </div>
</x-layouts.app>
