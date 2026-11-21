<x-layouts.app title="Scan QR & Barcode Sirkulasi - SIPUS Digital">
    <x-slot:pageTitle>Meja Sirkulasi: Scan QR & Barcode</x-slot:pageTitle>

    <!-- Subheader Toolbar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-2 border-b border-zinc-200/70">
        <div class="flex items-center space-x-2 text-xs text-zinc-600 font-medium">
            <div class="flex items-center space-x-1.5 px-3 py-1.5 bg-white border border-zinc-200/80 rounded-xl shadow-2xs">
                <i data-lucide="qr-code" class="w-3.5 h-3.5 text-zinc-500"></i>
                <span class="font-semibold text-zinc-800">Scanner Sirkulasi Meja Layanan</span>
            </div>
            <span class="text-xs text-zinc-400">Scan Barcode ISBN Buku atau QR Tiket Peminjaman</span>
        </div>
    </div>

    <!-- Scanner Container -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
        <!-- Input & Scanner Area (Left 1 Col) -->
        <div class="lg:col-span-1 space-y-4">
            <div class="bg-white rounded-2xl border border-zinc-200/80 p-5 shadow-2xs">
                <h3 class="text-sm font-bold text-zinc-900 mb-2 flex items-center gap-2">
                    <i data-lucide="scan" class="w-4 h-4 text-orange-600"></i>
                    <span>Input / Scan Kode</span>
                </h3>
                <p class="text-xs text-zinc-500 mb-4">
                    Gunakan scanner barcode fisik atau masukkan kode peminjaman / nomor ISBN secara manual.
                </p>

                <form method="GET" action="{{ route('admin.scanner.index') }}" class="space-y-3">
                    <div class="relative">
                        <input
                            type="text"
                            name="code"
                            id="scan-input"
                            value="{{ $code }}"
                            autofocus
                            placeholder="Contoh: BRW-202611-0001"
                            class="w-full px-3.5 py-2.5 text-sm font-mono bg-zinc-50 border border-zinc-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-500 focus:bg-white text-zinc-900 placeholder-zinc-400 shadow-inner"
                        >
                    </div>

                    <button type="submit" class="w-full py-2.5 bg-zinc-950 hover:bg-black text-white text-xs font-bold rounded-xl transition shadow-xs flex items-center justify-center gap-2 cursor-pointer">
                        <i data-lucide="search" class="w-3.5 h-3.5"></i>
                        <span>Cari & Validasi Data</span>
                    </button>
                </form>

                <!-- Quick Test Samples -->
                <div class="mt-5 pt-4 border-t border-zinc-100">
                    <span class="text-[11px] font-semibold text-zinc-400 uppercase tracking-wider block mb-2">Sampel Cepat (Klik untuk Uji):</span>
                    <div class="flex flex-wrap gap-1.5">
                        <a href="{{ route('admin.scanner.index', ['code' => 'BRW-202611-0001']) }}" class="px-2 py-1 bg-zinc-100 hover:bg-orange-50 hover:text-orange-700 text-zinc-600 rounded-lg text-[10px] font-mono transition">
                            BRW-202611-0001
                        </a>
                        <a href="{{ route('admin.scanner.index', ['code' => 'BRW-202611-0002']) }}" class="px-2 py-1 bg-zinc-100 hover:bg-orange-50 hover:text-orange-700 text-zinc-600 rounded-lg text-[10px] font-mono transition">
                            BRW-202611-0002
                        </a>
                        <a href="{{ route('admin.scanner.index', ['code' => '978-0132350884']) }}" class="px-2 py-1 bg-zinc-100 hover:bg-orange-50 hover:text-orange-700 text-zinc-600 rounded-lg text-[10px] font-mono transition">
                            ISBN Clean Code
                        </a>
                    </div>
                </div>
            </div>

            <!-- Scanner Guide Card -->
            <div class="bg-gradient-to-br from-zinc-900 to-zinc-950 rounded-2xl p-4 text-white text-xs border border-zinc-800 shadow-sm">
                <div class="flex items-center gap-2 mb-2">
                    <span class="p-1 rounded-md bg-orange-500/20 text-orange-400">
                        <i data-lucide="info" class="w-3.5 h-3.5"></i>
                    </span>
                    <span class="font-bold">Panduan Serah Terima Buku</span>
                </div>
                <ul class="text-[11px] text-zinc-400 space-y-1.5 list-disc list-inside">
                    <li>Scan tiket permohonan anggota untuk <strong class="text-white">Persetujuan</strong>.</li>
                    <li>Scan barcode buku saat pengembalian untuk <strong class="text-white">Verifikasi Fisik</strong>.</li>
                    <li>Denda otomatis dihitung jika lewat tanggal jatuh tempo.</li>
                </ul>
            </div>
        </div>

        <!-- Result Card Area (Right 2 Cols) -->
        <div class="lg:col-span-2">
            @if($borrowing)
                <!-- Borrowing Result -->
                <div class="bg-white rounded-2xl border border-zinc-200/80 p-5 shadow-2xs space-y-5">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-zinc-100 gap-2">
                        <div>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-mono font-bold uppercase
                                @if($borrowing->status === 'borrowed') bg-sky-50 text-sky-700 border border-sky-200
                                @elseif($borrowing->status === 'pending') bg-amber-50 text-amber-700 border border-amber-200
                                @elseif($borrowing->status === 'returned') bg-emerald-50 text-emerald-700 border border-emerald-200
                                @else bg-zinc-100 text-zinc-700 border border-zinc-200 @endif
                            ">
                                {{ strtoupper($borrowing->status) }}
                            </span>
                            <h2 class="text-lg font-bold text-zinc-900 mt-1 font-mono">{{ $borrowing->borrowing_code }}</h2>
                        </div>

                        <!-- Fine Indicator if overdue -->
                        @if($borrowing->isOverdue())
                            <div class="px-3 py-1.5 bg-rose-50 border border-rose-200 rounded-xl text-rose-700 text-xs">
                                <span class="font-bold flex items-center gap-1">
                                    <i data-lucide="alert-triangle" class="w-3.5 h-3.5"></i>
                                    Terlambat {{ $borrowing->daysOverdue() }} Hari
                                </span>
                                <span class="text-[11px]">Denda: Rp {{ number_format($borrowing->fines, 0, ',', '.') }}</span>
                            </div>
                        @endif
                    </div>

                    <!-- Details Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                        <!-- Borrower Info -->
                        <div class="p-3.5 bg-zinc-50 rounded-xl border border-zinc-200/70 space-y-2">
                            <span class="text-[10px] font-bold text-zinc-400 uppercase tracking-wider block">Peminjam (Anggota)</span>
                            <div class="flex items-center space-x-2.5">
                                <div class="w-8 h-8 rounded-full bg-zinc-900 text-white font-bold flex items-center justify-center text-xs">
                                    {{ strtoupper(substr($borrowing->user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-bold text-zinc-900">{{ $borrowing->user->name }}</p>
                                    <p class="text-[11px] text-zinc-500">{{ $borrowing->user->email }}</p>
                                </div>
                            </div>
                            <p class="text-[11px] text-zinc-600">Telepon: {{ $borrowing->user->phone ?? '-' }}</p>
                        </div>

                        <!-- Book Info -->
                        <div class="p-3.5 bg-zinc-50 rounded-xl border border-zinc-200/70 space-y-2">
                            <span class="text-[10px] font-bold text-zinc-400 uppercase tracking-wider block">Detail Buku</span>
                            <p class="font-bold text-zinc-900 line-clamp-1">{{ $borrowing->book->title }}</p>
                            <p class="text-[11px] font-mono text-zinc-500">ISBN: {{ $borrowing->book->isbn }}</p>
                            <p class="text-[11px] text-zinc-600">Lokasi: <span class="font-semibold text-zinc-800">{{ $borrowing->book->shelf_location }}</span></p>
                        </div>
                    </div>

                    <!-- Dates & Timeline -->
                    <div class="p-3.5 bg-zinc-50/70 rounded-xl border border-zinc-200/70 grid grid-cols-2 sm:grid-cols-3 gap-3 text-xs">
                        <div>
                            <span class="text-[10px] text-zinc-400 block font-medium">Tanggal Pinjam</span>
                            <span class="font-semibold text-zinc-800">{{ $borrowing->borrow_date->format('d M Y') }}</span>
                        </div>
                        <div>
                            <span class="text-[10px] text-zinc-400 block font-medium">Batas Kembali</span>
                            <span class="font-semibold {{ $borrowing->isOverdue() ? 'text-rose-600 font-bold' : 'text-zinc-800' }}">
                                {{ $borrowing->return_due_date->format('d M Y') }}
                            </span>
                        </div>
                        <div>
                            <span class="text-[10px] text-zinc-400 block font-medium">Perpanjangan</span>
                            <span class="font-semibold text-zinc-800">{{ $borrowing->renewed_count ?? 0 }}x diperpanjang</span>
                        </div>
                    </div>

                    <!-- Direct Action Buttons -->
                    <div class="pt-3 border-t border-zinc-100 flex flex-wrap gap-2.5">
                        @if($borrowing->status === 'pending')
                            <form action="{{ route('admin.borrowings.approve', $borrowing) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-xs flex items-center gap-1.5 transition cursor-pointer">
                                    <i data-lucide="check-circle" class="w-3.5 h-3.5"></i>
                                    <span>Setujui & Serahkan Buku Sekarang</span>
                                </button>
                            </form>
                            <form action="{{ route('admin.borrowings.reject', $borrowing) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="px-4 py-2 bg-zinc-100 hover:bg-rose-50 hover:text-rose-700 text-zinc-700 font-medium text-xs rounded-xl border border-zinc-200 transition cursor-pointer">
                                    Tolak Permohonan
                                </button>
                            </form>
                        @elseif($borrowing->status === 'borrowed')
                            <form action="{{ route('admin.borrowings.return', $borrowing) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="fines" value="{{ $borrowing->fines }}">
                                <button type="submit" class="px-4 py-2 bg-zinc-950 hover:bg-black text-white font-bold text-xs rounded-xl shadow-xs flex items-center gap-1.5 transition cursor-pointer">
                                    <i data-lucide="corner-down-left" class="w-3.5 h-3.5"></i>
                                    <span>Konfirmasi Buku Dikembalikan</span>
                                </button>
                            </form>
                        @else
                            <div class="text-xs text-zinc-500 italic py-1">
                                Transaksi ini sudah selesai (Status: {{ $borrowing->status }}).
                            </div>
                        @endif
                    </div>
                </div>
            @elseif($book)
                <!-- Book Result -->
                <div class="bg-white rounded-2xl border border-zinc-200/80 p-5 shadow-2xs space-y-4">
                    <div class="flex items-start gap-4">
                        <img src="{{ $book->cover_url }}" alt="{{ $book->title }}" class="w-20 h-28 object-cover rounded-xl shadow-xs border border-zinc-200 flex-shrink-0">
                        <div class="space-y-1.5 flex-1">
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-orange-50 text-orange-700 border border-orange-200">
                                {{ $book->category->name ?? 'Katalog' }}
                            </span>
                            <h2 class="text-base font-bold text-zinc-900">{{ $book->title }}</h2>
                            <p class="text-xs text-zinc-500 font-mono">ISBN: {{ $book->isbn }}</p>
                            <p class="text-xs text-zinc-600">Penulis: <span class="font-medium text-zinc-800">{{ $book->author }}</span></p>
                            <div class="pt-2 flex items-center gap-3 text-xs">
                                <span class="font-medium text-zinc-600">Lokasi: <strong class="text-zinc-900">{{ $book->shelf_location }}</strong></span>
                                <span class="font-medium text-zinc-600">Stok: <strong class="text-emerald-700">{{ $book->available_stock }} / {{ $book->stock }}</strong></span>
                            </div>
                        </div>
                    </div>
                    <div class="pt-3 border-t border-zinc-100 flex gap-2">
                        <a href="{{ route('admin.books.edit', $book) }}" class="px-3.5 py-1.5 bg-zinc-100 hover:bg-zinc-200 text-zinc-800 rounded-xl text-xs font-semibold transition">
                            Edit Buku
                        </a>
                        <a href="{{ route('admin.books.show', $book) }}" class="px-3.5 py-1.5 bg-zinc-950 hover:bg-black text-white rounded-xl text-xs font-semibold transition">
                            Lihat Riwayat Buku
                        </a>
                    </div>
                </div>
            @elseif($code)
                <!-- Not Found State -->
                <div class="bg-white rounded-2xl border border-zinc-200/80 p-8 text-center shadow-2xs">
                    <i data-lucide="help-circle" class="w-10 h-10 mx-auto mb-2 text-zinc-300"></i>
                    <h3 class="text-sm font-bold text-zinc-800 mb-1">Data Tidak Ditemukan</h3>
                    <p class="text-xs text-zinc-500 max-w-sm mx-auto">
                        Tidak ada transaksi peminjaman, buku, atau anggota yang cocok dengan kode <span class="font-mono font-semibold text-zinc-800">"{{ $code }}"</span>.
                    </p>
                </div>
            @else
                <!-- Idle State -->
                <div class="bg-white rounded-2xl border border-zinc-200/80 p-8 text-center shadow-2xs">
                    <i data-lucide="scan-line" class="w-12 h-12 mx-auto mb-3 text-orange-500/60 animate-pulse"></i>
                    <h3 class="text-sm font-bold text-zinc-800 mb-1">Siap Menerima Scan</h3>
                    <p class="text-xs text-zinc-400 max-w-md mx-auto">
                        Arahkan scanner ke QR Code peminjaman anggota atau ketikkan kode di sebelah kiri untuk validasi cepat.
                    </p>
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>
