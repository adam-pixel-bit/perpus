<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Sistem Informasi Perpustakaan' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 text-slate-900 font-sans antialiased min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-5xl mx-auto grid grid-cols-1 lg:grid-cols-12 bg-white rounded-3xl shadow-xl border border-slate-200/80 overflow-hidden">
        <!-- Left Branding Panel (Hidden on mobile) -->
        <div class="lg:col-span-5 bg-gradient-to-br from-blue-700 via-indigo-800 to-slate-900 text-white p-8 lg:p-12 flex flex-col justify-between relative overflow-hidden">
            <div class="relative z-10 space-y-6">
                <div class="flex items-center space-x-3">
                    <div class="w-11 h-11 rounded-2xl bg-white/10 backdrop-blur-md border border-white/20 flex items-center justify-center text-white shadow-inner">
                        <i data-lucide="library" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold tracking-tight">SIPUS DIGITAL</h1>
                        <p class="text-xs text-blue-200">Sistem Informasi Perpustakaan</p>
                    </div>
                </div>

                <div class="pt-8 space-y-4">
                    <h2 class="text-2xl font-extrabold leading-snug">Akses Pengetahuan Tanpa Batas & Terintegrasi</h2>
                    <p class="text-sm text-blue-100/90 leading-relaxed">
                        Kelola katalog buku, peminjaman digital, pengembalian tepat waktu, dan laporan transaksi terintegrasi dengan database Supabase PostgreSQL.
                    </p>
                </div>
            </div>

            <!-- Features bullet points -->
            <div class="relative z-10 pt-8 border-t border-white/10 space-y-3 text-xs text-blue-100 font-medium">
                <div class="flex items-center space-x-2.5">
                    <i data-lucide="check-circle-2" class="w-4 h-4 text-emerald-400"></i>
                    <span>Katalog buku terupdate dengan pencarian instan</span>
                </div>
                <div class="flex items-center space-x-2.5">
                    <i data-lucide="check-circle-2" class="w-4 h-4 text-emerald-400"></i>
                    <span>Role-Based Access Control (Admin & User)</span>
                </div>
                <div class="flex items-center space-x-2.5">
                    <i data-lucide="check-circle-2" class="w-4 h-4 text-emerald-400"></i>
                    <span>Riwayat transaksi dan notifikasi transparan</span>
                </div>
            </div>

            <!-- Background subtle pattern decoration -->
            <div class="absolute -right-16 -bottom-16 w-64 h-64 bg-blue-500/20 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -left-16 -top-16 w-64 h-64 bg-indigo-500/20 rounded-full blur-3xl pointer-events-none"></div>
        </div>

        <!-- Right Content Panel -->
        <div class="lg:col-span-7 p-8 lg:p-12 flex flex-col justify-center bg-white">
            <!-- Flash Message Banner -->
            @if(session('success'))
                <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs flex items-center space-x-2.5">
                    <i data-lucide="check-circle-2" class="w-4 h-4 text-emerald-600 flex-shrink-0"></i>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs flex items-center space-x-2.5">
                    <i data-lucide="alert-circle" class="w-4 h-4 text-rose-600 flex-shrink-0"></i>
                    <span class="font-medium">{{ session('error') }}</span>
                </div>
            @endif

            @if(session('info'))
                <div class="mb-6 p-4 rounded-xl bg-blue-50 border border-blue-200 text-blue-800 text-xs flex items-center space-x-2.5">
                    <i data-lucide="info" class="w-4 h-4 text-blue-600 flex-shrink-0"></i>
                    <span class="font-medium">{{ session('info') }}</span>
                </div>
            @endif

            {{ $slot }}
        </div>
    </div>
</body>
</html>
