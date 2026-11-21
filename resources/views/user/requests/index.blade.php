<x-layouts.app title="Usulan Buku Baru - SIPUS Digital">
    <x-slot:pageTitle>Usulan Pengadaan Buku</x-slot:pageTitle>

    <!-- Subheader Toolbar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-2 border-b border-zinc-200/70">
        <div class="flex items-center space-x-2 text-xs text-zinc-600 font-medium">
            <div class="flex items-center space-x-1.5 px-3 py-1.5 bg-white border border-zinc-200/80 rounded-xl shadow-2xs">
                <i data-lucide="sparkles" class="w-3.5 h-3.5 text-zinc-500"></i>
                <span class="font-semibold text-zinc-800">Usulan Buku Anggota</span>
            </div>
            <span class="text-xs text-zinc-400">Total {{ $requests->total() }} usulan diajukan</span>
        </div>

        <button type="button" onclick="openRequestModal()" class="flex items-center space-x-1.5 px-3.5 py-1.5 text-xs font-semibold text-white bg-zinc-950 hover:bg-black rounded-xl shadow-2xs transition-all cursor-pointer">
            <i data-lucide="plus" class="w-3.5 h-3.5 text-orange-400"></i>
            <span>Ajukan Usulan Buku Baru</span>
        </button>
    </div>

    <!-- Requests Table -->
    <div class="bg-white rounded-2xl border border-zinc-200/80 shadow-2xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead class="bg-zinc-50/80 border-b border-zinc-200/80 text-[11px] font-semibold text-zinc-500 uppercase tracking-wider">
                    <tr>
                        <th class="py-3 px-4">Judul & Pengarang</th>
                        <th class="py-3 px-4">Penerbit & Tahun</th>
                        <th class="py-3 px-4">Alasan Kebutuhan</th>
                        <th class="py-3 px-4">Tgl Usulan</th>
                        <th class="py-3 px-4">Status Usulan</th>
                        <th class="py-3 px-4">Catatan Petugas</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200/70 text-zinc-700">
                    @forelse($requests as $req)
                        <tr class="hover:bg-zinc-50/60 transition-colors">
                            <td class="py-3 px-4">
                                <p class="font-bold text-zinc-900">{{ $req->title }}</p>
                                <p class="text-[11px] text-zinc-400">Penulis: {{ $req->author }}</p>
                                @if($req->isbn)
                                    <p class="text-[10px] font-mono text-zinc-400">ISBN: {{ $req->isbn }}</p>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-zinc-600">
                                <p>{{ $req->publisher ?? '-' }}</p>
                                <p class="text-[10px] text-zinc-400">{{ $req->publication_year ?? '-' }}</p>
                            </td>
                            <td class="py-3 px-4 text-zinc-600 max-w-xs leading-relaxed">
                                {{ $req->reason }}
                            </td>
                            <td class="py-3 px-4 whitespace-nowrap text-zinc-500 font-mono text-[11px]">
                                {{ $req->created_at->format('d/m/Y') }}
                            </td>
                            <td class="py-3 px-4 whitespace-nowrap">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase
                                    @if($req->status === 'fulfilled') bg-emerald-50 text-emerald-700 border border-emerald-200
                                    @elseif($req->status === 'approved') bg-sky-50 text-sky-700 border border-sky-200
                                    @elseif($req->status === 'rejected') bg-rose-50 text-rose-700 border border-rose-200
                                    @else bg-amber-50 text-amber-700 border border-amber-200 @endif
                                ">
                                    @if($req->status === 'fulfilled') Telah Diadakan
                                    @elseif($req->status === 'approved') Disetujui
                                    @elseif($req->status === 'rejected') Ditolak
                                    @else Menunggu Review @endif
                                </span>
                            </td>
                            <td class="py-3 px-4 text-zinc-500 text-[11px] max-w-xs italic">
                                {{ $req->admin_note ?? '—' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-zinc-400">
                                <i data-lucide="book-open" class="w-8 h-8 mx-auto mb-2 text-zinc-300"></i>
                                <p class="font-semibold text-zinc-700">Belum ada usulan buku yang Anda ajukan</p>
                                <p class="text-xs text-zinc-400 mt-1">Buku yang Anda cari belum ada di katalog? Klik tombol di atas untuk mengusulkannya.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($requests->hasPages())
            <div class="p-3.5 border-t border-zinc-100 bg-white">
                {{ $requests->links() }}
            </div>
        @endif
    </div>

    <!-- Request Book Modal -->
    <div id="requestModal" class="fixed inset-0 z-50 hidden bg-zinc-950/60 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl border border-zinc-100 space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-zinc-100">
                <h3 class="text-sm font-bold text-zinc-900 flex items-center gap-2">
                    <i data-lucide="book-plus" class="w-4 h-4 text-orange-600"></i>
                    <span>Ajukan Usulan Pengadaan Buku Baru</span>
                </h3>
                <button type="button" onclick="closeRequestModal()" class="text-zinc-400 hover:text-zinc-600 cursor-pointer">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>

            <form method="POST" action="{{ route('user.requests.store') }}" class="space-y-3.5 text-xs">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-zinc-700 mb-1">Judul Buku <span class="text-rose-500">*</span></label>
                    <input type="text" name="title" required placeholder="Contoh: Refactoring: Improving the Design of Existing Code" class="w-full px-3 py-2 bg-zinc-50 border border-zinc-300 rounded-xl focus:outline-none focus:ring-1 focus:ring-orange-500 focus:bg-white text-zinc-900">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-zinc-700 mb-1">Pengarang / Penulis <span class="text-rose-500">*</span></label>
                        <input type="text" name="author" required placeholder="Contoh: Martin Fowler" class="w-full px-3 py-2 bg-zinc-50 border border-zinc-300 rounded-xl focus:outline-none focus:ring-1 focus:ring-orange-500 focus:bg-white text-zinc-900">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-zinc-700 mb-1">Penerbit</label>
                        <input type="text" name="publisher" placeholder="Contoh: Addison-Wesley" class="w-full px-3 py-2 bg-zinc-50 border border-zinc-300 rounded-xl focus:outline-none focus:ring-1 focus:ring-orange-500 focus:bg-white text-zinc-900">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-zinc-700 mb-1">Tahun Terbit</label>
                        <input type="number" name="publication_year" placeholder="Contoh: 2018" min="1900" max="{{ date('Y') + 1 }}" class="w-full px-3 py-2 bg-zinc-50 border border-zinc-300 rounded-xl focus:outline-none focus:ring-1 focus:ring-orange-500 focus:bg-white text-zinc-900">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-zinc-700 mb-1">Nomor ISBN (Bila Ada)</label>
                        <input type="text" name="isbn" placeholder="Contoh: 978-0134757599" class="w-full px-3 py-2 bg-zinc-50 border border-zinc-300 rounded-xl focus:outline-none focus:ring-1 focus:ring-orange-500 focus:bg-white text-zinc-900 font-mono">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-zinc-700 mb-1">Alasan Kebutuhan Pengadaan <span class="text-rose-500">*</span></label>
                    <textarea name="reason" rows="3" required placeholder="Jelaskan secara singkat mengapa buku ini bermanfaat untuk disediakan di perpustakaan..." class="w-full px-3 py-2 bg-zinc-50 border border-zinc-300 rounded-xl focus:outline-none focus:ring-1 focus:ring-orange-500 focus:bg-white text-zinc-900"></textarea>
                </div>

                <div class="pt-3 border-t border-zinc-100 flex justify-end space-x-2">
                    <button type="button" onclick="closeRequestModal()" class="px-4 py-2 bg-zinc-100 hover:bg-zinc-200 text-zinc-700 font-semibold rounded-xl transition cursor-pointer">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 bg-zinc-950 hover:bg-black text-white font-bold rounded-xl shadow-xs transition cursor-pointer">
                        Kirim Usulan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openRequestModal() {
            document.getElementById('requestModal').classList.remove('hidden');
        }
        function closeRequestModal() {
            document.getElementById('requestModal').classList.add('hidden');
        }
    </script>
</x-layouts.app>
