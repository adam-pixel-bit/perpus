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
<body class="bg-[#F4F5F7] text-zinc-900 font-sans antialiased min-h-screen p-0 sm:p-4 lg:p-6 flex flex-col justify-center">

    <!-- Outermost Window Container -->
    <div class="w-full max-w-[1520px] mx-auto bg-white rounded-none sm:rounded-3xl border-0 sm:border border-zinc-200/80 shadow-none sm:shadow-xl sm:shadow-zinc-200/40 overflow-hidden flex flex-col min-h-screen sm:min-h-[92vh]">
        <div class="flex-1 flex overflow-hidden">

            <!-- SIDEBAR (Drawer on sm/md, static on lg) -->
            <aside id="sidebar" class="w-64 sm:w-72 lg:w-64 bg-white border-r border-zinc-200/80 flex flex-col justify-between shrink-0 transition-transform duration-200 lg:translate-x-0 -translate-x-full fixed lg:static z-50 h-full shadow-2xl lg:shadow-none">
                <div class="overflow-y-auto">
                    <!-- Brand -->
                    <div class="p-4 flex items-center justify-between border-b border-zinc-100">
                        <div class="flex items-center space-x-2.5">
                            <div class="w-9 h-9 rounded-xl bg-zinc-950 text-white font-black text-lg flex items-center justify-center tracking-tighter shadow-xs">
                                P
                            </div>
                            <div class="leading-tight">
                                <h2 class="text-xs font-bold text-zinc-900 tracking-tight flex items-center space-x-1">
                                    <span>SIPUS Digital</span>
                                </h2>
                                <span class="text-[10px] text-zinc-400 font-medium">Sistem Perpustakaan</span>
                            </div>
                        </div>
                        <button type="button" onclick="toggleSidebar()" class="lg:hidden text-zinc-400 hover:text-zinc-600 p-1" title="Tutup Menu">
                            <i data-lucide="chevrons-left" class="w-4 h-4"></i>
                        </button>
                    </div>

                    <!-- Functional Search Bar -->
                    <div class="p-3">
                        <form method="GET" action="{{ auth()->user()?->isAdmin() ? route('admin.books.index') : route('user.catalog.index') }}">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-zinc-400">
                                    <i data-lucide="search" class="w-3.5 h-3.5"></i>
                                </div>
                                <input
                                    type="text"
                                    name="search"
                                    value="{{ request('search') }}"
                                    placeholder="Cari judul, ISBN..."
                                    class="w-full pl-8 pr-12 py-1.5 text-xs bg-zinc-50 border border-zinc-200/80 rounded-xl focus:outline-none focus:ring-1 focus:ring-orange-500 focus:bg-white text-zinc-800 placeholder-zinc-400"
                                >
                                <button type="submit" class="absolute inset-y-0 right-0 pr-2.5 flex items-center text-[10px] text-zinc-400 hover:text-orange-600 font-semibold cursor-pointer">
                                    Cari
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Navigation Sections -->
                    <nav class="px-3 py-2 space-y-5 text-xs font-medium text-zinc-600">
                        <!-- MENU UTAMA -->
                        <div class="space-y-0.5">
                            <span class="px-3 text-[10px] font-bold text-zinc-400 uppercase tracking-wider block mb-1.5">MENU UTAMA</span>
                            
                            @if(auth()->user()?->isAdmin())
                                <!-- Dashboard Admin -->
                                <a href="{{ route('admin.dashboard') }}" class="group relative flex items-center justify-between px-3 py-2 rounded-xl transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-zinc-100/80 text-zinc-950 font-bold' : 'hover:bg-zinc-50 hover:text-zinc-900' }}">
                                    @if(request()->routeIs('admin.dashboard'))
                                        <span class="absolute -left-3 top-1.5 bottom-1.5 w-1 bg-orange-600 rounded-r-md"></span>
                                    @endif
                                    <div class="flex items-center space-x-2.5">
                                        <i data-lucide="layout-grid" class="w-4 h-4 {{ request()->routeIs('admin.dashboard') ? 'text-zinc-950' : 'text-zinc-400 group-hover:text-zinc-600' }}"></i>
                                        <span>Dashboard</span>
                                    </div>
                                </a>

                                <!-- Katalog Buku -->
                                <a href="{{ route('admin.books.index') }}" class="group relative flex items-center justify-between px-3 py-2 rounded-xl transition-colors {{ request()->routeIs('admin.books.*') ? 'bg-zinc-100/80 text-zinc-950 font-bold' : 'hover:bg-zinc-50 hover:text-zinc-900' }}">
                                    @if(request()->routeIs('admin.books.*'))
                                        <span class="absolute -left-3 top-1.5 bottom-1.5 w-1 bg-orange-600 rounded-r-md"></span>
                                    @endif
                                    <div class="flex items-center space-x-2.5">
                                        <i data-lucide="box" class="w-4 h-4 {{ request()->routeIs('admin.books.*') ? 'text-zinc-950' : 'text-zinc-400 group-hover:text-zinc-600' }}"></i>
                                        <span>Katalog Buku</span>
                                    </div>
                                    <span class="px-1.5 py-0.5 rounded-full text-[10px] font-semibold bg-zinc-100 text-zinc-600">
                                        {{ \App\Models\Book::count() }}
                                    </span>
                                </a>

                                <!-- Sirkulasi Peminjaman -->
                                <a href="{{ route('admin.borrowings.index') }}" class="group relative flex items-center justify-between px-3 py-2 rounded-xl transition-colors {{ request()->routeIs('admin.borrowings.*') ? 'bg-zinc-100/80 text-zinc-950 font-bold' : 'hover:bg-zinc-50 hover:text-zinc-900' }}">
                                    @if(request()->routeIs('admin.borrowings.*'))
                                        <span class="absolute -left-3 top-1.5 bottom-1.5 w-1 bg-orange-600 rounded-r-md"></span>
                                    @endif
                                    <div class="flex items-center space-x-2.5">
                                        <i data-lucide="shopping-bag" class="w-4 h-4 {{ request()->routeIs('admin.borrowings.*') ? 'text-zinc-950' : 'text-zinc-400 group-hover:text-zinc-600' }}"></i>
                                        <span>Sirkulasi Pinjam</span>
                                    </div>
                                    @php
                                        $pendingCountBadge = \App\Models\Borrowing::where('status', 'pending')->count();
                                    @endphp
                                    @if($pendingCountBadge > 0)
                                        <span class="px-1.5 py-0.5 rounded-full text-[10px] font-bold bg-orange-100 text-orange-700 animate-pulse">
                                            {{ $pendingCountBadge }}
                                        </span>
                                    @endif
                                </a>

                                <!-- Scan QR / Barcode -->
                                <a href="{{ route('admin.scanner.index') }}" class="group relative flex items-center justify-between px-3 py-2 rounded-xl transition-colors {{ request()->routeIs('admin.scanner.*') ? 'bg-zinc-100/80 text-zinc-950 font-bold' : 'hover:bg-zinc-50 hover:text-zinc-900' }}">
                                    @if(request()->routeIs('admin.scanner.*'))
                                        <span class="absolute -left-3 top-1.5 bottom-1.5 w-1 bg-orange-600 rounded-r-md"></span>
                                    @endif
                                    <div class="flex items-center space-x-2.5">
                                        <i data-lucide="qr-code" class="w-4 h-4 {{ request()->routeIs('admin.scanner.*') ? 'text-zinc-950' : 'text-zinc-400 group-hover:text-zinc-600' }}"></i>
                                        <span>Scan QR / Barcode</span>
                                    </div>
                                </a>

                                <!-- Data Anggota -->
                                <a href="{{ route('admin.users.index') }}" class="group relative flex items-center justify-between px-3 py-2 rounded-xl transition-colors {{ request()->routeIs('admin.users.*') ? 'bg-zinc-100/80 text-zinc-950 font-bold' : 'hover:bg-zinc-50 hover:text-zinc-900' }}">
                                    @if(request()->routeIs('admin.users.*'))
                                        <span class="absolute -left-3 top-1.5 bottom-1.5 w-1 bg-orange-600 rounded-r-md"></span>
                                    @endif
                                    <div class="flex items-center space-x-2.5">
                                        <i data-lucide="users-round" class="w-4 h-4 {{ request()->routeIs('admin.users.*') ? 'text-zinc-950' : 'text-zinc-400 group-hover:text-zinc-600' }}"></i>
                                        <span>Data Anggota</span>
                                    </div>
                                    <span class="px-1.5 py-0.5 rounded-full text-[10px] font-semibold bg-zinc-100 text-zinc-600">
                                        {{ \App\Models\User::where('role', 'user')->count() }}
                                    </span>
                                </a>

                                <!-- Kategori Buku -->
                                <a href="{{ route('admin.categories.index') }}" class="group relative flex items-center justify-between px-3 py-2 rounded-xl transition-colors {{ request()->routeIs('admin.categories.*') ? 'bg-zinc-100/80 text-zinc-950 font-bold' : 'hover:bg-zinc-50 hover:text-zinc-900' }}">
                                    @if(request()->routeIs('admin.categories.*'))
                                        <span class="absolute -left-3 top-1.5 bottom-1.5 w-1 bg-orange-600 rounded-r-md"></span>
                                    @endif
                                    <div class="flex items-center space-x-2.5">
                                        <i data-lucide="tags" class="w-4 h-4 {{ request()->routeIs('admin.categories.*') ? 'text-zinc-950' : 'text-zinc-400 group-hover:text-zinc-600' }}"></i>
                                        <span>Kategori Buku</span>
                                    </div>
                                    <span class="px-1.5 py-0.5 rounded-full text-[10px] font-semibold bg-zinc-100 text-zinc-600">
                                        {{ \App\Models\Category::count() }}
                                    </span>
                                </a>
                            @else
                                <!-- MENU ANGGOTA / USER -->
                                <a href="{{ route('user.dashboard') }}" class="group relative flex items-center justify-between px-3 py-2 rounded-xl transition-colors {{ request()->routeIs('user.dashboard') ? 'bg-zinc-100/80 text-zinc-950 font-bold' : 'hover:bg-zinc-50 hover:text-zinc-900' }}">
                                    @if(request()->routeIs('user.dashboard'))
                                        <span class="absolute -left-3 top-1.5 bottom-1.5 w-1 bg-orange-600 rounded-r-md"></span>
                                    @endif
                                    <div class="flex items-center space-x-2.5">
                                        <i data-lucide="layout-grid" class="w-4 h-4 {{ request()->routeIs('user.dashboard') ? 'text-zinc-950' : 'text-zinc-400' }}"></i>
                                        <span>Dashboard Anggota</span>
                                    </div>
                                </a>

                                <a href="{{ route('user.catalog.index') }}" class="group relative flex items-center justify-between px-3 py-2 rounded-xl transition-colors {{ request()->routeIs('user.catalog.*') ? 'bg-zinc-100/80 text-zinc-950 font-bold' : 'hover:bg-zinc-50 hover:text-zinc-900' }}">
                                    @if(request()->routeIs('user.catalog.*'))
                                        <span class="absolute -left-3 top-1.5 bottom-1.5 w-1 bg-orange-600 rounded-r-md"></span>
                                    @endif
                                    <div class="flex items-center space-x-2.5">
                                        <i data-lucide="book-open" class="w-4 h-4 {{ request()->routeIs('user.catalog.*') ? 'text-zinc-950' : 'text-zinc-400' }}"></i>
                                        <span>Katalog Koleksi</span>
                                    </div>
                                    <span class="px-1.5 py-0.5 rounded-full text-[10px] font-semibold bg-zinc-100 text-zinc-600">
                                        {{ \App\Models\Book::where('is_available', true)->count() }}
                                    </span>
                                </a>

                                <a href="{{ route('user.borrowings.index') }}" class="group relative flex items-center justify-between px-3 py-2 rounded-xl transition-colors {{ request()->routeIs('user.borrowings.*') ? 'bg-zinc-100/80 text-zinc-950 font-bold' : 'hover:bg-zinc-50 hover:text-zinc-900' }}">
                                    @if(request()->routeIs('user.borrowings.*'))
                                        <span class="absolute -left-3 top-1.5 bottom-1.5 w-1 bg-orange-600 rounded-r-md"></span>
                                    @endif
                                    <div class="flex items-center space-x-2.5">
                                        <i data-lucide="history" class="w-4 h-4 {{ request()->routeIs('user.borrowings.*') ? 'text-zinc-950' : 'text-zinc-400' }}"></i>
                                        <span>Riwayat Pinjam</span>
                                    </div>
                                </a>
                            @endif
                        </div>

                        <!-- LAPORAN & KONTROL (HANYA UNTUK ADMIN) -->
                        @if(auth()->user()?->isAdmin())
                            <div class="space-y-0.5">
                                <span class="px-3 text-[10px] font-bold text-zinc-400 uppercase tracking-wider block mb-1.5">LAPORAN & ANALITIK</span>
                                
                                <a href="{{ route('admin.reports.index') }}" class="group relative flex items-center justify-between px-3 py-2 rounded-xl transition-colors {{ request()->routeIs('admin.reports.index') ? 'bg-zinc-100/80 text-zinc-950 font-bold' : 'hover:bg-zinc-50 hover:text-zinc-900' }}">
                                    @if(request()->routeIs('admin.reports.index'))
                                        <span class="absolute -left-3 top-1.5 bottom-1.5 w-1 bg-orange-600 rounded-r-md"></span>
                                    @endif
                                    <div class="flex items-center space-x-2.5">
                                        <i data-lucide="bar-chart-2" class="w-4 h-4 {{ request()->routeIs('admin.reports.index') ? 'text-zinc-950' : 'text-zinc-400 group-hover:text-zinc-600' }}"></i>
                                        <span>Laporan & Statistik</span>
                                    </div>
                                </a>

                                <a href="{{ route('admin.reports.export') }}" class="group flex items-center space-x-2.5 px-3 py-2 rounded-xl text-zinc-600 hover:bg-zinc-50 hover:text-zinc-900 transition-colors">
                                    <i data-lucide="download" class="w-4 h-4 text-zinc-400 group-hover:text-zinc-600"></i>
                                    <span>Unduh Laporan CSV</span>
                                </a>

                                <!-- Audit Log -->
                                <a href="{{ route('admin.audit-logs.index') }}" class="group relative flex items-center justify-between px-3 py-2 rounded-xl transition-colors {{ request()->routeIs('admin.audit-logs.*') ? 'bg-zinc-100/80 text-zinc-950 font-bold' : 'hover:bg-zinc-50 hover:text-zinc-900' }}">
                                    @if(request()->routeIs('admin.audit-logs.*'))
                                        <span class="absolute -left-3 top-1.5 bottom-1.5 w-1 bg-orange-600 rounded-r-md"></span>
                                    @endif
                                    <div class="flex items-center space-x-2.5">
                                        <i data-lucide="shield-alert" class="w-4 h-4 {{ request()->routeIs('admin.audit-logs.*') ? 'text-zinc-950' : 'text-zinc-400 group-hover:text-zinc-600' }}"></i>
                                        <span>Audit Log</span>
                                    </div>
                                    <span class="px-1.5 py-0.5 rounded-full text-[9px] font-semibold bg-zinc-200 text-zinc-700">Security</span>
                                </a>
                            </div>
                        @endif
                    </nav>
                </div>

                <!-- Sidebar Bottom: Akun & Logout -->
                <div class="p-4 border-t border-zinc-100">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2.5 min-w-0">
                            <div class="w-8 h-8 rounded-full bg-zinc-950 text-white font-bold flex items-center justify-center text-xs shadow-xs">
                                {{ strtoupper(substr(auth()->user()?->name ?? 'U', 0, 1)) }}
                            </div>
                            <div class="min-w-0">
                                <p class="text-xs font-bold text-zinc-900 truncate max-w-[120px]">{{ auth()->user()?->name ?? 'Tamu' }}</p>
                                <p class="text-[10px] text-zinc-400 font-medium">
                                    {{ auth()->user()?->isAdmin() ? 'Administrator' : 'Anggota Perpus' }}
                                </p>
                            </div>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" title="Keluar / Logout" class="p-2 text-zinc-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition-colors cursor-pointer">
                                <i data-lucide="log-out" class="w-4 h-4"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </aside>

            <!-- MAIN VIEW CONTAINER -->
            <div class="flex-1 flex flex-col min-w-0 overflow-y-auto bg-white">
                <!-- Top Header Navbar -->
                <header class="h-14 sm:h-16 px-3.5 sm:px-6 lg:px-8 border-b border-zinc-100 flex items-center justify-between shrink-0 relative">
                    <div class="flex items-center space-x-2.5 sm:space-x-3 min-w-0">
                        <button type="button" onclick="toggleSidebar()" class="lg:hidden p-1.5 text-zinc-600 hover:bg-zinc-100 rounded-lg shrink-0 cursor-pointer" title="Buka Menu">
                            <i data-lucide="menu" class="w-5 h-5"></i>
                        </button>
                        <h1 class="text-base sm:text-lg lg:text-xl font-bold text-zinc-950 tracking-tight truncate">{{ $pageTitle ?? 'Katalog Buku' }}</h1>
                    </div>

                    <!-- Top Right Utilities -->
                    <div class="flex items-center space-x-2 sm:space-x-3 shrink-0">
                        @php
                            $unreadNotifications = auth()->check() ? \App\Models\Notification::where('user_id', auth()->id())->latest()->take(6)->get() : collect();
                            $unreadCount = auth()->check() ? \App\Models\Notification::where('user_id', auth()->id())->where('is_read', false)->count() : 0;
                        @endphp

                        <!-- Notification Bell Dropdown Button -->
                        <div class="relative">
                            <button
                                type="button"
                                onclick="toggleNotificationDropdown()"
                                class="relative p-2 text-zinc-500 hover:text-zinc-800 hover:bg-zinc-50 rounded-xl border border-zinc-200/80 shadow-2xs transition-colors cursor-pointer"
                                title="Notifikasi Sistem"
                            >
                                <i data-lucide="bell" class="w-4 h-4"></i>
                                @if($unreadCount > 0)
                                    <span class="absolute -top-1 -right-1 w-4 h-4 bg-orange-600 text-white text-[10px] font-bold rounded-full flex items-center justify-center animate-pulse">
                                        {{ $unreadCount }}
                                    </span>
                                @endif
                            </button>

                            <!-- Dropdown Menu -->
                            <div id="notification-dropdown" class="hidden absolute right-0 mt-2 w-80 sm:w-96 bg-white border border-zinc-200 rounded-2xl shadow-xl z-50 overflow-hidden animate-in fade-in duration-150">
                                <div class="p-3.5 border-b border-zinc-100 flex items-center justify-between bg-zinc-50/70">
                                    <div class="flex items-center space-x-2">
                                        <span class="text-xs font-bold text-zinc-900">Notifikasi</span>
                                        @if($unreadCount > 0)
                                            <span class="px-1.5 py-0.2 bg-orange-100 text-orange-700 text-[10px] font-bold rounded-full">
                                                {{ $unreadCount }} baru
                                            </span>
                                        @endif
                                    </div>
                                    @if($unreadCount > 0)
                                        <form method="POST" action="{{ route('notifications.readAll') }}">
                                            @csrf
                                            <button type="submit" class="text-[11px] font-semibold text-orange-600 hover:text-orange-700 cursor-pointer">
                                                Tandai semua dibaca
                                            </button>
                                        </form>
                                    @endif
                                </div>

                                <div class="max-h-72 overflow-y-auto divide-y divide-zinc-100 text-xs">
                                    @forelse($unreadNotifications as $notif)
                                        <div class="p-3 hover:bg-zinc-50/80 transition-colors {{ !$notif->is_read ? 'bg-orange-50/30' : '' }}">
                                            <div class="flex items-start justify-between gap-2">
                                                <div class="flex-1">
                                                    <p class="font-bold text-zinc-900 text-xs">{{ $notif->title }}</p>
                                                    <p class="text-[11px] text-zinc-500 mt-0.5 leading-relaxed">{{ $notif->message }}</p>
                                                    <span class="text-[10px] text-zinc-400 mt-1 block">{{ $notif->created_at->diffForHumans() }}</span>
                                                </div>
                                                @if(!$notif->is_read)
                                                    <form method="POST" action="{{ route('notifications.read', $notif->id) }}">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" title="Tandai dibaca" class="text-zinc-400 hover:text-orange-600 p-1 cursor-pointer">
                                                            <i data-lucide="check" class="w-3.5 h-3.5"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                    @empty
                                        <div class="py-8 text-center text-zinc-400 text-xs">
                                            Tidak ada notifikasi saat ini.
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        <!-- Quick Context Action Button -->
                        @if(auth()->user()?->isAdmin())
                            <a
                                href="{{ route('admin.books.create') }}"
                                class="inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-xl bg-zinc-950 hover:bg-black text-white text-xs font-bold transition-all shadow-md shadow-zinc-950/10 active:scale-[0.98]"
                            >
                                <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                                <span class="hidden sm:inline">Tambah Buku Baru</span>
                                <span class="sm:hidden">Tambah</span>
                            </a>
                        @else
                            <a
                                href="{{ route('user.catalog.index') }}"
                                class="inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-xl bg-zinc-950 hover:bg-black text-white text-xs font-bold transition-all shadow-md shadow-zinc-950/10 active:scale-[0.98]"
                            >
                                <i data-lucide="book-open" class="w-3.5 h-3.5"></i>
                                <span class="hidden sm:inline">Katalog Buku</span>
                                <span class="sm:hidden">Katalog</span>
                            </a>
                        @endif
                    </div>
                </header>

                <!-- Flash Notifications -->
                @if(session('success'))
                    <div class="mx-6 sm:mx-8 mt-4 p-3 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <i data-lucide="check-circle" class="w-4 h-4 text-emerald-600"></i>
                            <span class="font-medium">{{ session('success') }}</span>
                        </div>
                        <button type="button" onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-800">
                            <i data-lucide="x" class="w-3.5 h-3.5"></i>
                        </button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mx-6 sm:mx-8 mt-4 p-3 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <i data-lucide="alert-circle" class="w-4 h-4 text-rose-600"></i>
                            <span class="font-medium">{{ session('error') }}</span>
                        </div>
                        <button type="button" onclick="this.parentElement.remove()" class="text-rose-600 hover:text-rose-800">
                            <i data-lucide="x" class="w-3.5 h-3.5"></i>
                        </button>
                    </div>
                @endif

                <!-- Page Content Slot -->
                <main class="flex-1 p-3.5 sm:p-6 lg:p-8 space-y-4 sm:space-y-6 overflow-x-hidden">
                    {{ $slot }}
                </main>
            </div>
        </div>
    </div>

    <!-- Mobile Drawer Backdrop -->
    <div id="sidebar-backdrop" onclick="toggleSidebar()" class="fixed inset-0 z-40 bg-zinc-950/50 backdrop-blur-xs hidden lg:hidden"></div>

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

        function toggleSidebar() {
            const sb = document.getElementById('sidebar');
            const bd = document.getElementById('sidebar-backdrop');
            sb.classList.toggle('-translate-x-full');
            bd.classList.toggle('hidden');
        }

        // Toggle KPI statistics container
        function toggleStatistics(checkbox) {
            const container = document.getElementById('statistics-container') || document.getElementById('stats-strip');
            if (container) {
                if (checkbox.checked) {
                    container.classList.remove('hidden');
                } else {
                    container.classList.add('hidden');
                }
            }
        }

        // Notification dropdown toggle
        function toggleNotificationDropdown() {
            const dd = document.getElementById('notification-dropdown');
            if (dd) {
                dd.classList.toggle('hidden');
            }
        }

        // Close dropdown when clicked outside
        document.addEventListener('click', function(event) {
            const dd = document.getElementById('notification-dropdown');
            const btn = event.target.closest('button[onclick="toggleNotificationDropdown()"]');
            if (dd && !dd.contains(event.target) && !btn) {
                dd.classList.add('hidden');
            }
        });
    </script>
</body>
</html>
