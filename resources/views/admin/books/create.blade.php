<x-layouts.app title="Tambah Buku Baru">
    <x-slot:header>Tambah Koleksi Buku Baru</x-slot:header>
    <x-slot:subheader>Masukkan rincian informasi bibliografi dan data inventaris buku</x-slot:subheader>

    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs p-6 sm:p-8">
            <div class="flex items-center justify-between pb-6 mb-6 border-b border-slate-100">
                <div>
                    <h3 class="font-extrabold text-lg text-slate-900">Formulir Pendaftaran Buku</h3>
                    <p class="text-xs text-slate-500">Seluruh data dengan tanda bintang (*) wajib diisi</p>
                </div>
                <a href="{{ route('admin.books.index') }}" class="inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold text-slate-600 hover:text-slate-900 bg-slate-100 hover:bg-slate-200 transition-colors">
                    <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                    <span>Kembali</span>
                </a>
            </div>

            <form method="POST" action="{{ route('admin.books.store') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <!-- Basic Info -->
                <div class="space-y-4">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-blue-600 flex items-center space-x-1.5">
                        <i data-lucide="info" class="w-4 h-4"></i>
                        <span>Informasi Pokok Buku</span>
                    </h4>

                    <!-- Title -->
                    <div>
                        <label for="title" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Judul Buku <span class="text-rose-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="title"
                            name="title"
                            value="{{ old('title') }}"
                            required
                            placeholder="e.g. Clean Architecture: A Craftsman's Guide"
                            class="w-full px-4 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-slate-900"
                        >
                        @error('title')
                            <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Category & ISBN -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="category_id" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                Kategori <span class="text-rose-500">*</span>
                            </label>
                            <select
                                id="category_id"
                                name="category_id"
                                required
                                class="w-full px-4 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-slate-800"
                            >
                                <option value="" disabled selected>Pilih Kategori Buku</option>
                                @foreach($categories as $c)
                                    <option value="{{ $c->id }}" {{ old('category_id') == $c->id ? 'selected' : '' }}>
                                        {{ $c->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="isbn" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                Nomor ISBN <span class="text-rose-500">*</span>
                            </label>
                            <input
                                type="text"
                                id="isbn"
                                name="isbn"
                                value="{{ old('isbn') }}"
                                required
                                placeholder="978-0134494166"
                                class="w-full px-4 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 font-mono text-slate-900"
                            >
                            @error('isbn')
                                <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Author, Publisher, Year -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label for="author" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                Penulis / Pengarang <span class="text-rose-500">*</span>
                            </label>
                            <input
                                type="text"
                                id="author"
                                name="author"
                                value="{{ old('author') }}"
                                required
                                placeholder="Robert C. Martin"
                                class="w-full px-4 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-slate-900"
                            >
                        </div>

                        <div>
                            <label for="publisher" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                Penerbit <span class="text-rose-500">*</span>
                            </label>
                            <input
                                type="text"
                                id="publisher"
                                name="publisher"
                                value="{{ old('publisher') }}"
                                required
                                placeholder="Prentice Hall"
                                class="w-full px-4 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-slate-900"
                            >
                        </div>

                        <div>
                            <label for="publication_year" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                Tahun Terbit <span class="text-rose-500">*</span>
                            </label>
                            <input
                                type="number"
                                id="publication_year"
                                name="publication_year"
                                value="{{ old('publication_year', date('Y')) }}"
                                required
                                min="1800"
                                max="{{ date('Y') + 1 }}"
                                class="w-full px-4 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-slate-900"
                            >
                        </div>
                    </div>
                </div>

                <!-- Inventory & Location Info -->
                <div class="space-y-4 pt-4 border-t border-slate-100">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-blue-600 flex items-center space-x-1.5">
                        <i data-lucide="package" class="w-4 h-4"></i>
                        <span>Inventaris & Lokasi Fisik</span>
                    </h4>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="stock" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                Jumlah Stok Fisik <span class="text-rose-500">*</span>
                            </label>
                            <input
                                type="number"
                                id="stock"
                                name="stock"
                                value="{{ old('stock', 1) }}"
                                required
                                min="1"
                                class="w-full px-4 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-slate-900"
                            >
                            <p class="text-[11px] text-slate-400 mt-1">Stok yang tersedia untuk dipinjam otomatis mengikuti nilai ini.</p>
                        </div>

                        <div>
                            <label for="shelf_location" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                Lokasi Rak Penyimpanan <span class="text-rose-500">*</span>
                            </label>
                            <input
                                type="text"
                                id="shelf_location"
                                name="shelf_location"
                                value="{{ old('shelf_location', 'Rak A-1') }}"
                                required
                                placeholder="e.g. Rak TK-03, Baris 2"
                                class="w-full px-4 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-slate-900"
                            >
                        </div>
                    </div>
                </div>

                <!-- Cover Image & Description -->
                <div class="space-y-4 pt-4 border-t border-slate-100">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-blue-600 flex items-center space-x-1.5">
                        <i data-lucide="image" class="w-4 h-4"></i>
                        <span>Sampul & Sinopsis Buku</span>
                    </h4>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="cover_image" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                Unggah File Sampul (Opsional)
                            </label>
                            <input
                                type="file"
                                id="cover_image"
                                name="cover_image"
                                accept="image/jpeg,image/png,image/webp"
                                class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer border border-slate-200 rounded-xl bg-slate-50"
                            >
                            <p class="text-[10px] text-slate-400 mt-1">Format: JPG, PNG, WEBP (Maks 2MB)</p>
                        </div>

                        <div>
                            <label for="cover_image_url" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                Atau Gunakan URL Gambar Eksternal
                            </label>
                            <input
                                type="url"
                                id="cover_image_url"
                                name="cover_image_url"
                                value="{{ old('cover_image_url') }}"
                                placeholder="https://images.unsplash.com/..."
                                class="w-full px-4 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-slate-900"
                            >
                        </div>
                    </div>

                    <div>
                        <label for="description" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Sinopsis / Deskripsi Buku
                        </label>
                        <textarea
                            id="description"
                            name="description"
                            rows="4"
                            placeholder="Tuliskan gambaran ringkas isi buku, sasaran pembaca, atau catatan khusus..."
                            class="w-full px-4 py-3 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-slate-900 resize-none"
                        >{{ old('description') }}</textarea>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="pt-6 border-t border-slate-100 flex items-center justify-end space-x-3">
                    <a href="{{ route('admin.books.index') }}" class="px-5 py-2.5 text-xs font-bold text-slate-600 hover:text-slate-900 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors">
                        Batal
                    </a>
                    <button
                        type="submit"
                        class="inline-flex items-center space-x-2 px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-lg shadow-blue-600/30 transition-all"
                    >
                        <i data-lucide="save" class="w-4 h-4"></i>
                        <span>Simpan Buku ke Katalog</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
