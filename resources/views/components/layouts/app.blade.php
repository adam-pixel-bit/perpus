<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Sistem Informasi Perpustakaan' }} — Uxerflow Library</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#F4F5F7] text-zinc-900 font-sans antialiased min-h-screen flex p-3 sm:p-5 selection:bg-orange-500 selection:text-white">
    <!-- Outer Application Canvas Box (Rounded Window Aesthetic like the screenshot) -->
    <div class="flex-1 w-full bg-white rounded-3xl shadow-xl shadow-zinc-200/60 border border-zinc-200/80 flex overflow-hidden min-h-[92vh]">
        
        <!-- SIDEBAR -->
        <aside id="sidebar" class="w-64 bg-white border-r border-zinc-200/80 flex flex-col justify-between flex-shrink-0 z-30 transition-transform duration-200 lg:translate-x-0 fixed inset-y-0 left-0 lg:static -translate-x-full lg:flex">
            <div class="flex-1 flex flex-col min-h-0 overflow-y-auto">
                <!-- Workspace / Team Header -->
                <div class="p-5 flex items-center justify-between border-b border-zinc-100">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 rounded-lg bg-zinc-900 text-white flex items-center justify-center font-bold text-sm tracking-tighter shadow-xs">
                            w.
                        </div>
                        <div class="min-w-0">
                            <h2 class="text-xs font-bold text-zinc-900 truncate">Uxerflow Inc.</h2>
                            <span class="text-[10px] text-zinc-400 font-medium block">
                                {{ auth()->user()?->isAdmin() ? 'Administrator Plan' : 'Member Free Plan' }}
                            </span>
                        </div>
                    </div>
                    <button type="button" class="text-zinc-400 hover:text-zinc-600 p-1 rounded-md" title="Collapse sidebar">
                        <i data-lucide="chevrons-left" class="w-4 h-4"></i>
                    </button>
                </div>

                <!-- Search Input -->
                <div class="px-4 pt-4 pb-2">
                    <div class="relative">
                        <i data-lucide="search" class="w-3.5 h-3.5 text-zinc-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                        <input
                            type="text"
                            placeholder="Search"
                            class="w-full pl-8 pr-12 py-1.5 text-xs bg-zinc-50 border border-zinc-200/80 rounded-xl focus:outline-none focus:ring-1 focus:ring-orange-500 text-zinc-800 placeholder:text-zinc-400 font-medium"
                        >
                        <span class="absolute right-2.5 top-1/2 -translate-y-1/2 text-[10px] text-zinc-400 font-semibold px-1 rounded border border-zinc-200 bg-white">
                            ⌘ K
                        </span>
                    </div>
                </div>

                <!-- Navigation Sections -->
                <nav class="px-3 py-2 space-y-5 text-xs font-medium text-zinc-600">
                    <!-- MAIN MENU -->
                    <div class="space-y-0.5">
                        <span class="px-3 text-[10px] font-bold text-zinc-400 uppercase tracking-wider block mb-1.5">MAIN MENU</span>
                        
                        @if(auth()->user()?->isAdmin())
                            <!-- Dashboard -->
                            <a href="{{ route('admin.dashboard') }}" class="group relative flex items-center justify-between px-3 py-2 rounded-xl transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-zinc-100/80 text-zinc-950 font-bold' : 'hover:bg-zinc-50 hover:text-zinc-900' }}">
                                @if(request()->routeIs('admin.dashboard'))
                                    <span class="absolute -left-3 top-1.5 bottom-1.5 w-1 bg-orange-600 rounded-r-md"></span>
                                @endif
                                <div class="flex items-center space-x-2.5">
                                    <i data-lucide="layout-grid" class="w-4 h-4 {{ request()->routeIs('admin.dashboard') ? 'text-zinc-950' : 'text-zinc-400 group-hover:text-zinc-600' }}"></i>
                                    <span>Dashboard</span>
                                </div>
                            </a>

                            <!-- Product / Books (Active Style as Screenshot) -->
                            <a href="{{ route('admin.books.index') }}" class="group relative flex items-center justify-between px-3 py-2 rounded-xl transition-colors {{ request()->routeIs('admin.books.*') ? 'bg-zinc-100/80 text-zinc-950 font-bold' : 'hover:bg-zinc-50 hover:text-zinc-900' }}">
                                @if(request()->routeIs('admin.books.*'))
                                    <span class="absolute -left-3 top-1.5 bottom-1.5 w-1 bg-orange-600 rounded-r-md"></span>
                                @endif
                                <div class="flex items-center space-x-2.5">
                                    <i data-lucide="box" class="w-4 h-4 {{ request()->routeIs('admin.books.*') ? 'text-zinc-950' : 'text-zinc-400 group-hover:text-zinc-600' }}"></i>
                                    <span>Product (Buku)</span>
                                </div>
                            </a>

                            <!-- Order / Sirkulasi -->
                            <a href="{{ route('admin.borrowings.index') }}" class="group relative flex items-center justify-between px-3 py-2 rounded-xl transition-colors {{ request()->routeIs('admin.borrowings.*') ? 'bg-zinc-100/80 text-zinc-950 font-bold' : 'hover:bg-zinc-50 hover:text-zinc-900' }}">
                                @if(request()->routeIs('admin.borrowings.*'))
                                    <span class="absolute -left-3 top-1.5 bottom-1.5 w-1 bg-orange-600 rounded-r-md"></span>
                                @endif
                                <div class="flex items-center space-x-2.5">
                                    <i data-lucide="shopping-bag" class="w-4 h-4 {{ request()->routeIs('admin.borrowings.*') ? 'text-zinc-950' : 'text-zinc-400 group-hover:text-zinc-600' }}"></i>
                                    <span>Order (Sirkulasi)</span>
                                </div>
                                @php
                                    $pendingCountBadge = \App\Models\Borrowing::where('status', 'pending')->count();
                                @endphp
                                @if($pendingCountBadge > 0)
                                    <span class="px-1.5 py-0.5 rounded-full text-[10px] font-bold bg-orange-100 text-orange-700">
                                        {{ $pendingCountBadge }}
                                    </span>
                                @endif
                            </a>

                            <!-- Customer / Anggota -->
                            <a href="{{ route('admin.users.index') }}" class="group relative flex items-center justify-between px-3 py-2 rounded-xl transition-colors {{ request()->routeIs('admin.users.*') ? 'bg-zinc-100/80 text-zinc-950 font-bold' : 'hover:bg-zinc-50 hover:text-zinc-900' }}">
                                @if(request()->routeIs('admin.users.*'))
                                    <span class="absolute -left-3 top-1.5 bottom-1.5 w-1 bg-orange-600 rounded-r-md"></span>
                                @endif
                                <div class="flex items-center space-x-2.5">
                                    <i data-lucide="users-round" class="w-4 h-4 {{ request()->routeIs('admin.users.*') ? 'text-zinc-950' : 'text-zinc-400 group-hover:text-zinc-600' }}"></i>
                                    <span>Customer (Anggota)</span>
                                </div>
                            </a>

                            <!-- Message / Kategori -->
                            <a href="{{ route('admin.categories.index') }}" class="group relative flex items-center justify-between px-3 py-2 rounded-xl transition-colors {{ request()->routeIs('admin.categories.*') ? 'bg-zinc-100/80 text-zinc-950 font-bold' : 'hover:bg-zinc-50 hover:text-zinc-900' }}">
                                @if(request()->routeIs('admin.categories.*'))
                                    <span class="absolute -left-3 top-1.5 bottom-1.5 w-1 bg-orange-600 rounded-r-md"></span>
                                @endif
                                <div class="flex items-center space-x-2.5">
                                    <i data-lucide="message-square" class="w-4 h-4 {{ request()->routeIs('admin.categories.*') ? 'text-zinc-950' : 'text-zinc-400 group-hover:text-zinc-600' }}"></i>
                                    <span>Kategori Koleksi</span>
                                </div>
                                <span class="px-1.5 py-0.5 rounded-full text-[10px] font-semibold bg-zinc-100 text-zinc-600">
                                    {{ \App\Models\Category::count() }}
                                </span>
                            </a>
                        @else
                            <!-- MEMBER USER MENU -->
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
                                    <span>Product (Katalog)</span>
                                </div>
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

                    <!-- TOOLS -->
                    <div class="space-y-0.5">
                        <span class="px-3 text-[10px] font-bold text-zinc-400 uppercase tracking-wider block mb-1.5">TOOLS</span>
                        
                        @if(auth()->user()?->isAdmin())
                            <a href="{{ route('admin.reports.index') }}" class="group flex items-center space-x-2.5 px-3 py-2 rounded-xl hover:bg-zinc-50 hover:text-zinc-900 transition-colors {{ request()->routeIs('admin.reports.*') ? 'bg-zinc-100/80 text-zinc-950 font-bold' : '' }}">
                                <i data-lucide="bar-chart-2" class="w-4 h-4 text-zinc-400 group-hover:text-zinc-600"></i>
                                <span>Analytics & Laporan</span>
                            </a>
                        @endif

                        <div class="group flex items-center space-x-2.5 px-3 py-2 rounded-xl text-zinc-400 hover:text-zinc-600 transition-colors cursor-pointer">
                            <i data-lucide="mail" class="w-4 h-4"></i>
                            <span>Email Notification</span>
                        </div>
                        <div class="group flex items-center space-x-2.5 px-3 py-2 rounded-xl text-zinc-400 hover:text-zinc-600 transition-colors cursor-pointer">
                            <i data-lucide="workflow" class="w-4 h-4"></i>
                            <span>Automation</span>
                        </div>
                        <div class="group flex items-center space-x-2.5 px-3 py-2 rounded-xl text-zinc-400 hover:text-zinc-600 transition-colors cursor-pointer">
                            <i data-lucide="cable" class="w-4 h-4"></i>
                            <span>Integration (Supabase)</span>
                        </div>
                    </div>

                    <!-- WORKSPACE -->
                    <div class="space-y-0.5">
                        <div class="px-3 flex items-center justify-between text-[10px] font-bold text-zinc-400 uppercase tracking-wider mb-1.5">
                            <span>WORKSPACE</span>
                            <i data-lucide="chevron-up" class="w-3 h-3 text-zinc-400"></i>
                        </div>
                        <div class="flex items-center justify-between px-3 py-1.5 rounded-xl text-zinc-600 hover:bg-zinc-50 cursor-pointer">
                            <div class="flex items-center space-x-2.5">
                                <span class="w-2.5 h-2.5 rounded-xs bg-indigo-600"></span>
                                <span class="text-xs">Campaign</span>
                            </div>
                            <span class="text-[10px] font-bold text-zinc-400 px-1.5 py-0.2 rounded-full bg-zinc-100">5</span>
                        </div>
                        <div class="flex items-center justify-between px-3 py-1.5 rounded-xl text-zinc-600 hover:bg-zinc-50 cursor-pointer">
                            <div class="flex items-center space-x-2.5">
                                <span class="w-2.5 h-2.5 rounded-xs bg-pink-500"></span>
                                <span class="text-xs">Product Plan</span>
                            </div>
                            <span class="text-[10px] font-bold text-zinc-400 px-1.5 py-0.2 rounded-full bg-zinc-100">4</span>
                        </div>
                    </div>
                </nav>
            </div>

            <!-- Sidebar Bottom Items -->
            <div class="p-4 border-t border-zinc-100 space-y-3">
                <div class="space-y-1 text-xs text-zinc-600 font-medium">
                    <div class="flex items-center space-x-2.5 px-3 py-1.5 hover:text-zinc-950 cursor-pointer">
                        <i data-lucide="help-circle" class="w-3.5 h-3.5 text-zinc-400"></i>
                        <span>Help center</span>
                    </div>
                    <div class="flex items-center space-x-2.5 px-3 py-1.5 hover:text-zinc-950 cursor-pointer">
                        <i data-lucide="message-circle" class="w-3.5 h-3.5 text-zinc-400"></i>
                        <span>Feedback</span>
                    </div>
                    <div class="flex items-center space-x-2.5 px-3 py-1.5 hover:text-zinc-950 cursor-pointer">
                        <i data-lucide="settings" class="w-3.5 h-3.5 text-zinc-400"></i>
                        <span>Settings</span>
                    </div>
                </div>

                <!-- Upgrade & Unlock Card (Bottom Sidebar) -->
                <div class="p-3 rounded-2xl bg-zinc-50 border border-zinc-200/80 flex items-center justify-between group hover:border-orange-300 transition-colors cursor-pointer">
                    <div class="flex items-center space-x-2.5">
                        <div class="w-7 h-7 rounded-lg bg-orange-600 text-white flex items-center justify-center shadow-xs">
                            <i data-lucide="rocket" class="w-3.5 h-3.5"></i>
                        </div>
                        <div class="min-w-0">
                            <p class="text-[11px] font-bold text-zinc-900 leading-tight">Upgrade & unlock</p>
                            <p class="text-[10px] text-zinc-400 truncate">all features</p>
                        </div>
                    </div>
                    <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-zinc-400 group-hover:text-orange-600 transition-colors"></i>
                </div>

                <!-- User Account / Logout -->
                <div class="pt-2 border-t border-zinc-100 flex items-center justify-between">
                    <div class="flex items-center space-x-2 min-w-0">
                        <div class="w-7 h-7 rounded-full bg-zinc-900 text-white font-bold flex items-center justify-center text-[10px]">
                            {{ strtoupper(substr(auth()->user()?->name ?? 'U', 0, 1)) }}
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs font-bold text-zinc-900 truncate max-w-[110px]">{{ auth()->user()?->name ?? 'Guest' }}</p>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" title="Logout" class="p-1.5 text-zinc-400 hover:text-rose-600 hover:bg-zinc-100 rounded-lg transition-colors">
                            <i data-lucide="log-out" class="w-3.5 h-3.5"></i>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- MAIN VIEW CONTAINER -->
        <div class="flex-1 flex flex-col min-w-0 overflow-y-auto bg-white">
            <!-- Top Header Navbar -->
            <header class="h-16 px-6 sm:px-8 border-b border-zinc-100 flex items-center justify-between flex-shrink-0">
                <div class="flex items-center space-x-3">
                    <button type="button" onclick="toggleSidebar()" class="lg:hidden p-1.5 text-zinc-500 hover:bg-zinc-100 rounded-lg">
                        <i data-lucide="menu" class="w-5 h-5"></i>
                    </button>
                    <h1 class="text-xl font-bold text-zinc-950 tracking-tight">{{ $pageTitle ?? 'Product' }}</h1>
                </div>

                <!-- Top Right Utilities -->
                <div class="flex items-center space-x-3">
                    <button class="p-2 text-zinc-500 hover:text-zinc-800 hover:bg-zinc-50 rounded-xl border border-zinc-200/60 shadow-2xs transition-colors" title="Notification">
                        <i data-lucide="bell" class="w-4 h-4"></i>
                    </button>

                    <!-- Avatar Group (+3) -->
                    <div class="flex items-center -space-x-1.5">
                        <img class="w-7 h-7 rounded-full border-2 border-white object-cover" src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=80&q=80" alt="Avatar">
                        <img class="w-7 h-7 rounded-full border-2 border-white object-cover" src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=80&q=80" alt="Avatar">
                        <span class="w-7 h-7 rounded-full border-2 border-white bg-orange-100 text-orange-800 text-[10px] font-bold flex items-center justify-center">
                            +3
                        </span>
                    </div>

                    <!-- Invite User Icon -->
                    <button class="p-2 text-zinc-600 hover:bg-zinc-100 rounded-xl border border-zinc-200/60 transition-colors" title="Invite member">
                        <i data-lucide="user-plus" class="w-4 h-4"></i>
                    </button>

                    <!-- Customize Widget Button -->
                    <button class="hidden sm:inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-xl border border-zinc-200/80 text-xs font-semibold text-zinc-700 hover:bg-zinc-50 transition-colors">
                        <i data-lucide="layout" class="w-3.5 h-3.5 text-zinc-500"></i>
                        <span>Customize Widget</span>
                    </button>
                </div>
            </header>

            <!-- Main Content Area -->
            <main class="flex-1 p-6 sm:p-8 space-y-6">
                <!-- Flash Notification Banner -->
                @if(session('success'))
                    <div class="p-3.5 rounded-2xl bg-zinc-50 border border-zinc-200 text-zinc-900 text-xs flex items-center justify-between shadow-2xs">
                        <div class="flex items-center space-x-2">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            <span class="font-semibold">{{ session('success') }}</span>
                        </div>
                        <button type="button" onclick="this.parentElement.remove()" class="text-zinc-400 hover:text-zinc-600">
                            <i data-lucide="x" class="w-3.5 h-3.5"></i>
                        </button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="p-3.5 rounded-2xl bg-rose-50 border border-rose-200 text-rose-900 text-xs flex items-center justify-between shadow-2xs">
                        <div class="flex items-center space-x-2">
                            <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                            <span class="font-semibold">{{ session('error') }}</span>
                        </div>
                        <button type="button" onclick="this.parentElement.remove()" class="text-rose-400 hover:text-rose-600">
                            <i data-lucide="x" class="w-3.5 h-3.5"></i>
                        </button>
                    </div>
                @endif

                {{ $slot }}
            </main>
        </div>
    </div>

    <!-- Mobile Drawer Backdrop -->
    <div id="sidebar-backdrop" onclick="toggleSidebar()" class="fixed inset-0 z-20 bg-zinc-900/40 backdrop-blur-xs hidden lg:hidden"></div>

    <script>
        function toggleSidebar() {
            const sb = document.getElementById('sidebar');
            const bd = document.getElementById('sidebar-backdrop');
            sb.classList.toggle('-translate-x-full');
            bd.classList.toggle('hidden');
        }

        // Toggle KPI statistics container
        function toggleStatistics(checkbox) {
            const container = document.getElementById('statistics-container');
            if (container) {
                if (checkbox.checked) {
                    container.classList.remove('hidden');
                } else {
                    container.classList.add('hidden');
                }
            }
        }
    </script>
</body>
</html>
