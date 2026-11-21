<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Sistem Informasi Perpustakaan' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#F4F5F7] text-zinc-900 font-sans antialiased min-h-screen flex items-center justify-center p-4 sm:p-6">
    <div class="w-full max-w-4xl mx-auto grid grid-cols-1 md:grid-cols-12 bg-white rounded-3xl shadow-xl shadow-zinc-200/50 border border-zinc-200/80 overflow-hidden">
        <!-- Left Branding Panel (Hidden on mobile) -->
        <div class="hidden md:flex md:col-span-5 bg-zinc-950 text-white p-8 lg:p-10 flex-col justify-between relative overflow-hidden">
            <div class="relative z-10 space-y-6">
                <!-- Logo & Brand Header -->
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-xl bg-white text-zinc-950 font-black text-xl flex items-center justify-center tracking-tighter">
                        w.
                    </div>
                    <div>
                        <h1 class="text-sm font-bold tracking-tight text-white leading-tight">Uxerflow Lib.</h1>
                        <p class="text-[11px] text-zinc-400">Library Operating System</p>
                    </div>
                </div>

                <div class="pt-6 space-y-3">
                    <div class="inline-flex items-center space-x-1.5 px-2.5 py-0.5 rounded-full bg-zinc-800 text-brand-orange text-[10px] font-semibold tracking-wide">
                        <span class="w-1.5 h-1.5 rounded-full bg-brand-orange"></span>
                        <span>Enterprise Cloud v2.4</span>
                    </div>
                    <h2 class="text-xl font-bold leading-snug text-white">Next-Generation Library Circulation & Catalog System</h2>
                    <p class="text-xs text-zinc-400 leading-relaxed">
                        Precision tracking of bibliographic resources, instant member reservations, automated overdue calculations, and Supabase PostgreSQL integration.
                    </p>
                </div>
            </div>

            <!-- Features -->
            <div class="relative z-10 pt-6 border-t border-zinc-800 space-y-2.5 text-xs text-zinc-300 font-medium">
                <div class="flex items-center space-x-2">
                    <i data-lucide="check" class="w-3.5 h-3.5 text-brand-orange"></i>
                    <span>Real-time availability & stack inventory</span>
                </div>
                <div class="flex items-center space-x-2">
                    <i data-lucide="check" class="w-3.5 h-3.5 text-brand-orange"></i>
                    <span>Role-Based Access Control (Admin & Member)</span>
                </div>
                <div class="flex items-center space-x-2">
                    <i data-lucide="check" class="w-3.5 h-3.5 text-brand-orange"></i>
                    <span>Reliable transactional state consistency</span>
                </div>
            </div>
        </div>

        <!-- Right Content Panel -->
        <div class="md:col-span-7 p-7 sm:p-10 flex flex-col justify-center bg-white">
            <!-- Flash Message Banner -->
            @if(session('success'))
                <div class="mb-4 p-3 rounded-xl bg-[#F0FDF4] border border-green-200 text-[#16A34A] text-xs flex items-center space-x-2">
                    <i data-lucide="check-circle" class="w-4 h-4 text-green-600 flex-shrink-0"></i>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 p-3 rounded-xl bg-[#FEECEC] border border-red-200 text-red-700 text-xs flex items-center space-x-2">
                    <i data-lucide="alert-circle" class="w-4 h-4 text-red-600 flex-shrink-0"></i>
                    <span class="font-medium">{{ session('error') }}</span>
                </div>
            @endif

            @if(session('info'))
                <div class="mb-4 p-3 rounded-xl bg-zinc-50 border border-zinc-200 text-zinc-700 text-xs flex items-center space-x-2">
                    <i data-lucide="info" class="w-4 h-4 text-zinc-500 flex-shrink-0"></i>
                    <span class="font-medium">{{ session('info') }}</span>
                </div>
            @endif

            {{ $slot }}
        </div>
    </div>

    <script>
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    </script>
</body>
</html>
