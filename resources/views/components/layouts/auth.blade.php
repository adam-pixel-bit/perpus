<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Masuk — Sistem Informasi Perpustakaan' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#F4F5F7] text-zinc-900 font-sans antialiased min-h-screen p-0 sm:p-4 lg:p-6 flex flex-col justify-center">

    <!-- macOS / Browser Window Frame Container (Exact match to Uxerflow Reference UI) -->
    <div class="w-full max-w-[1520px] mx-auto bg-white rounded-none sm:rounded-3xl border-0 sm:border border-zinc-200/80 shadow-none sm:shadow-xl sm:shadow-zinc-200/40 overflow-hidden flex flex-col min-h-screen sm:min-h-[92vh]">
        
        <!-- Browser Chrome Window Topbar -->
        <div class="h-9 sm:h-10 px-3 sm:px-4 border-b border-zinc-200/80 bg-zinc-50/80 flex items-center justify-between shrink-0 select-none">
            <!-- Left: macOS Traffic Light Buttons + History Controls -->
            <div class="flex items-center space-x-3 sm:space-x-4">
                <div class="flex items-center space-x-1.5 sm:space-x-2">
                    <span class="w-2.5 h-2.5 sm:w-3 sm:h-3 rounded-full bg-[#FF5F56] border border-[#E0443E] inline-block"></span>
                    <span class="w-2.5 h-2.5 sm:w-3 sm:h-3 rounded-full bg-[#FFBD2E] border border-[#DEA123] inline-block"></span>
                    <span class="w-2.5 h-2.5 sm:w-3 sm:h-3 rounded-full bg-[#27C93F] border border-[#1AAB29] inline-block"></span>
                </div>
                <div class="hidden sm:flex items-center space-x-2 text-zinc-400">
                    <i data-lucide="chevron-left" class="w-3.5 h-3.5 cursor-pointer hover:text-zinc-600"></i>
                    <i data-lucide="chevron-right" class="w-3.5 h-3.5 cursor-pointer hover:text-zinc-600"></i>
                    <i data-lucide="rotate-cw" class="w-3 h-3 cursor-pointer hover:text-zinc-600"></i>
                </div>
            </div>

            <!-- Center: URL Omnibox -->
            <div class="flex items-center space-x-1.5 px-2.5 sm:px-3 py-0.5 sm:py-1 bg-white border border-zinc-200/80 rounded-lg text-[10px] sm:text-[11px] font-mono text-zinc-500 shadow-2xs w-44 sm:w-72 md:w-80 justify-center">
                <i data-lucide="shield-check" class="w-3.5 h-3.5 text-emerald-600 shrink-0"></i>
                <span class="truncate">sipus.perpus.id/{{ request()->path() }}</span>
            </div>

            <!-- Right: Workspace Status -->
            <div class="flex items-center space-x-1.5 sm:space-x-2 text-zinc-400 shrink-0">
                <span class="text-[9px] sm:text-[10px] font-semibold text-zinc-500 uppercase tracking-wider hidden sm:inline">SIPUS Cloud Security</span>
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
            </div>
        </div>

        <!-- Window Inner Workspace Content -->
        <div class="flex-1 flex flex-col overflow-y-auto bg-white">
            {{ $slot }}
        </div>
    </div>

    <script>
        // Universal image error fallback to ensure no broken images appear anywhere in the app
        const FALLBACK_BOOK_COVER = 'data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="120" height="160" viewBox="0 0 120 160" fill="%23f3f4f6"><rect width="120" height="160" rx="6" fill="%23f4f5f7"/><path d="M38 55h44M38 72h32M38 89h40" stroke="%239ca3af" stroke-width="3" stroke-linecap="round"/><rect x="26" y="32" width="68" height="96" rx="6" fill="none" stroke="%23cbd5e1" stroke-width="2.5"/><text x="60" y="145" font-family="sans-serif" font-size="9" fill="%2394a3b8" text-anchor="middle" font-weight="bold">BUKU</text></svg>';

        document.addEventListener('error', function (e) {
            if (e.target && e.target.tagName && e.target.tagName.toLowerCase() === 'img') {
                if (!e.target.dataset.triedFallback) {
                    e.target.dataset.triedFallback = 'true';
                    e.target.src = FALLBACK_BOOK_COVER;
                }
            }
        }, true);

        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    </script>
</body>
</html>
