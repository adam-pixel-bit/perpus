<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kartu Anggota - {{ $user->name }} ({{ $user->member_code }})</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Instrument Sans', sans-serif; }
        .font-mono { font-family: 'JetBrains Mono', monospace; }
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; padding: 0 !important; }
            .print-card { box-shadow: none !important; border: 1px solid #18181b !important; page-break-inside: avoid; }
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
            <span>Cetak / Unduh Kartu PDF</span>
        </button>
        <button onclick="window.close()" class="px-4 py-2.5 bg-white border border-zinc-300 text-zinc-700 text-xs font-semibold rounded-xl hover:bg-zinc-50 transition cursor-pointer">
            Tutup
        </button>
    </div>

    <!-- Cards Container (Front & Back) -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-4xl w-full">
        <!-- FRONT OF CARD -->
        <div class="print-card w-full max-w-[420px] mx-auto aspect-[1.586/1] bg-gradient-to-br from-zinc-900 via-zinc-950 to-black text-white rounded-3xl p-6 shadow-2xl border border-zinc-800 flex flex-col justify-between relative overflow-hidden">
            <!-- Glow FX -->
            <div class="absolute -right-10 -bottom-10 w-44 h-44 bg-orange-600/20 rounded-full blur-2xl"></div>

            <!-- Top Row -->
            <div class="flex items-center justify-between pb-3 border-b border-zinc-800/80 relative z-10">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 rounded-xl bg-orange-500/20 border border-orange-500/30 flex items-center justify-center text-orange-500 font-bold text-xs">
                        S
                    </div>
                    <div>
                        <h2 class="font-bold text-xs tracking-wider uppercase text-white">SIPUS DIGITAL</h2>
                        <p class="text-[8px] font-mono text-zinc-400 tracking-widest uppercase">KARTU ANGGOTA PERPUSTAKAAN</p>
                    </div>
                </div>
                <span class="px-2 py-0.5 rounded-full text-[8px] font-mono font-bold bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">
                    STATUS: AKTIF
                </span>
            </div>

            <!-- Middle Row -->
            <div class="flex items-center space-x-4 my-auto relative z-10">
                <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-16 h-16 rounded-2xl object-cover border-2 border-zinc-700 shadow-md flex-shrink-0">
                <div class="min-w-0">
                    <span class="text-[9px] font-mono text-zinc-400 uppercase tracking-wider block">NAMA ANGGOTA</span>
                    <h3 class="text-base font-bold text-white truncate">{{ $user->name }}</h3>
                    <p class="text-xs font-mono font-bold text-orange-400 mt-0.5">{{ $user->member_code }}</p>
                    <p class="text-[10px] text-zinc-400 truncate">{{ $user->email }}</p>
                </div>
            </div>

            <!-- Bottom Row -->
            <div class="pt-3 border-t border-zinc-800/80 flex items-end justify-between relative z-10">
                <div>
                    <span class="text-[8px] font-mono text-zinc-500 block uppercase">BERLAKU SEJAK</span>
                    <span class="text-[11px] font-mono font-semibold text-zinc-300">{{ $user->created_at->format('d/m/Y') }}</span>
                </div>
                <div class="text-right">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=60x60&data={{ urlencode($user->member_code) }}" alt="QR ID" class="w-11 h-11 rounded-lg bg-white p-1 ml-auto shadow-xs">
                </div>
            </div>
        </div>

        <!-- BACK OF CARD -->
        <div class="print-card w-full max-w-[420px] mx-auto aspect-[1.586/1] bg-white text-zinc-900 rounded-3xl p-6 shadow-2xl border border-zinc-300 flex flex-col justify-between relative">
            <div>
                <h4 class="font-bold text-xs text-zinc-900 uppercase tracking-wider mb-2 border-b border-zinc-200 pb-1.5 flex items-center justify-between">
                    <span>Ketentuan Anggota</span>
                    <span class="text-[9px] font-mono text-zinc-400">SIPUS V2</span>
                </h4>
                <ol class="text-[9px] text-zinc-600 leading-relaxed space-y-1 list-decimal list-inside">
                    <li>Kartu ini adalah identitas resmi anggota perpustakaan.</li>
                    <li>Wajib dibawa & ditunjukkan saat peminjaman/pengembalian buku.</li>
                    <li>Maksimal peminjaman 3 judul buku bersamaan.</li>
                    <li>Denda keterlambatan berlaku Rp 1.000 / hari per buku.</li>
                    <li>Kehilangan kartu harap segera lapor ke meja sirkulasi.</li>
                </ol>
            </div>

            <div class="pt-3 border-t border-zinc-200 flex items-center justify-between text-[9px] text-zinc-500">
                <div>
                    <p class="font-bold text-zinc-800">Layanan Sirkulasi</p>
                    <p>admin@perpustakaan.com</p>
                </div>
                <div class="text-right">
                    <p class="font-mono text-[8px] text-zinc-400">SECURITY SEAL</p>
                    <p class="font-bold text-zinc-800">VERIFIED MEMBER</p>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
