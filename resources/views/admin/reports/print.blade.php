<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Sirkulasi Perpustakaan ({{ Carbon\Carbon::parse($startDate)->format('d/m/Y') }} - {{ Carbon\Carbon::parse($endDate)->format('d/m/Y') }})</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Instrument Sans', sans-serif; color: #18181b; }
        .font-mono { font-family: 'JetBrains Mono', monospace; }
        @media print {
            .no-print { display: none !important; }
            body { padding: 0 !important; }
            @page { size: A4 landscape; margin: 15mm; }
        }
    </style>
</head>
<body class="bg-zinc-50 min-h-screen py-4 sm:py-8 px-3 sm:px-6 text-xs">

    <!-- Action Bar -->
    <div class="no-print max-w-6xl mx-auto mb-4 sm:mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-white p-3.5 sm:p-4 rounded-2xl border border-zinc-200 shadow-xs">
        <div>
            <h2 class="font-bold text-sm text-zinc-900">Pratinjau Cetak Laporan Resmi</h2>
            <p class="text-xs text-zinc-400">Siap dicetak atau disimpan sebagai dokumen PDF resmi bertanda tangan.</p>
        </div>
        <div class="flex items-center space-x-2 sm:space-x-2.5 w-full sm:w-auto">
            <button onclick="window.print()" class="flex-1 sm:flex-initial px-4 sm:px-5 py-2 bg-zinc-950 hover:bg-black text-white text-xs font-bold rounded-xl shadow-xs transition flex items-center justify-center space-x-2 cursor-pointer">
                <svg class="w-4 h-4 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                </svg>
                <span>Cetak / Ekspor PDF</span>
            </button>
            <button onclick="window.close()" class="px-4 py-2 bg-zinc-100 hover:bg-zinc-200 text-zinc-700 text-xs font-semibold rounded-xl transition cursor-pointer">
                Tutup
            </button>
        </div>
    </div>

    <!-- Official Report Paper (A4 Landscape) -->
    <div class="max-w-6xl mx-auto bg-white border border-zinc-300 p-4 sm:p-8 lg:p-10 rounded-2xl shadow-lg print:border-none print:shadow-none print:p-0 space-y-5 sm:space-y-6">
        
        <!-- Official Letterhead / Kop Surat -->
        <div class="border-b-2 border-zinc-900 pb-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div class="flex items-center space-x-3 sm:space-x-4">
                <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl bg-zinc-950 text-orange-500 font-bold flex items-center justify-center text-lg sm:text-xl shadow-inner border border-zinc-800 shrink-0">
                    S
                </div>
                <div>
                    <h1 class="text-sm sm:text-lg font-bold uppercase tracking-wider text-zinc-950">PERPUSTAKAAN DIGITAL TERPADU (SIPUS)</h1>
                    <p class="text-[11px] sm:text-xs text-zinc-600 font-medium">Gedung Pusat Informasi & Literasi Lt. 2, Jl. Perpustakaan No. 1, Jakarta Pusat</p>
                    <p class="text-[9px] sm:text-[10px] text-zinc-400 font-mono">Website: https://sipus.perpus.id • Email: admin@perpustakaan.com • Telp: (021) 7654-3210</p>
                </div>
            </div>
            <div class="text-left sm:text-right font-mono text-[9px] sm:text-[10px] text-zinc-500 pt-2 sm:pt-0 border-t sm:border-t-0 border-zinc-100 flex sm:block justify-between items-center">
                <p>KODE: <strong class="text-zinc-900">RPT-{{ date('Ymd-His') }}</strong></p>
                <p>TANGGAL: {{ date('d/m/Y H:i') }}</p>
            </div>
        </div>

        <!-- Title & Period -->
        <div class="text-center space-y-1">
            <h2 class="text-base font-bold text-zinc-900 uppercase tracking-wide">LAPORAN SIRKULASI & STATISTIK PEMINJAMAN BUKU</h2>
            <p class="text-xs text-zinc-600 font-medium">
                Periode: <strong class="text-zinc-900">{{ Carbon\Carbon::parse($startDate)->format('d F Y') }}</strong> s/d <strong class="text-zinc-900">{{ Carbon\Carbon::parse($endDate)->format('d F Y') }}</strong>
            </p>
        </div>

        <!-- KPI Summary Cards (Fully Responsive Grid) -->
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 print:grid-cols-5 gap-2 sm:gap-3 text-center">
            <div class="p-2.5 sm:p-3 rounded-xl border border-zinc-200 bg-zinc-50/70">
                <span class="text-[9px] sm:text-[10px] text-zinc-500 block uppercase font-medium truncate">Total Sirkulasi</span>
                <span class="text-sm sm:text-base font-bold text-zinc-900">{{ $totalTransactions }}</span>
            </div>
            <div class="p-2.5 sm:p-3 rounded-xl border border-zinc-200 bg-zinc-50/70">
                <span class="text-[9px] sm:text-[10px] text-zinc-500 block uppercase font-medium truncate">Sedang Dipinjam</span>
                <span class="text-sm sm:text-base font-bold text-blue-600">{{ $totalBorrowed }}</span>
            </div>
            <div class="p-2.5 sm:p-3 rounded-xl border border-zinc-200 bg-zinc-50/70">
                <span class="text-[9px] sm:text-[10px] text-zinc-500 block uppercase font-medium truncate">Dikembalikan</span>
                <span class="text-sm sm:text-base font-bold text-emerald-600">{{ $totalReturned }}</span>
            </div>
            <div class="p-2.5 sm:p-3 rounded-xl border border-zinc-200 bg-zinc-50/70">
                <span class="text-[9px] sm:text-[10px] text-zinc-500 block uppercase font-medium truncate">Terlambat</span>
                <span class="text-sm sm:text-base font-bold text-rose-600">{{ $totalOverdue }}</span>
            </div>
            <div class="col-span-2 sm:col-span-1 lg:col-span-1 print:col-span-1 p-2.5 sm:p-3 rounded-xl border border-zinc-200 bg-zinc-50/70">
                <span class="text-[9px] sm:text-[10px] text-zinc-500 block uppercase font-medium truncate">Total Denda</span>
                <span class="text-sm sm:text-base font-bold text-zinc-900 font-mono">Rp {{ number_format($totalFines, 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- Data Table -->
        <div class="overflow-x-auto border border-zinc-300 rounded-xl">
            <table class="w-full text-left text-[11px] border-collapse min-w-[760px] print:min-w-full">
                <thead class="bg-zinc-100 border-b border-zinc-300 text-zinc-700 font-bold uppercase tracking-wider text-[10px]">
                    <tr>
                        <th class="py-2.5 px-3 w-8">No</th>
                        <th class="py-2.5 px-3">Kode Pinjam</th>
                        <th class="py-2.5 px-3">Nama Peminjam</th>
                        <th class="py-2.5 px-3">Judul Buku</th>
                        <th class="py-2.5 px-3">ISBN</th>
                        <th class="py-2.5 px-3">Tgl Pinjam</th>
                        <th class="py-2.5 px-3">Batas Tempo</th>
                        <th class="py-2.5 px-3">Tgl Kembali</th>
                        <th class="py-2.5 px-3">Status</th>
                        <th class="py-2.5 px-3 text-right">Denda</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 text-zinc-800">
                    @forelse($borrowings as $index => $b)
                        <tr class="{{ $index % 2 === 1 ? 'bg-zinc-50/60' : '' }}">
                            <td class="py-2 px-3 text-zinc-400 font-mono">{{ $index + 1 }}</td>
                            <td class="py-2 px-3 font-mono font-semibold">{{ $b->borrowing_code }}</td>
                            <td class="py-2 px-3 font-medium">{{ $b->user->name ?? '-' }}</td>
                            <td class="py-2 px-3 max-w-xs truncate">{{ $b->book->title ?? '-' }}</td>
                            <td class="py-2 px-3 font-mono text-[10px] text-zinc-500">{{ $b->book->isbn ?? '-' }}</td>
                            <td class="py-2 px-3">{{ $b->borrow_date->format('d/m/Y') }}</td>
                            <td class="py-2 px-3">{{ $b->return_due_date->format('d/m/Y') }}</td>
                            <td class="py-2 px-3">{{ $b->actual_return_date ? $b->actual_return_date->format('d/m/Y') : '—' }}</td>
                            <td class="py-2 px-3 uppercase font-bold text-[9px]">
                                {{ $b->status }}
                            </td>
                            <td class="py-2 px-3 text-right font-mono font-semibold {{ $b->fines > 0 ? 'text-red-600' : 'text-zinc-500' }}">
                                {{ $b->fines > 0 ? 'Rp ' . number_format($b->fines, 0, ',', '.') : '—' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="py-8 text-center text-zinc-400 italic">
                                Tidak ada data transaksi peminjaman pada rentang tanggal yang dipilih.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Official Signatures (Tanda Tangan) -->
        <div class="pt-8 grid grid-cols-2 gap-8 text-center text-xs text-zinc-800 break-inside-avoid">
            <div>
                <p class="text-zinc-500">Mengetahui,</p>
                <p class="font-bold text-zinc-950 mt-0.5">Kepala Perpustakaan</p>
                <div class="h-16"></div>
                <p class="font-bold underline text-zinc-950">Drs. H. Mulyadi, M.Pd.</p>
                <p class="text-[10px] text-zinc-500 font-mono">NIP. 19780512 200212 1 004</p>
            </div>
            <div>
                <p class="text-zinc-500">Petugas Sirkulasi,</p>
                <p class="font-bold text-zinc-950 mt-0.5">{{ Auth::user()->name }}</p>
                <div class="h-16"></div>
                <p class="font-bold underline text-zinc-950">{{ Auth::user()->name }}</p>
                <p class="text-[10px] text-zinc-500 font-mono">ID Staf: {{ Auth::user()->email }}</p>
            </div>
        </div>

    </div>

</body>
</html>
