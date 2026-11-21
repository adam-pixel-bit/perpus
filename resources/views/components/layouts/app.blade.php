<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Dashboard' }} — SIPUS DIGITAL</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 text-slate-900 font-sans antialiased min-h-screen flex flex-col selection:bg-blue-600 selection:text-white">
    <div class="flex-1 flex overflow-hidden">
        <!-- Sidebar Navigation -->
        <aside id="sidebar" class="fixed inset-y-0 left-0 z-40 w-64 bg-slate-900 text-slate-300 transform -translate-x-full lg:translate-x-0 lg:static lg:inset-0 transition-transform duration-200 ease-in-out flex flex-col justify-between border-r border-slate-800 shadow-xl lg:shadow-none">
            <div>
                <!-- Brand Header -->
                <div class="h-16 flex items-center px-6 border-b border-slate-800 bg-slate-950/50">
                    <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('user.dashboard') }}" class="flex items-center space-x-3">
                        <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-blue-600 to-indigo-500 flex items-center justify-center text-white shadow-md shadow-blue-500/20">
                            <i data-lucide="library" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <span class="font-extrabold text-base tracking-tight text-white block">SIPUS DIGITAL</span>
                            <span class="text-[10px] uppercase font-bold tracking-wider text-blue-400 block -mt-1">
                                {{ auth()->user()->isAdmin() ? 'Administrator' : 'Portal Anggota' }}
                            </span>
                        </div>
                    </a>
                </div>

                <!-- Navigation Links -->
                <nav class="p-4 space-y-1.5 overflow-y-auto max-h-[calc(100vh-10rem)]">
                    @if(auth()->user()->isAdmin())
                        <div class="px-3 pt-2 pb-1 text-[11px] font-bold uppercase tracking-wider text-slate-300">Utama</div>
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/30' : 'hover:bg-slate-800 hover:text-white text-slate-300' }}">
                            <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
                            <span>Dashboard</span>
                        </a>

                        <div class="px-3 pt-4 pb-1 text-[11px] font-bold uppercase tracking-wider text-slate-300">Katalog & Koleksi</div>
                        <a href="{{ route('admin.books.index') }}" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('admin.books.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/30' : 'hover:bg-slate-800 hover:text-white text-slate-300' }}">
                            <i data-lucide="book-open" class="w-4 h-4"></i>
                            <span>Manajemen Buku</span>
                        </a>
                        <a href="{{ route('admin.categories.index') }}" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('admin.categories.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/30' : 'hover:bg-slate-800 hover:text-white text-slate-300' }}">
                            <i data-lucide="folder-kanban" class="w-4 h-4"></i>
                            <span>Kategori Buku</span>
                        </a>

                        <div class="px-3 pt-4 pb-1 text-[11px] font-bold uppercase tracking-wider text-slate-300">Sirkulasi & Anggota</div>
                        <a href="{{ route('admin.borrowings.index') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('admin.borrowings.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/30' : 'hover:bg-slate-800 hover:text-white text-slate-300' }}">
                            <div class="flex items-center space-x-3">
                                <i data-lucide="arrow-left-right" class="w-4 h-4"></i>
                                <span>Peminjaman</span>
                            </div>
                            @php
                                $pendingBorrowingsBadge = \App\Models\Borrowing::where('status', 'pending')->count();
                            @endphp
                            @if($pendingBorrowingsBadge > 0)
                                <span class="px-2 py-0.5 text-xs font-bold rounded-full bg-amber-500 text-slate-950">
                                    {{ $pendingBorrowingsBadge }}
                                </span>
                            @endif
                        </a>
                        <a href="{{ route('admin.users.index') }}" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('admin.users.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/30' : 'hover:bg-slate-800 hover:text-white text-slate-300' }}">
                            <i data-lucide="users" class="w-4 h-4"></i>
                            <span>Kelola Pengguna</span>
                        </a>

                        <div class="px-3 pt-4 pb-1 text-[11px] font-bold uppercase tracking-wider text-slate-300">Analitik</div>
                        <a href="{{ route('admin.reports.index') }}" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('admin.reports.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/30' : 'hover:bg-slate-800 hover:text-white text-slate-300' }}">
                            <i data-lucide="bar-chart-3" class="w-4 h-4"></i>
                            <span>Laporan Sistem</span>
                        </a>
                    @else
                        <!-- USER MENU -->
                        <div class="px-3 pt-2 pb-1 text-[11px] font-bold uppercase tracking-wider text-slate-300">Menu Anggota</div>
                        <a href="{{ route('user.dashboard') }}" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('user.dashboard') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/30' : 'hover:bg-slate-800 hover:text-white text-slate-300' }}">
                            <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
                            <span>Dashboard</span>
                        </a>
                        <a href="{{ route('user.catalog.index') }}" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('user.catalog.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/30' : 'hover:bg-slate-800 hover:text-white text-slate-300' }}">
                            <i data-lucide="book-open" class="w-4 h-4"></i>
                            <span>Katalog Buku</span>
                        </a>
                        <a href="{{ route('user.borrowings.index') }}" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('user.borrowings.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/30' : 'hover:bg-slate-800 hover:text-white text-slate-300' }}">
                            <i data-lucide="history" class="w-4 h-4"></i>
                            <span>Riwayat Pinjam</span>
                        </a>
                    @endif
                </nav>
            </div>

            <!-- User Footer in Sidebar -->
            <div class="p-4 border-t border-slate-800 bg-slate-950/40">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-full bg-blue-600/20 text-blue-400 border border-blue-500/30 font-bold flex items-center justify-center text-sm">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-bold text-white truncate">{{ auth()->user()->name }}</p>
                        <p class="text-[11px] text-slate-400 truncate">{{ auth()->user()->email }}</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" title="Logout" class="p-2 text-slate-400 hover:text-rose-400 hover:bg-slate-800 rounded-lg transition-colors">
                            <i data-lucide="log-out" class="w-4 h-4"></i>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Backdrop for mobile drawer -->
        <div id="sidebar-backdrop" onclick="toggleSidebar()" class="fixed inset-0 z-30 bg-slate-900/50 backdrop-blur-xs hidden lg:hidden"></div>

        <!-- Main Wrapper -->
        <div class="flex-1 flex flex-col min-w-0 overflow-y-auto">
            <!-- Navbar Header -->
            <header class="sticky top-0 z-20 h-16 bg-white/90 backdrop-blur-md border-b border-slate-200/80 px-4 sm:px-6 lg:px-8 flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <!-- Mobile Hamburger -->
                    <button type="button" onclick="toggleSidebar()" class="lg:hidden p-2 rounded-xl text-slate-600 hover:bg-slate-100 focus:outline-none">
                        <i data-lucide="menu" class="w-5 h-5"></i>
                    </button>
                    <div>
                        <h2 class="text-lg font-bold text-slate-900 leading-tight">{{ $header ?? 'Dashboard' }}</h2>
                        <p class="text-xs text-slate-500 hidden sm:block">{{ $subheader ?? 'Sistem Informasi Perpustakaan Digital' }}</p>
                    </div>
                </div>

                <!-- Right Toolbar Actions -->
                <div class="flex items-center space-x-3">
                    <!-- Notification Bell with Dropdown -->
                    @php
                        $unreadNotifications = auth()->user()->notifications()->where('is_read', false)->latest()->take(5)->get();
                        $unreadCount = auth()->user()->notifications()->where('is_read', false)->count();
                    @endphp
                    <div class="relative" id="notification-container">
                        <button onclick="toggleNotificationDropdown()" class="relative p-2 text-slate-500 hover:text-slate-800 hover:bg-slate-100 rounded-xl transition-colors focus:outline-none">
                            <i data-lucide="bell" class="w-5 h-5"></i>
                            @if($unreadCount > 0)
                                <span class="absolute top-1.5 right-1.5 w-2 h-2 rounded-full bg-rose-500 animate-pulse"></span>
                            @endif
                        </button>

                        <!-- Notification Dropdown Panel -->
                        <div id="notification-menu" class="hidden absolute right-0 mt-2 w-80 sm:w-96 bg-white rounded-2xl shadow-xl border border-slate-200 py-3 z-50 animate-in fade-in duration-150">
                            <div class="px-4 py-2 border-b border-slate-100 flex items-center justify-between">
                                <div class="flex items-center space-x-2">
                                    <span class="font-bold text-sm text-slate-900">Notifikasi</span>
                                    @if($unreadCount > 0)
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-100 text-blue-700">
                                            {{ $unreadCount }} Baru
                                        </span>
                                    @endif
                                </div>
                                @if($unreadCount > 0)
                                    <form method="POST" action="{{ route('notifications.readAll') }}">
                                        @csrf
                                        <button type="submit" class="text-xs font-semibold text-blue-600 hover:text-blue-700">
                                            Tandai semua dibaca
                                        </button>
                                    </form>
                                @endif
                            </div>

                            <div class="max-h-72 overflow-y-auto divide-y divide-slate-100">
                                @forelse($unreadNotifications as $n)
                                    <div class="p-3.5 hover:bg-slate-50 transition-colors">
                                        <div class="flex items-start justify-between">
                                            <p class="text-xs font-bold text-slate-900">{{ $n->title }}</p>
                                            <span class="text-[10px] text-slate-400">{{ $n->created_at->diffForHumans() }}</span>
                                        </div>
                                        <p class="text-xs text-slate-600 mt-1 leading-relaxed">{{ $n->message }}</p>
                                        @if($n->link)
                                            <form method="POST" action="{{ route('notifications.read', $n->id) }}" class="mt-2">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="text-[11px] font-bold text-blue-600 hover:underline">
                                                    Lihat Detail →
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                @empty
                                    <div class="py-8 text-center text-slate-400 text-xs">
                                        <i data-lucide="bell-off" class="w-6 h-6 mx-auto mb-2 text-slate-300"></i>
                                        Tidak ada notifikasi baru saat ini.
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- User Profile Pill -->
                    <div class="flex items-center space-x-3 pl-3 border-l border-slate-200">
                        <div class="text-right hidden sm:block">
                            <p class="text-xs font-bold text-slate-900 leading-tight">{{ auth()->user()->name }}</p>
                            <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">
                                {{ auth()->user()->isAdmin() ? 'Administrator' : 'Anggota Perpustakaan' }}
                            </span>
                        </div>
                        <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-blue-600 to-indigo-600 text-white font-bold flex items-center justify-center text-xs shadow-sm">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Content Area -->
            <main class="flex-1 p-4 sm:p-6 lg:p-8 max-w-7xl w-full mx-auto space-y-6">
                <!-- Flash Notification Banners -->
                @if(session('success'))
                    <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-start space-x-3 shadow-xs animate-in slide-in-from-top-2">
                        <i data-lucide="check-circle-2" class="w-5 h-5 text-emerald-600 flex-shrink-0 mt-0.5"></i>
                        <div class="flex-1">
                            <p class="font-bold text-xs uppercase tracking-wider text-emerald-700">Berhasil</p>
                            <p class="mt-0.5 text-xs text-emerald-900">{{ session('success') }}</p>
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-sm flex items-start space-x-3 shadow-xs animate-in slide-in-from-top-2">
                        <i data-lucide="alert-triangle" class="w-5 h-5 text-rose-600 flex-shrink-0 mt-0.5"></i>
                        <div class="flex-1">
                            <p class="font-bold text-xs uppercase tracking-wider text-rose-700">Pemberitahuan</p>
                            <p class="mt-0.5 text-xs text-rose-900">{{ session('error') }}</p>
                        </div>
                    </div>
                @endif

                @if($errors->any())
                    <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs shadow-xs space-y-1">
                        <p class="font-bold text-xs uppercase tracking-wider text-rose-700 flex items-center space-x-1.5">
                            <i data-lucide="alert-circle" class="w-4 h-4"></i>
                            <span>Terdapat beberapa kesalahan validasi:</span>
                        </p>
                        <ul class="list-disc pl-5 space-y-0.5 text-rose-900 pt-1">
                            @foreach($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{ $slot }}
            </main>

            <!-- Footer -->
            <footer class="border-t border-slate-200 bg-white py-4 px-6 text-center text-xs text-slate-500 no-print">
                <p>Sistem Informasi Perpustakaan Berbasis Web • Laravel 12 & Supabase PostgreSQL • RBAC Admin & Anggota</p>
            </footer>
        </div>
    </div>

    <!-- Vanilla JS Helpers -->
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const backdrop = document.getElementById('sidebar-backdrop');
            sidebar.classList.toggle('-translate-x-full');
            backdrop.classList.toggle('hidden');
        }

        function toggleNotificationDropdown() {
            const menu = document.getElementById('notification-menu');
            menu.classList.toggle('hidden');
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            const container = document.getElementById('notification-container');
            const menu = document.getElementById('notification-menu');
            if (container && !container.contains(e.target) && menu && !menu.classList.contains('hidden')) {
                menu.classList.add('hidden');
            }
        });
    </script>
</body>
</html>
