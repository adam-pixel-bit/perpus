<x-layouts.app title="Tambah Buku Baru">
    <div class="max-w-3xl mx-auto space-y-4">
        <!-- Subheader Toolbar -->
        <div class="flex items-center justify-between pb-2 border-b border-zinc-200/70">
            <div>
                <h3 class="font-bold text-sm text-zinc-900">Add New Book to Collection</h3>
                <p class="text-xs text-zinc-400 mt-0.5">Enter bibliographic and catalog inventory details</p>
            </div>
            <a href="{{ route('admin.books.index') }}" class="inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold text-zinc-600 hover:text-zinc-900 bg-white border border-zinc-200/80 shadow-2xs transition-colors">
                <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                <span>Back</span>
            </a>
        </div>

        <div class="bg-white rounded-2xl border border-zinc-200/80 shadow-2xs p-5 sm:p-6">
            <form method="POST" action="{{ route('admin.books.store') }}" enctype="multipart/form-data" class="space-y-5">
                @csrf

                <!-- Basic Info -->
                <div class="space-y-3.5">
                    <h4 class="text-xs font-semibold text-zinc-900 flex items-center space-x-1.5">
                        <i data-lucide="book" class="w-3.5 h-3.5 text-zinc-500"></i>
                        <span>Basic Information</span>
                    </h4>

                    <!-- Title -->
                    <div>
                        <label for="title" class="block text-[11px] font-semibold text-zinc-700 mb-1">
                            Book Title <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="title"
                            name="title"
                            value="{{ old('title') }}"
                            required
                            placeholder="e.g. Clean Architecture: A Craftsman's Guide"
                            class="w-full px-3 py-1.5 text-xs bg-zinc-50/70 border border-zinc-200/80 rounded-xl focus:outline-none focus:ring-1 focus:ring-zinc-400 focus:bg-white text-zinc-900"
                        >
                        @error('title')
                            <p class="text-[11px] text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Category & ISBN -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                        <div>
                            <label for="category_id" class="block text-[11px] font-semibold text-zinc-700 mb-1">
                                Category <span class="text-red-500">*</span>
                            </label>
                            <select
                                id="category_id"
                                name="category_id"
                                required
                                class="w-full px-3 py-1.5 text-xs bg-zinc-50/70 border border-zinc-200/80 rounded-xl focus:outline-none focus:ring-1 focus:ring-zinc-400 focus:bg-white text-zinc-800"
                            >
                                <option value="" disabled selected>Select Category</option>
                                @foreach($categories as $c)
                                    <option value="{{ $c->id }}" {{ old('category_id') == $c->id ? 'selected' : '' }}>
                                        {{ $c->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <p class="text-[11px] text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="isbn" class="block text-[11px] font-semibold text-zinc-700 mb-1">
                                ISBN Code <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="text"
                                id="isbn"
                                name="isbn"
                                value="{{ old('isbn') }}"
                                required
                                placeholder="978-0134494166"
                                class="w-full px-3 py-1.5 text-xs bg-zinc-50/70 border border-zinc-200/80 rounded-xl focus:outline-none focus:ring-1 focus:ring-zinc-400 focus:bg-white font-mono text-zinc-900"
                            >
                            @error('isbn')
                                <p class="text-[11px] text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Author, Publisher, Year -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5">
                        <div>
                            <label for="author" class="block text-[11px] font-semibold text-zinc-700 mb-1">
                                Author <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="text"
                                id="author"
                                name="author"
                                value="{{ old('author') }}"
                                required
                                placeholder="Robert C. Martin"
                                class="w-full px-3 py-1.5 text-xs bg-zinc-50/70 border border-zinc-200/80 rounded-xl focus:outline-none focus:ring-1 focus:ring-zinc-400 focus:bg-white text-zinc-900"
                            >
                        </div>

                        <div>
                            <label for="publisher" class="block text-[11px] font-semibold text-zinc-700 mb-1">
                                Publisher <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="text"
                                id="publisher"
                                name="publisher"
                                value="{{ old('publisher') }}"
                                required
                                placeholder="Prentice Hall"
                                class="w-full px-3 py-1.5 text-xs bg-zinc-50/70 border border-zinc-200/80 rounded-xl focus:outline-none focus:ring-1 focus:ring-zinc-400 focus:bg-white text-zinc-900"
                            >
                        </div>

                        <div>
                            <label for="publication_year" class="block text-[11px] font-semibold text-zinc-700 mb-1">
                                Publication Year <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="number"
                                id="publication_year"
                                name="publication_year"
                                value="{{ old('publication_year', date('Y')) }}"
                                required
                                min="1800"
                                max="{{ date('Y') + 1 }}"
                                class="w-full px-3 py-1.5 text-xs bg-zinc-50/70 border border-zinc-200/80 rounded-xl focus:outline-none focus:ring-1 focus:ring-zinc-400 focus:bg-white text-zinc-900"
                            >
                        </div>
                    </div>
                </div>

                <!-- Inventory & Location Info -->
                <div class="space-y-3.5 pt-4 border-t border-zinc-100">
                    <h4 class="text-xs font-semibold text-zinc-900 flex items-center space-x-1.5">
                        <i data-lucide="package" class="w-3.5 h-3.5 text-zinc-500"></i>
                        <span>Inventory & Shelf Stacks</span>
                    </h4>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                        <div>
                            <label for="stock" class="block text-[11px] font-semibold text-zinc-700 mb-1">
                                Physical Stock Quantity <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="number"
                                id="stock"
                                name="stock"
                                value="{{ old('stock', 1) }}"
                                required
                                min="1"
                                class="w-full px-3 py-1.5 text-xs bg-zinc-50/70 border border-zinc-200/80 rounded-xl focus:outline-none focus:ring-1 focus:ring-zinc-400 focus:bg-white text-zinc-900"
                            >
                            <p class="text-[10px] text-zinc-400 mt-1">Available loan copies initially match this number.</p>
                        </div>

                        <div>
                            <label for="shelf_location" class="block text-[11px] font-semibold text-zinc-700 mb-1">
                                Shelf Stacks Location <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="text"
                                id="shelf_location"
                                name="shelf_location"
                                value="{{ old('shelf_location', 'Rak A-1') }}"
                                required
                                placeholder="e.g. Shelf TK-03, Row 2"
                                class="w-full px-3 py-1.5 text-xs bg-zinc-50/70 border border-zinc-200/80 rounded-xl focus:outline-none focus:ring-1 focus:ring-zinc-400 focus:bg-white text-zinc-900"
                            >
                        </div>
                    </div>
                </div>

                <!-- Cover Image & Description -->
                <div class="space-y-3.5 pt-4 border-t border-zinc-100">
                    <h4 class="text-xs font-semibold text-zinc-900 flex items-center space-x-1.5">
                        <i data-lucide="image" class="w-3.5 h-3.5 text-zinc-500"></i>
                        <span>Cover & Synopsis</span>
                    </h4>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                        <div>
                            <label for="cover_image" class="block text-[11px] font-semibold text-zinc-700 mb-1">
                                Upload Cover File (Optional)
                            </label>
                            <input
                                type="file"
                                id="cover_image"
                                name="cover_image"
                                accept="image/jpeg,image/png,image/webp"
                                class="w-full text-xs text-zinc-500 file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-zinc-100 file:text-zinc-700 hover:file:bg-zinc-200 cursor-pointer border border-zinc-200/80 rounded-xl bg-zinc-50/70"
                            >
                            <p class="text-[10px] text-zinc-400 mt-1">Formats: JPG, PNG, WEBP (Max 2MB)</p>
                        </div>

                        <div>
                            <label for="cover_image_url" class="block text-[11px] font-semibold text-zinc-700 mb-1">
                                Or Image URL
                            </label>
                            <input
                                type="url"
                                id="cover_image_url"
                                name="cover_image_url"
                                value="{{ old('cover_image_url') }}"
                                placeholder="https://images.unsplash.com/..."
                                class="w-full px-3 py-1.5 text-xs bg-zinc-50/70 border border-zinc-200/80 rounded-xl focus:outline-none focus:ring-1 focus:ring-zinc-400 focus:bg-white text-zinc-900"
                            >
                        </div>
                    </div>

                    <div>
                        <label for="description" class="block text-[11px] font-semibold text-zinc-700 mb-1">
                            Book Synopsis / Summary
                        </label>
                        <textarea
                            id="description"
                            name="description"
                            rows="4"
                            placeholder="Brief synopsis, intended audience, topics covered..."
                            class="w-full px-3 py-2 text-xs bg-zinc-50/70 border border-zinc-200/80 rounded-xl focus:outline-none focus:ring-1 focus:ring-zinc-400 focus:bg-white text-zinc-900 resize-none"
                        >{{ old('description') }}</textarea>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="pt-4 border-t border-zinc-100 flex items-center justify-end space-x-2">
                    <a href="{{ route('admin.books.index') }}" class="px-3.5 py-1.5 text-xs font-semibold text-zinc-600 hover:text-zinc-900 bg-zinc-100 hover:bg-zinc-200 rounded-xl transition-colors">
                        Cancel
                    </a>
                    <button
                        type="submit"
                        class="inline-flex items-center space-x-1.5 px-4 py-1.5 bg-zinc-950 hover:bg-zinc-800 text-white text-xs font-semibold rounded-xl shadow-2xs transition-all"
                    >
                        <i data-lucide="check" class="w-3.5 h-3.5"></i>
                        <span>Save Book</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
