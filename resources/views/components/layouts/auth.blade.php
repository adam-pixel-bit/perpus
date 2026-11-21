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
<body class="bg-[#F0F4FA] text-zinc-900 font-sans antialiased min-h-screen flex items-center justify-center p-3 sm:p-6 lg:p-8 relative overflow-x-hidden">

    <!-- Ambient Background Glows matching Reference UI -->
    <div class="fixed top-0 left-0 w-96 h-96 bg-blue-200/40 rounded-full blur-3xl pointer-events-none -translate-x-1/2 -translate-y-1/2"></div>
    <div class="fixed bottom-0 right-0 w-96 h-96 bg-rose-200/30 rounded-full blur-3xl pointer-events-none translate-x-1/3 translate-y-1/3"></div>

    <div class="w-full max-w-5xl mx-auto relative z-10">
        {{ $slot }}
    </div>

    <script>
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    </script>
</body>
</html>
