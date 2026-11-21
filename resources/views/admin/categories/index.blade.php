<x-layouts.app title="Manajemen Kategori">
    <x-slot:header>Manajemen Kategori Buku</x-slot:header>
    <x-slot:subheader>Kelola pengelompokan klasifikasi buku untuk mempermudah pencarian koleksi</x-slot:subheader>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        <!-- Form Tambah Kategori (4 Cols) -->
        <div class="lg:col-span-4 bg-white rounded-3xl border border-slate-200/80 shadow-xs p-6 space-y-4">
            <div>
                <h3 class="font-extrabold text-base text-slate-900" id="form-title">Tambah Kategori Baru</h3>
                <p class="text-xs text-slate-500">Masukkan nama bidang ilmu atau klasifikasi buku</p>
            </div>

            <form id="category-form" method="POST" action="{{ route('admin.categories.store') }}" class="space-y-4">
                @csrf
                <div id="method-spoof"></div>

                <div>
                    <label for="name" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                        Nama Kategori <span class="text-rose-500">*</span>
                    </label>
                    <input
                        type="text"
                        id="cat-name"
                        name="name"
                        required
                        placeholder="e.g. Kecerdasan Buatan"
                        class="w-full px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-slate-900"
                    >
                </div>

                <div>
                    <label for="description" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                        Deskripsi Singkat
                    </label>
                    <textarea
                        id="cat-description"
                        name="description"
                        rows="3"
                        placeholder="Penjelasan mengenai cakupan topik dalam kategori ini..."
                        class="w-full px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-slate-900 resize-none"
                    ></textarea>
                </div>

                <div class="flex items-center space-x-2 pt-2">
                    <button
                        type="button"
                        id="cancel-edit-btn"
                        onclick="resetCategoryForm()"
                        class="hidden flex-1 py-2 text-xs font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors"
                    >
                        Batal
                    </button>
                    <button
                        type="submit"
                        id="submit-btn"
                        class="flex-1 py-2 px-4 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-md shadow-blue-600/20 transition-all flex items-center justify-center space-x-1.5"
                    >
                        <i data-lucide="plus-circle" class="w-4 h-4"></i>
                        <span id="submit-btn-text">Simpan Kategori</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Tabel Daftar Kategori (8 Cols) -->
        <div class="lg:col-span-8 bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden flex flex-col">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="font-extrabold text-base text-slate-900">Daftar Kategori Terdaftar</h3>
                    <p class="text-xs text-slate-500">Total {{ $categories->total() }} kategori aktif</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-slate-50/75 border-b border-slate-200 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                            <th class="py-3 px-4">Nama & Slug</th>
                            <th class="py-3 px-4">Deskripsi</th>
                            <th class="py-3 px-4">Jumlah Buku</th>
                            <th class="py-3 px-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($categories as $category)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="py-3 px-4">
                                    <span class="font-bold text-slate-900 block">{{ $category->name }}</span>
                                    <span class="font-mono text-[10px] text-slate-400 block">{{ $category->slug }}</span>
                                </td>
                                <td class="py-3 px-4 text-slate-600 max-w-xs truncate">
                                    {{ $category->description ?: 'Tidak ada deskripsi' }}
                                </td>
                                <td class="py-3 px-4">
                                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                        {{ $category->books_count }} Judul
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-right">
                                    <div class="inline-flex items-center space-x-1">
                                        <button
                                            type="button"
                                            onclick="editCategory({{ $category->id }}, '{{ addslashes($category->name) }}', '{{ addslashes($category->description ?? '') }}')"
                                            class="p-1.5 text-slate-500 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-colors"
                                            title="Edit Kategori"
                                        >
                                            <i data-lucide="edit-3" class="w-4 h-4"></i>
                                        </button>
                                        <form method="POST" action="{{ route('admin.categories.destroy', $category->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori \'{{ addslashes($category->name) }}\'?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                type="submit"
                                                class="p-1.5 text-slate-500 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors"
                                                title="Hapus Kategori"
                                                {{ $category->books_count > 0 ? 'disabled' : '' }}
                                            >
                                                <i data-lucide="trash-2" class="w-4 h-4 {{ $category->books_count > 0 ? 'opacity-30 cursor-not-allowed' : '' }}"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-8 text-center text-slate-400">Belum ada kategori yang ditambahkan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($categories->hasPages())
                <div class="p-4 border-t border-slate-200 bg-slate-50/50">
                    {{ $categories->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- JavaScript to handle Inline Edit -->
    <script>
        function editCategory(id, name, description) {
            document.getElementById('form-title').innerText = 'Edit Kategori: ' + name;
            document.getElementById('cat-name').value = name;
            document.getElementById('cat-description').value = description;

            const form = document.getElementById('category-form');
            form.action = '/admin/categories/' + id;

            document.getElementById('method-spoof').innerHTML = '<input type="hidden" name="_method" value="PUT">';
            document.getElementById('submit-btn-text').innerText = 'Perbarui Kategori';
            document.getElementById('cancel-edit-btn').classList.remove('hidden');

            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function resetCategoryForm() {
            document.getElementById('form-title').innerText = 'Tambah Kategori Baru';
            document.getElementById('cat-name').value = '';
            document.getElementById('cat-description').value = '';

            const form = document.getElementById('category-form');
            form.action = '{{ route("admin.categories.store") }}';

            document.getElementById('method-spoof').innerHTML = '';
            document.getElementById('submit-btn-text').innerText = 'Simpan Kategori';
            document.getElementById('cancel-edit-btn').classList.add('hidden');
        }
    </script>
</x-layouts.app>
