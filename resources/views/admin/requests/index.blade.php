<x-layouts.app title="Kelola Usulan Buku - SIPUS Digital">
    <x-slot:pageTitle>Kelola Usulan Buku Anggota</x-slot:pageTitle>

    <!-- Subheader Toolbar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-2 border-b border-zinc-200/70">
        <div class="flex items-center overflow-x-auto pb-1 sm:pb-0 gap-2 text-xs text-zinc-600 font-medium">
            <div class="flex items-center space-x-1.5 px-3 py-1.5 bg-white border border-zinc-200/80 rounded-xl shadow-2xs shrink-0">
                <i data-lucide="book-marked" class="w-3.5 h-3.5 text-zinc-500"></i>
                <span class="font-semibold text-zinc-800">Daftar Usulan</span>
            </div>

            <!-- Status Filter Tabs -->
            <a href="{{ route('admin.requests.index') }}" class="px-3 py-1.5 rounded-xl text-xs font-semibold transition-all shrink-0 {{ empty($status) || $status === 'all' ? 'bg-zinc-950 text-white shadow-2xs' : 'bg-white text-zinc-600 border border-zinc-200/80 hover:bg-zinc-50' }}">
                Semua Usulan
            </a>
            <a href="{{ route('admin.requests.index', ['status' => 'pending']) }}" class="px-3 py-1.5 rounded-xl text-xs font-semibold transition-all shrink-0 {{ $status === 'pending' ? 'bg-amber-500 text-white shadow-2xs' : 'bg-white text-zinc-600 border border-zinc-200/80 hover:bg-zinc-50' }}">
                Menunggu Review
            </a>
            <a href="{{ route('admin.requests.index', ['status' => 'approved']) }}" class="px-3 py-1.5 rounded-xl text-xs font-semibold transition-all shrink-0 {{ $status === 'approved' ? 'bg-sky-600 text-white shadow-2xs' : 'bg-white text-zinc-600 border border-zinc-200/80 hover:bg-zinc-50' }}">
                Disetujui
            </a>
            <a href="{{ route('admin.requests.index', ['status' => 'fulfilled']) }}" class="px-3 py-1.5 rounded-xl text-xs font-semibold transition-all shrink-0 {{ $status === 'fulfilled' ? 'bg-emerald-600 text-white shadow-2xs' : 'bg-white text-zinc-600 border border-zinc-200/80 hover:bg-zinc-50' }}">
                Telah Diadakan
            </a>
            <a href="{{ route('admin.requests.index', ['status' => 'rejected']) }}" class="px-3 py-1.5 rounded-xl text-xs font-semibold transition-all shrink-0 {{ $status === 'rejected' ? 'bg-rose-600 text-white shadow-2xs' : 'bg-white text-zinc-600 border border-zinc-200/80 hover:bg-zinc-50' }}">
                Ditolak
            </a>
        </div>
    </div>

    <!-- Requests Table -->
    <div class="bg-white rounded-2xl border border-zinc-200/80 shadow-2xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead class="bg-zinc-50/80 border-b border-zinc-200/80 text-[11px] font-semibold text-zinc-500 uppercase tracking-wider">
                    <tr>
                        <th class="py-3 px-4">Pengusul</th>
                        <th class="py-3 px-4">Buku Yang Diusulkan</th>
                        <th class="py-3 px-4">Penerbit & Tahun</th>
                        <th class="py-3 px-4">Alasan Pengusulan</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4 text-right">Aksi Tindakan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200/70 text-zinc-700">
                    @forelse($requests as $req)
                        <tr class="hover:bg-zinc-50/60 transition-colors">
                            <td class="py-3 px-4 whitespace-nowrap">
                                <div class="flex items-center space-x-2.5">
                                    <img src="{{ $req->user->avatar_url }}" alt="{{ $req->user->name }}" class="w-8 h-8 rounded-xl object-cover border border-zinc-200">
                                    <div>
                                        <p class="font-bold text-zinc-900">{{ $req->user->name }}</p>
                                        <p class="text-[10px] text-zinc-400 font-mono">{{ $req->user->member_code }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3 px-4">
                                <p class="font-bold text-zinc-900">{{ $req->title }}</p>
                                <p class="text-[11px] text-zinc-500">Penulis: {{ $req->author }}</p>
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
                            <td class="py-3 px-4 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-1.5">
                                    @if($req->status === 'pending')
                                        <form action="{{ route('admin.requests.updateStatus', $req) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="approved">
                                            <button type="submit" class="px-2.5 py-1 bg-sky-50 hover:bg-sky-100 text-sky-700 border border-sky-200 rounded-lg text-[11px] font-semibold transition cursor-pointer">
                                                Setujui
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.requests.updateStatus', $req) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="rejected">
                                            <button type="submit" class="px-2.5 py-1 bg-zinc-50 hover:bg-rose-50 hover:text-rose-700 text-zinc-600 border border-zinc-200 rounded-lg text-[11px] font-semibold transition cursor-pointer">
                                                Tolak
                                            </button>
                                        </form>
                                    @elseif($req->status === 'approved')
                                        <form action="{{ route('admin.requests.updateStatus', $req) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="fulfilled">
                                            <button type="submit" class="px-2.5 py-1 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-[11px] font-semibold shadow-2xs transition cursor-pointer">
                                                Tandai Tersedia di Katalog
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-zinc-400 text-[11px] italic">Selesai</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-zinc-400">
                                <i data-lucide="inbox" class="w-8 h-8 mx-auto mb-2 text-zinc-300"></i>
                                <p class="font-semibold text-zinc-700">Tidak ada usulan buku</p>
                                <p class="text-xs text-zinc-400 mt-1">Belum ada usulan pengadaan buku pada status ini.</p>
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
</x-layouts.app>
