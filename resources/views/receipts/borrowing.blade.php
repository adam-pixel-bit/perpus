<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Bukti Sirkulasi - {{ $borrowing->borrowing_code }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;600;700&family=Instrument+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Instrument Sans', sans-serif; }
        .font-mono { font-family: 'JetBrains Mono', monospace; }
        @media print {
            .no-print { display: none !important; }
            body { padding: 0 !important; background: white !important; }
            .receipt { box-shadow: none !important; border: 1px dashed #71717a !important; margin: 0 auto !important; }
        }
    </style>
</head>
<body class="bg-zinc-100 min-h-screen py-8 px-4 flex flex-col items-center justify-center">

    <!-- Action Bar -->
    <div class="no-print mb-6 flex items-center space-x-3">
        <button onclick="window.print()" class="px-5 py-2.5 bg-zinc-950 hover:bg-black text-white text-xs font-bold rounded-xl shadow-md transition flex items-center space-x-2 cursor-pointer">
            <svg class="w-4 h-4 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
            </svg>
            <span>Cetak Struk / Simpan PDF</span>
        </button>
        <button onclick="window.close()" class="px-4 py-2.5 bg-white border border-zinc-300 text-zinc-700 text-xs font-semibold rounded-xl hover:bg-zinc-50 transition cursor-pointer">
            Tutup
        </button>
    </div>

    <!-- Thermal Paper Receipt (Standard 80mm / 380px) -->
    <div class="receipt w-full max-w-[360px] bg-white border border-zinc-300 rounded-2xl p-6 shadow-xl text-zinc-900 font-mono text-xs space-y-4">
        <!-- Header -->
        <div class="text-center pb-3 border-b border-dashed border-zinc-300 space-y-1">
            <h2 class="font-bold text-sm tracking-widest text-zinc-950">SIPUS DIGITAL</h2>
            <p class="text-[10px] text-zinc-500">Sistem Informasi Perpustakaan Terpadu</p>
            <p class="text-[9px] text-zinc-400">Gedung Pusat Perpustakaan Lt. 2, Jakarta</p>
        </div>

        <!-- Meta Info -->
        <div class="text-[11px] space-y-1 border-b border-dashed border-zinc-300 pb-3">
            <div class="flex justify-between">
                <span class="text-zinc-500">No. Transaksi</span>
                <span class="font-bold">{{ $borrowing->borrowing_code }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-zinc-500">Tanggal Transaksi</span>
                <span>{{ $borrowing->borrow_date->format('d/m/Y') }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-zinc-500">Anggota</span>
                <span class="font-bold">{{ $borrowing->user->name }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-zinc-500">Kode Member</span>
                <span>{{ $borrowing->user->member_code }}</span>
            </div>
        </div>

        <!-- Book Details -->
        <div class="border-b border-dashed border-zinc-300 pb-3 space-y-1.5 text-[11px]">
            <span class="text-[10px] text-zinc-400 uppercase font-semibold">Buku Yang Dipinjam:</span>
            <p class="font-bold text-xs text-zinc-950 leading-snug">{{ $borrowing->book->title }}</p>
            <div class="flex justify-between text-zinc-600">
                <span>ISBN: {{ $borrowing->book->isbn }}</span>
                <span>Rak: {{ $borrowing->book->shelf_location }}</span>
            </div>
            <div class="flex justify-between pt-1 font-semibold text-zinc-900">
                <span>Jatuh Tempo:</span>
                <span class="text-orange-600">{{ $borrowing->return_due_date->format('d/m/Y') }}</span>
            </div>
        </div>

        <!-- Status & Denda -->
        <div class="border-b border-dashed border-zinc-300 pb-3 text-[11px] space-y-1">
            <div class="flex justify-between">
                <span class="text-zinc-500">Status Sirkulasi:</span>
                <span class="font-bold uppercase">{{ $borrowing->status }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-zinc-500">Perpanjangan:</span>
                <span>{{ $borrowing->renewed_count ?? 0 }}x diperpanjang</span>
            </div>
            <div class="flex justify-between">
                <span class="text-zinc-500">Denda Keterlambatan:</span>
                <span class="font-bold {{ $borrowing->fines > 0 ? 'text-red-600' : 'text-zinc-800' }}">
                    Rp {{ number_format($borrowing->fines, 0, ',', '.') }}
                </span>
            </div>
            @if($borrowing->fine_payment_status === 'paid')
                <div class="flex justify-between text-emerald-600 font-bold">
                    <span>Status Denda:</span>
                    <span>LUNAS DIBAYAR</span>
                </div>
            @endif
        </div>

        <!-- QR Code Verification -->
        <div class="text-center py-2 space-y-2">
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data={{ urlencode($borrowing->borrowing_code) }}" alt="QR Code" class="w-20 h-20 mx-auto">
            <p class="text-[9px] text-zinc-400">Scan QR Code ini di meja sirkulasi saat mengembalikan buku.</p>
        </div>

        <!-- Footer Notice -->
        <div class="text-center text-[9px] text-zinc-400 pt-2 border-t border-dashed border-zinc-300">
            <p>Terima kasih atas kunjungan Anda.</p>
            <p>Harap kembalikan buku tepat waktu sebelum tanggal jatuh tempo.</p>
        </div>
    </div>

</body>
</html>
