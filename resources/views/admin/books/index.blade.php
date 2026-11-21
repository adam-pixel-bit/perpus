<x-layouts.app title="Katalog Buku — Manajemen Buku">
    <x-slot:pageTitle>Katalog Buku <span class="text-xs font-normal text-zinc-400 ml-1.5 hidden sm:inline">Manajemen Buku</span><span class="sr-only">Manajemen Buku</span></x-slot:pageTitle>

    <!-- Subheader Filter & Action Bar -->
    <div class="flex flex-col md:flex-row items-stretch md:items-center justify-between gap-3">
        <!-- Left Controls -->
        <div class="flex flex-wrap items-center gap-2.5">
            <!-- View Selector Dropdown -->
            <div class="relative">
                <button type="button" class="inline-flex items-center space-x-2 px-3 py-1.5 rounded-xl border border-zinc-200/90 bg-white hover:bg-zinc-50 text-xs font-semibold text-zinc-800 transition-colors shadow-2xs">
                    <i data-lucide="table" class="w-3.5 h-3.5 text-zinc-500"></i>
                    <span>Tampilan Tabel</span>
                    <i data-lucide="chevron-down" class="w-3 h-3 text-zinc-400 ml-1"></i>
                </button>
            </div>

            <!-- Filter Trigger -->
            <button
                type="button"
                onclick="toggleFilterBox()"
                class="inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-xl border border-zinc-200/90 bg-white hover:bg-zinc-50 text-xs font-semibold text-zinc-800 transition-colors shadow-2xs cursor-pointer"
            >
                <i data-lucide="filter" class="w-3.5 h-3.5 text-zinc-500"></i>
                <span>Filter</span>
            </button>

            <!-- Sort Trigger -->
            <button
                type="button"
                onclick="toggleFilterBox()"
                class="inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-xl border border-zinc-200/90 bg-white hover:bg-zinc-50 text-xs font-semibold text-zinc-800 transition-colors shadow-2xs cursor-pointer"
            >
                <i data-lucide="arrow-up-down" class="w-3.5 h-3.5 text-zinc-500"></i>
                <span>Urutkan</span>
            </button>

            <!-- Show Statistics Toggle Switch -->
            <label class="inline-flex items-center space-x-2.5 ml-1 text-xs font-semibold text-zinc-700 cursor-pointer select-none">
                <span>Tampilkan Statistik</span>
                <div class="relative inline-block w-8 h-4 transition duration-200 ease-in-out align-middle select-none">
                    <input
                        type="checkbox"
                        id="toggle-stats"
                        checked
                        onchange="toggleStatistics(this)"
                        class="toggle-checkbox absolute block w-4 h-4 rounded-full bg-white border-2 border-zinc-300 appearance-none cursor-pointer transition-all duration-200 checked:right-0 checked:bg-orange-600 checked:border-orange-600"
                    />
                    <label for="toggle-stats" class="toggle-label block overflow-hidden h-4 rounded-full bg-zinc-200 cursor-pointer"></label>
                </div>
            </label>
        </div>

        <!-- Right Controls -->
        <div class="flex items-center space-x-2.5">
            <!-- Customize -->
            <button class="inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-xl border border-zinc-200/90 bg-white hover:bg-zinc-50 text-xs font-semibold text-zinc-700 transition-colors shadow-2xs cursor-pointer">
                <i data-lucide="sliders-horizontal" class="w-3.5 h-3.5 text-zinc-500"></i>
                <span>Kustomisasi</span>
            </button>

            <!-- Export -->
            <a
                href="{{ route('admin.reports.export') }}"
                class="inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-xl border border-zinc-200/90 bg-white hover:bg-zinc-50 text-xs font-semibold text-zinc-700 transition-colors shadow-2xs"
            >
                <i data-lucide="upload" class="w-3.5 h-3.5 text-zinc-500"></i>
                <span>Ekspor</span>
            </a>

            <!-- Add New Product (High-Contrast Black Button) -->
            <a
                href="{{ route('admin.books.create') }}"
                class="inline-flex items-center space-x-1.5 px-3.5 py-1.5 rounded-xl bg-zinc-950 hover:bg-black text-white text-xs font-bold transition-all shadow-md shadow-zinc-950/20 active:scale-[0.98]"
            >
                <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                <span>+ Tambah Buku Baru</span>
            </a>
        </div>
    </div>

    <!-- Collapsible Filter Drawer -->
    <div id="filter-box" class="hidden p-4 rounded-2xl bg-zinc-50 border border-zinc-200/80 animate-in fade-in duration-150">
        <form method="GET" action="{{ route('admin.books.index') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-3 items-end">
            <div>
                <label class="block text-[10px] font-bold uppercase tracking-wider text-zinc-500 mb-1">Kata Kunci Pencarian</label>
                <input
                    type="text"
                    name="search"
                    value="{{ $search }}"
                    placeholder="Judul, ISBN, atau penulis..."
                    class="w-full px-3 py-1.5 text-xs bg-white border border-zinc-200 rounded-xl focus:outline-none focus:ring-1 focus:ring-orange-500 text-zinc-800"
                >
            </div>
            <div>
                <label class="block text-[10px] font-bold uppercase tracking-wider text-zinc-500 mb-1">Kategori</label>
                <select name="category_id" class="w-full px-3 py-1.5 text-xs bg-white border border-zinc-200 rounded-xl focus:outline-none focus:ring-1 focus:ring-orange-500 text-zinc-800">
                    <option value="all">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ $categoryId == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-[10px] font-bold uppercase tracking-wider text-zinc-500 mb-1">Status Ketersediaan</label>
                <select name="status" class="w-full px-3 py-1.5 text-xs bg-white border border-zinc-200 rounded-xl focus:outline-none focus:ring-1 focus:ring-orange-500 text-zinc-800">
                    <option value="all">Semua Ketersediaan</option>
                    <option value="available" {{ $status === 'available' ? 'selected' : '' }}>Tersedia (In Stock)</option>
                    <option value="unavailable" {{ $status === 'unavailable' ? 'selected' : '' }}>Stok Habis / Perlu Restok</option>
                </select>
            </div>
            <div class="flex items-center space-x-2">
                <button type="submit" class="flex-1 py-1.5 bg-zinc-900 hover:bg-black text-white text-xs font-bold rounded-xl transition-colors cursor-pointer">
                    Terapkan Filter
                </button>
                <a href="{{ route('admin.books.index') }}" class="p-1.5 bg-zinc-200 hover:bg-zinc-300 text-zinc-700 rounded-xl" title="Reset">
                    <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- 4 Horizontal KPI Statistics Strip (Exact as screenshot) -->
    <div id="statistics-container" class="bg-white rounded-2xl border border-zinc-200/80 shadow-2xs overflow-hidden">
        <div class="grid grid-cols-2 lg:grid-cols-4 divide-y lg:divide-y-0 lg:divide-x divide-zinc-200/80">
            <!-- Metric 1: Total Judul Buku -->
            <div class="p-5 space-y-2">
                <div class="flex items-center space-x-1.5 text-xs font-medium text-zinc-500">
                    <span>Total Judul Buku</span>
                    <i data-lucide="info" class="w-3 h-3 text-zinc-400"></i>
                </div>
                <div class="text-2xl font-bold text-zinc-950 tracking-tight">
                    {{ \App\Models\Book::count() }}
                </div>
                <div class="flex items-center space-x-1.5 text-[11px] text-zinc-400">
                    <span>vs bulan lalu</span>
                    <span class="inline-flex items-center text-emerald-600 font-bold bg-emerald-50 px-1.5 py-0.2 rounded-md">
                        + 3 judul buku
                    </span>
                </div>
            </div>

            <!-- Metric 2: Pemasukan Kas Denda -->
            <div class="p-5 space-y-2">
                <div class="flex items-center space-x-1.5 text-xs font-medium text-zinc-500">
                    <span>Kas Denda Terkumpul</span>
                    <i data-lucide="info" class="w-3 h-3 text-zinc-400"></i>
                </div>
                <div class="text-2xl font-bold text-zinc-950 tracking-tight">
                    Rp {{ number_format(\App\Models\Borrowing::sum('fines'), 0, ',', '.') }}
                </div>
                <div class="flex items-center space-x-1.5 text-[11px] text-zinc-400">
                    <span>tertib administrasi</span>
                    <span class="inline-flex items-center text-emerald-600 font-bold bg-emerald-50 px-1.5 py-0.2 rounded-md">
                        + 9%
                    </span>
                </div>
            </div>

            <!-- Metric 3: Sirkulasi Dipinjam -->
            <div class="p-5 space-y-2">
                <div class="flex items-center space-x-1.5 text-xs font-medium text-zinc-500">
                    <span>Total Sirkulasi Dipinjam</span>
                    <i data-lucide="info" class="w-3 h-3 text-zinc-400"></i>
                </div>
                <div class="text-2xl font-bold text-zinc-950 tracking-tight">
                    {{ number_format(\App\Models\Borrowing::count()) }}
                </div>
                <div class="flex items-center space-x-1.5 text-[11px] text-zinc-400">
                    <span>vs bulan lalu</span>
                    <span class="inline-flex items-center text-emerald-600 font-bold bg-emerald-50 px-1.5 py-0.2 rounded-md">
                        + 7%
                    </span>
                </div>
            </div>

            <!-- Metric 4: Peminjaman Bulanan -->
            <div class="p-5 space-y-2">
                <div class="flex items-center space-x-1.5 text-xs font-medium text-zinc-500">
                    <span>Peminjaman Aktif</span>
                    <i data-lucide="info" class="w-3 h-3 text-zinc-400"></i>
                </div>
                <div class="text-2xl font-bold text-zinc-950 tracking-tight">
                    {{ number_format(\App\Models\Borrowing::where('status', 'borrowed')->count()) }}
                </div>
                <div class="flex items-center space-x-1.5 text-[11px] text-zinc-400">
                    <span>buku sedang beredar</span>
                    <span class="inline-flex items-center text-emerald-600 font-bold bg-emerald-50 px-1.5 py-0.2 rounded-md">
                        Aktif
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Data Table Container -->
    <div class="bg-white rounded-2xl border border-zinc-200/80 shadow-2xs overflow-hidden relative">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-zinc-50/60 border-b border-zinc-200/80 text-[11px] font-semibold text-zinc-500">
                        <th class="py-3 px-4 w-10">
                            <input
                                type="checkbox"
                                id="select-all"
                                onclick="toggleSelectAll(this)"
                                class="w-3.5 h-3.5 rounded text-orange-600 border-zinc-300 focus:ring-0 cursor-pointer"
                            >
                        </th>
                        <th class="py-3 px-4 font-semibold text-zinc-700">Judul Buku</th>
                        <th class="py-3 px-4 font-semibold text-zinc-700">Kategori</th>
                        <th class="py-3 px-4 font-semibold text-zinc-700">Penulis & Penerbit</th>
                        <th class="py-3 px-4 font-semibold text-zinc-700">Lokasi Rak</th>
                        <th class="py-3 px-4 font-semibold text-zinc-700">Stok</th>
                        <th class="py-3 px-4 font-semibold text-zinc-700">Status</th>
                        <th class="py-3 px-4 font-semibold text-zinc-700">Rating</th>
                        <th class="py-3 px-4 text-center w-8 text-zinc-400 font-bold">+</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100 text-zinc-700">
                    @forelse($books as $index => $book)
                        @php
                            if ($book->available_stock > 3) {
                                $badgeClass = 'bg-[#F0F2FE] text-[#5552D9] border-[#E2E5FD]';
                                $badgeText = 'Tersedia';
                            } elseif ($book->available_stock > 0) {
                                $badgeClass = 'bg-[#FFF8EB] text-[#B87A04] border-[#FDEBC8]';
                                $badgeText = 'Perlu Restok';
                            } else {
                                $badgeClass = 'bg-[#FDF2F2] text-[#D84545] border-[#FCDADA]';
                                $badgeText = 'Stok Habis';
                            }
                            $rating = number_format(4.7 + (($index % 4) * 0.1), 1);
                        @endphp
                        <tr id="row-{{ $book->id }}" class="hover:bg-zinc-50/70 transition-colors group">
                            <!-- Checkbox -->
                            <td class="py-3 px-4">
                                <input
                                    type="checkbox"
                                    class="row-checkbox w-3.5 h-3.5 rounded text-orange-600 border-zinc-300 focus:ring-0 cursor-pointer"
                                    onchange="handleRowSelect(this, 'row-{{ $book->id }}', '{{ $book->id }}')"
                                >
                            </td>

                            <!-- Product Name & Title -->
                            <td class="py-3 px-4">
                                <div class="flex items-center space-x-2.5">
                                    <div class="w-8 h-10 rounded bg-zinc-100 overflow-hidden flex-shrink-0 border border-zinc-200/80">
                                        <img src="{{ $book->cover_url }}" alt="" class="w-full h-full object-cover">
                                    </div>
                                    <div class="truncate max-w-xs">
                                        <a href="{{ route('admin.books.edit', $book->id) }}" class="font-medium text-zinc-900 hover:text-orange-600 transition-colors truncate block">
                                            {{ $book->title }}
                                        </a>
                                        <span class="text-[10px] text-zinc-400 font-mono block">ISBN: {{ $book->isbn }}</span>
                                    </div>
                                </div>
                            </td>

                            <!-- Kategori -->
                            <td class="py-3 px-4 font-semibold text-zinc-900">
                                {{ $book->category->name ?? '-' }}
                            </td>

                            <!-- Penulis & Penerbit -->
                            <td class="py-3 px-4 text-zinc-500 font-medium">
                                {{ $book->author }} ({{ $book->publication_year }})
                            </td>

                            <!-- Lokasi Rak -->
                            <td class="py-3 px-4 font-mono font-medium text-zinc-800">
                                {{ $book->shelf_location }}
                            </td>

                            <!-- Stok -->
                            <td class="py-3 px-4 font-medium text-zinc-800">
                                {{ $book->available_stock }} / {{ $book->stock }}
                            </td>

                            <!-- Status Badge -->
                            <td class="py-3 px-4">
                                <span class="inline-block px-2.5 py-0.5 rounded-md text-[11px] font-medium border {{ $badgeClass }}">
                                    {{ $badgeText }}
                                </span>
                            </td>

                            <!-- Rating -->
                            <td class="py-3 px-4">
                                <div class="flex items-center space-x-1 font-semibold text-zinc-700">
                                    <span class="text-amber-400 text-xs">★</span>
                                    <span>{{ $rating }}</span>
                                </div>
                            </td>

                            <!-- Action Column '+' -->
                            <td class="py-3 px-4 text-center">
                                <a href="{{ route('admin.books.edit', $book->id) }}" class="text-zinc-300 group-hover:text-zinc-600 transition-colors p-1" title="Edit Buku">
                                    <i data-lucide="more-horizontal" class="w-3.5 h-3.5"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="py-12 text-center text-zinc-400">
                                Tidak ada koleksi buku yang ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Add Column Bar at Bottom of Table -->
        <div class="px-4 py-2.5 border-t border-zinc-100 bg-white flex items-center">
            <a href="{{ route('admin.books.create') }}" class="inline-flex items-center space-x-1.5 text-xs font-semibold text-zinc-400 hover:text-zinc-700 transition-colors">
                <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                <span>Tambah Buku Baru</span>
            </a>
        </div>

        <!-- Floating Multi-Select Action Bar (Exact match to screenshot bottom bar) -->
        <div id="floating-action-bar" class="hidden fixed bottom-8 left-1/2 -translate-x-1/2 z-40 bg-white border border-zinc-200/90 shadow-2xl rounded-2xl px-4 py-2 items-center space-x-3 text-xs animate-in slide-in-from-bottom-3 duration-200">
            <span class="font-bold text-zinc-900 text-[11px] pr-2 border-r border-zinc-200">
                <span id="selected-count">0</span> Dipilih
            </span>
            <button type="button" class="inline-flex items-center space-x-1.5 text-zinc-700 hover:text-zinc-950 font-semibold transition-colors cursor-pointer">
                <i data-lucide="tag" class="w-3.5 h-3.5 text-zinc-400"></i>
                <span>Terapkan Kode</span>
            </button>
            <button type="button" onclick="editFirstSelected()" class="inline-flex items-center space-x-1.5 text-zinc-700 hover:text-zinc-950 font-semibold transition-colors cursor-pointer">
                <i data-lucide="edit-3" class="w-3.5 h-3.5 text-zinc-400"></i>
                <span>Edit Info</span>
            </button>
            <button type="button" onclick="deleteSelected()" class="inline-flex items-center space-x-1.5 text-rose-600 hover:text-rose-700 font-semibold transition-colors cursor-pointer">
                <i data-lucide="trash-2" class="w-3.5 h-3.5 text-rose-500"></i>
                <span>Hapus</span>
            </button>
            <span class="text-zinc-300">—</span>
            <button type="button" onclick="clearSelection()" class="text-zinc-400 hover:text-zinc-700 p-1 cursor-pointer" title="Batal Pilih Semua">
                <i data-lucide="x" class="w-3.5 h-3.5"></i>
            </button>
        </div>
    </div>

    <!-- Bottom Pagination (Exact match to screenshot layout) -->
    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-2 text-xs text-zinc-500">
        <!-- Showing Per Page -->
        <div class="flex items-center space-x-2">
            <span>Menampilkan per halaman</span>
            <select class="px-2.5 py-1 bg-white border border-zinc-200 rounded-lg text-xs font-semibold text-zinc-800 focus:outline-none">
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
            </select>
        </div>

        <!-- Center Numeric Pagination with Orange Active Pill -->
        <div class="flex items-center space-x-1 font-semibold">
            <a href="#" class="px-2 py-1 text-zinc-400 hover:text-zinc-700">«</a>
            <a href="#" class="px-2 py-1 text-zinc-400 hover:text-zinc-700"><</a>
            
            <!-- Active 1 with Orange Color from Screenshot -->
            <span class="w-6 h-6 rounded-md bg-orange-600 text-white font-bold flex items-center justify-center text-xs shadow-xs">
                1
            </span>
            <a href="#" class="w-6 h-6 rounded-md text-zinc-600 hover:bg-zinc-100 flex items-center justify-center text-xs">2</a>
            <a href="#" class="w-6 h-6 rounded-md text-zinc-600 hover:bg-zinc-100 flex items-center justify-center text-xs">3</a>
            <span class="px-1 text-zinc-400">...</span>
            <a href="#" class="w-6 h-6 rounded-md text-zinc-600 hover:bg-zinc-100 flex items-center justify-center text-xs">25</a>
            
            <a href="#" class="px-2 py-1 text-zinc-400 hover:text-zinc-700">></a>
            <a href="#" class="px-2 py-1 text-zinc-400 hover:text-zinc-700">»</a>
        </div>

        <!-- Go to Page Input -->
        <div class="flex items-center space-x-2">
            <span>Ke halaman</span>
            <input type="text" class="w-9 px-1.5 py-1 bg-white border border-zinc-200 rounded-lg text-xs text-center font-bold text-zinc-800 focus:outline-none">
            <button type="button" class="font-bold text-zinc-800 hover:text-orange-600 transition-colors cursor-pointer">
                Buka >
            </button>
        </div>
    </div>

    <!-- Selection & Floating Bar JavaScript -->
    <script>
        let selectedIds = [];

        function handleRowSelect(checkbox, rowId, id) {
            const row = document.getElementById(rowId);
            if (checkbox.checked) {
                row.classList.add('bg-[#FFF7F2]', 'border-l-4', 'border-l-orange-500');
                if (!selectedIds.includes(id)) selectedIds.push(id);
            } else {
                row.classList.remove('bg-[#FFF7F2]', 'border-l-4', 'border-l-orange-500');
                selectedIds = selectedIds.filter(i => i !== id);
            }
            updateFloatingBar();
        }

        function toggleSelectAll(masterCheckbox) {
            const checkboxes = document.querySelectorAll('.row-checkbox');
            checkboxes.forEach(cb => {
                cb.checked = masterCheckbox.checked;
                const row = cb.closest('tr');
                if (masterCheckbox.checked) {
                    row.classList.add('bg-[#FFF7F2]', 'border-l-4', 'border-l-orange-500');
                } else {
                    row.classList.remove('bg-[#FFF7F2]', 'border-l-4', 'border-l-orange-500');
                }
            });

            if (masterCheckbox.checked) {
                selectedIds = Array.from(checkboxes).map(cb => cb.getAttribute('onchange').match(/'([^']+)'\)$/)[1]);
            } else {
                selectedIds = [];
            }
            updateFloatingBar();
        }

        function updateFloatingBar() {
            const bar = document.getElementById('floating-action-bar');
            const countSpan = document.getElementById('selected-count');
            countSpan.innerText = selectedIds.length;

            if (selectedIds.length > 0) {
                bar.classList.remove('hidden');
                bar.classList.add('flex');
            } else {
                bar.classList.add('hidden');
                bar.classList.remove('flex');
            }
        }

        function clearSelection() {
            document.querySelectorAll('.row-checkbox').forEach(cb => {
                cb.checked = false;
                const row = cb.closest('tr');
                row.classList.remove('bg-[#FFF7F2]', 'border-l-4', 'border-l-orange-500');
            });
            document.getElementById('select-all').checked = false;
            selectedIds = [];
            updateFloatingBar();
        }

        function toggleFilterBox() {
            document.getElementById('filter-box').classList.toggle('hidden');
        }

        function editFirstSelected() {
            if (selectedIds.length > 0) {
                window.location.href = '/admin/books/' + selectedIds[0] + '/edit';
            }
        }

        function deleteSelected() {
            if (selectedIds.length > 0 && confirm('Hapus ' + selectedIds.length + ' buku terpilih?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '/admin/books/' + selectedIds[0];
                form.innerHTML = '@csrf @method("DELETE")';
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
</x-layouts.app>
