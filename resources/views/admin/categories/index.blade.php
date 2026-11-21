<x-layouts.app title="Manajemen Kategori">
    <x-slot:pageTitle>Kategori Koleksi</x-slot:pageTitle>

    <!-- Subheader Toolbar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-2 border-b border-zinc-200/70">
        <div class="flex items-center space-x-2 text-xs text-zinc-600 font-medium">
            <div class="flex items-center space-x-1.5 px-3 py-1.5 bg-white border border-zinc-200/80 rounded-xl shadow-2xs">
                <i data-lucide="folder-tree" class="w-3.5 h-3.5 text-zinc-500"></i>
                <span class="font-semibold text-zinc-800">Tabel Kategori</span>
                <i data-lucide="chevron-down" class="w-3 h-3 text-zinc-400"></i>
            </div>
            <span class="text-xs text-zinc-400">Total {{ $categories->total() }} klasifikasi bidang ilmu</span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        <!-- Form Tambah Kategori (4 Cols) -->
        <div class="lg:col-span-4 bg-white rounded-2xl border border-zinc-200/80 shadow-2xs p-5 space-y-4">
            <div>
                <h3 class="font-bold text-sm text-zinc-900" id="form-title">Tambah Kategori Baru</h3>
                <p class="text-xs text-zinc-400 mt-0.5">Definisikan bidang ilmu atau pengelompokan buku</p>
            </div>

            <form id="category-form" method="POST" action="{{ route('admin.categories.store') }}" class="space-y-3.5">
                @csrf
                <div id="method-spoof"></div>

                <div>
                    <label for="cat-name" class="block text-[11px] font-semibold text-zinc-700 mb-1">
                        Nama Kategori <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        id="cat-name"
                        name="name"
                        required
                        placeholder="Contoh: Kecerdasan Buatan"
                        class="w-full px-3 py-1.5 text-xs bg-zinc-50/70 border border-zinc-200/80 rounded-xl focus:outline-none focus:ring-1 focus:ring-zinc-400 focus:bg-white text-zinc-900"
                    >
                </div>

                <div>
                    <label for="cat-description" class="block text-[11px] font-semibold text-zinc-700 mb-1">
                        Deskripsi Singkat
                    </label>
                    <textarea
                        id="cat-description"
                        name="description"
                        rows="3"
                        placeholder="Cakupan topik dan materi dalam kategori ini..."
                        class="w-full px-3 py-2 text-xs bg-zinc-50/70 border border-zinc-200/80 rounded-xl focus:outline-none focus:ring-1 focus:ring-zinc-400 focus:bg-white text-zinc-900 resize-none"
                    ></textarea>
                </div>

                <div class="flex items-center space-x-2 pt-2">
                    <button
                        type="button"
                        id="cancel-edit-btn"
                        onclick="resetCategoryForm()"
                        class="hidden flex-1 py-1.5 text-xs font-semibold text-zinc-600 bg-zinc-100 hover:bg-zinc-200 rounded-xl transition-colors cursor-pointer"
                    >
                        Batal
                    </button>
                    <button
                        type="submit"
                        id="submit-btn"
                        class="flex-1 py-1.5 px-4 bg-zinc-950 hover:bg-zinc-800 text-white text-xs font-semibold rounded-xl shadow-2xs transition-all flex items-center justify-center space-x-1.5 cursor-pointer"
                    >
                        <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                        <span id="submit-btn-text">Simpan Kategori</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Tabel Daftar Kategori (8 Cols) -->
        <div class="lg:col-span-8 bg-white rounded-2xl border border-zinc-200/80 shadow-2xs overflow-hidden flex flex-col">
            <div class="p-4 sm:p-5 border-b border-zinc-100 flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-sm text-zinc-900">Kategori Terdaftar</h3>
                    <p class="text-xs text-zinc-400 mt-0.5">Seluruh taksonomi buku yang aktif dalam sistem</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="border-b border-zinc-100 text-[11px] font-medium text-zinc-400">
                            <th class="py-3 px-4 w-10">
                                <input type="checkbox" class="w-3.5 h-3.5 rounded border-zinc-300 text-brand-orange focus:ring-0">
                            </th>
                            <th class="py-3 px-4">Nama & Slug</th>
                            <th class="py-3 px-4">Deskripsi</th>
                            <th class="py-3 px-4">Jumlah Judul Buku</th>
                            <th class="py-3 px-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100/70 text-zinc-700">
                        @forelse($categories as $category)
                            <tr class="hover:bg-zinc-50/60 transition-colors">
                                <td class="py-3.5 px-4">
                                    <input type="checkbox" class="w-3.5 h-3.5 rounded border-zinc-300 text-brand-orange focus:ring-0">
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="font-semibold text-zinc-900 block">{{ $category->name }}</span>
                                    <span class="font-mono text-[10px] text-zinc-400 block">{{ $category->slug }}</span>
                                </td>
                                <td class="py-3.5 px-4 text-zinc-500 max-w-xs truncate">
                                    {{ $category->description ?: 'Tidak ada deskripsi' }}
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-semibold bg-[#EEF2FF] text-[#4F46E5]">
                                        {{ $category->books_count }} Judul
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-right">
                                    <div class="inline-flex items-center space-x-1">
                                        <button
                                            type="button"
                                            onclick="editCategory({{ $category->id }}, '{{ addslashes($category->name) }}', '{{ addslashes($category->description ?? '') }}')"
                                            class="p-1 text-zinc-400 hover:text-zinc-800 hover:bg-zinc-100 rounded-lg transition-colors cursor-pointer"
                                            title="Edit Kategori"
                                        >
                                            <i data-lucide="edit-3" class="w-3.5 h-3.5"></i>
                                        </button>
                                        <form method="POST" action="{{ route('admin.categories.destroy', $category->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori \'{{ addslashes($category->name) }}\'?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                type="submit"
                                                class="p-1 text-zinc-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors cursor-pointer"
                                                title="Hapus Kategori"
                                                {{ $category->books_count > 0 ? 'disabled' : '' }}
                                            >
                                                <i data-lucide="trash-2" class="w-3.5 h-3.5 {{ $category->books_count > 0 ? 'opacity-30 cursor-not-allowed' : '' }}"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-10 text-center text-zinc-400">Belum ada kategori yang ditambahkan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($categories->hasPages())
                <div class="p-3.5 border-t border-zinc-100 bg-white">
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
