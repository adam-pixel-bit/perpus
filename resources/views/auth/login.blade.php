<x-layouts.auth title="Masuk — Sistem Informasi Perpustakaan">
    <div class="grid grid-cols-1 lg:grid-cols-12 min-h-[calc(92vh-2.5rem)] divide-y lg:divide-y-0 lg:divide-x divide-zinc-200/80">
        
        <!-- LEFT PANEL: LOGIN FORM & WORKSPACE AUTH -->
        <div class="lg:col-span-4 xl:col-span-4 p-6 sm:p-8 flex flex-col justify-between bg-white">
            <div class="space-y-6">
                <!-- Brand / Workspace Switcher (Exact as Reference Image) -->
                <div class="flex items-center justify-between pb-4 border-b border-zinc-100">
                    <div class="flex items-center space-x-2.5">
                        <div class="w-9 h-9 rounded-xl bg-zinc-950 text-white font-black text-lg flex items-center justify-center tracking-tighter shadow-xs">
                            w.
                        </div>
                        <div class="leading-tight">
                            <h2 class="text-xs font-bold text-zinc-900 tracking-tight flex items-center space-x-1">
                                <span>Uxerflow Inc.</span>
                            </h2>
                            <span class="text-[10px] text-zinc-400 font-medium">SIPUS Cloud • Free Plan</span>
                        </div>
                    </div>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-orange-50 text-orange-700 border border-orange-200/60">
                        Portal Masuk
                    </span>
                </div>

                <!-- Page Header Title -->
                <div>
                    <h1 class="text-lg font-bold text-zinc-950 tracking-tight">Masuk ke Akun</h1>
                    <p class="text-xs text-zinc-400 mt-0.5">
                        Selamat Datang Kembali — Masukkan email dan kata sandi Anda.
                    </p>
                </div>

                <!-- Quick Demo 1-Click Fill Cards -->
                <div class="p-3.5 rounded-2xl bg-zinc-50 border border-zinc-200/80 space-y-2">
                    <p class="text-[11px] font-bold text-zinc-600 flex items-center space-x-1.5">
                        <i data-lucide="sparkles" class="w-3.5 h-3.5 text-orange-600"></i>
                        <span>Akses Cepat Akun Demo (Klik untuk mengisi):</span>
                    </p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                        <button
                            type="button"
                            onclick="fillCredentials('admin@perpustakaan.com', 'password123')"
                            class="p-2 rounded-xl bg-white border border-zinc-200/90 hover:border-zinc-400 text-left transition-all cursor-pointer shadow-2xs group"
                        >
                            <span class="text-[11px] font-bold text-zinc-900 group-hover:text-orange-600 block">👤 Administrator</span>
                            <span class="text-[10px] text-zinc-400 block truncate">admin@perpustakaan.com</span>
                        </button>
                        <button
                            type="button"
                            onclick="fillCredentials('budi@perpustakaan.com', 'password123')"
                            class="p-2 rounded-xl bg-white border border-zinc-200/90 hover:border-zinc-400 text-left transition-all cursor-pointer shadow-2xs group"
                        >
                            <span class="text-[11px] font-bold text-zinc-900 group-hover:text-orange-600 block">📖 Anggota (User)</span>
                            <span class="text-[10px] text-zinc-400 block truncate">budi@perpustakaan.com</span>
                        </button>
                    </div>
                </div>

                <!-- Flash Messages -->
                @if(session('success'))
                    <div class="p-3 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs flex items-center space-x-2">
                        <i data-lucide="check-circle" class="w-4 h-4 text-emerald-600 flex-shrink-0"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div class="p-3 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs flex items-center space-x-2">
                        <i data-lucide="alert-circle" class="w-4 h-4 text-rose-600 flex-shrink-0"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                <!-- Login Form -->
                <form method="POST" action="{{ route('login.post') }}" class="space-y-4">
                    @csrf

                    <!-- Email Field -->
                    <div>
                        <label for="email" class="block text-[11px] font-bold uppercase tracking-wider text-zinc-600 mb-1">
                            Alamat Email
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-zinc-400">
                                <i data-lucide="mail" class="w-3.5 h-3.5"></i>
                            </div>
                            <input
                                id="email"
                                type="email"
                                name="email"
                                value="{{ old('email', 'admin@perpustakaan.com') }}"
                                required
                                autofocus
                                placeholder="nama@perpustakaan.com"
                                class="w-full pl-8.5 pr-3 py-2 text-xs bg-zinc-50 border border-zinc-200/80 rounded-xl focus:outline-none focus:ring-1 focus:ring-orange-500 focus:bg-white text-zinc-900 placeholder-zinc-400 transition-colors"
                            >
                        </div>
                        @error('email')
                            <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password Field -->
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <label for="password" class="block text-[11px] font-bold uppercase tracking-wider text-zinc-600">
                                Kata Sandi
                            </label>
                            <span class="text-[10px] text-zinc-400 font-medium">Min. 6 Karakter</span>
                        </div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-zinc-400">
                                <i data-lucide="lock" class="w-3.5 h-3.5"></i>
                            </div>
                            <input
                                id="password"
                                type="password"
                                name="password"
                                value="password123"
                                required
                                placeholder="••••••••"
                                class="w-full pl-8.5 pr-9 py-2 text-xs bg-zinc-50 border border-zinc-200/80 rounded-xl focus:outline-none focus:ring-1 focus:ring-orange-500 focus:bg-white text-zinc-900 placeholder-zinc-400 transition-colors"
                            >
                            <button
                                type="button"
                                onclick="togglePasswordVisibility()"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-zinc-400 hover:text-zinc-600 cursor-pointer"
                                title="Lihat/Sembunyikan Kata Sandi"
                            >
                                <i id="eye-icon" data-lucide="eye" class="w-3.5 h-3.5"></i>
                            </button>
                        </div>
                        @error('password')
                            <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Remember Me Toggle (Matches Screenshot Toggle Style) -->
                    <div class="flex items-center justify-between text-xs pt-1">
                        <label class="inline-flex items-center space-x-2 cursor-pointer select-none">
                            <div class="relative inline-block w-7 h-4 transition duration-200 ease-in-out align-middle select-none">
                                <input
                                    type="checkbox"
                                    name="remember"
                                    id="remember-toggle"
                                    checked
                                    class="toggle-checkbox absolute block w-4 h-4 rounded-full bg-white border-2 border-zinc-300 appearance-none cursor-pointer transition-all duration-200 checked:right-0 checked:bg-orange-600 checked:border-orange-600"
                                />
                                <label for="remember-toggle" class="toggle-label block overflow-hidden h-4 rounded-full bg-zinc-200 cursor-pointer"></label>
                            </div>
                            <span class="text-xs text-zinc-600 font-medium">Ingat saya</span>
                        </label>
                        <a href="{{ route('register') }}" class="text-xs font-semibold text-orange-600 hover:text-orange-700">
                            Daftar Anggota →
                        </a>
                    </div>

                    <!-- High-Contrast Black Button (Exact Uxerflow Style) -->
                    <button
                        type="submit"
                        class="w-full py-2.5 px-4 bg-zinc-950 hover:bg-black text-white text-xs font-bold rounded-xl shadow-md shadow-zinc-950/20 transition-all active:scale-[0.98] flex items-center justify-center space-x-2 cursor-pointer mt-2"
                    >
                        <i data-lucide="log-in" class="w-3.5 h-3.5"></i>
                        <span>Masuk ke Dashboard</span>
                    </button>
                </form>
            </div>

            <!-- Footer Section -->
            <div class="pt-6 border-t border-zinc-100 flex items-center justify-between text-xs text-zinc-400">
                <span>Belum punya akun?</span>
                <a href="{{ route('register') }}" class="font-bold text-zinc-900 hover:text-orange-600 transition-colors">
                    Registrasi Akun Baru →
                </a>
            </div>
        </div>

        <!-- RIGHT PANEL: UXERFLOW DASHBOARD & LIVE CATALOG PREVIEW (Exact match to screenshot) -->
        <div class="lg:col-span-8 xl:col-span-8 p-6 sm:p-8 space-y-5 bg-white overflow-hidden">
            <!-- Top Controls Toolbar (Table View, Filter, Sort, Show Statistics, Export, Add New) -->
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3">
                <div class="flex items-center flex-wrap gap-2.5">
                    <div class="inline-flex items-center space-x-2 px-3 py-1.5 rounded-xl border border-zinc-200/90 bg-white text-xs font-semibold text-zinc-800 shadow-2xs">
                        <i data-lucide="table" class="w-3.5 h-3.5 text-zinc-500"></i>
                        <span>Table View</span>
                        <i data-lucide="chevron-down" class="w-3 h-3 text-zinc-400 ml-1"></i>
                    </div>

                    <div class="inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-xl border border-zinc-200/90 bg-white text-xs font-semibold text-zinc-800 shadow-2xs">
                        <i data-lucide="filter" class="w-3.5 h-3.5 text-zinc-500"></i>
                        <span>Filter</span>
                    </div>

                    <div class="inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-xl border border-zinc-200/90 bg-white text-xs font-semibold text-zinc-800 shadow-2xs">
                        <i data-lucide="arrow-up-down" class="w-3.5 h-3.5 text-zinc-500"></i>
                        <span>Sort</span>
                    </div>

                    <!-- Orange Show Statistics Toggle Switch -->
                    <label class="inline-flex items-center space-x-2 ml-1 text-xs font-semibold text-zinc-700 select-none">
                        <span>Show Statistics</span>
                        <div class="relative inline-block w-8 h-4 transition duration-200 ease-in-out align-middle select-none">
                            <input
                                type="checkbox"
                                id="toggle-preview-stats"
                                checked
                                onchange="togglePreviewStats(this)"
                                class="toggle-checkbox absolute block w-4 h-4 rounded-full bg-white border-2 border-zinc-300 appearance-none transition-all duration-200 checked:right-0 checked:bg-orange-600 checked:border-orange-600"
                            />
                            <label for="toggle-preview-stats" class="toggle-label block overflow-hidden h-4 rounded-full bg-zinc-200"></label>
                        </div>
                    </label>
                </div>

                <!-- Right Action Buttons -->
                <div class="flex items-center space-x-2">
                    <div class="inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-xl border border-zinc-200/90 bg-white text-xs font-semibold text-zinc-700 shadow-2xs">
                        <i data-lucide="sliders-horizontal" class="w-3.5 h-3.5 text-zinc-500"></i>
                        <span>Customize</span>
                    </div>
                    <div class="inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-xl border border-zinc-200/90 bg-white text-xs font-semibold text-zinc-700 shadow-2xs">
                        <i data-lucide="upload" class="w-3.5 h-3.5 text-zinc-500"></i>
                        <span>Export</span>
                    </div>
                    <a
                        href="{{ route('register') }}"
                        class="inline-flex items-center space-x-1.5 px-3.5 py-1.5 rounded-xl bg-zinc-950 hover:bg-black text-white text-xs font-bold transition-all shadow-md shadow-zinc-950/20 active:scale-[0.98]"
                    >
                        <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                        <span>+ Add New Product</span>
                    </a>
                </div>
            </div>

            <!-- 4 Divided Horizontal KPI Statistics Cards (Exact match to screenshot) -->
            <div id="preview-statistics" class="bg-white rounded-2xl border border-zinc-200/80 shadow-2xs overflow-hidden">
                <div class="grid grid-cols-2 lg:grid-cols-4 divide-y lg:divide-y-0 lg:divide-x divide-zinc-200/80">
                    <!-- Metric 1: Total Product -->
                    <div class="p-4 space-y-1.5">
                        <div class="flex items-center space-x-1.5 text-xs font-medium text-zinc-500">
                            <span>Total Product</span>
                            <i data-lucide="info" class="w-3 h-3 text-zinc-400"></i>
                        </div>
                        <div class="text-2xl font-bold text-zinc-950 tracking-tight">
                            {{ max(250, \App\Models\Book::count()) }}
                        </div>
                        <div class="flex items-center space-x-1.5 text-[11px] text-zinc-400">
                            <span>vs last month</span>
                            <span class="inline-flex items-center text-emerald-600 font-bold bg-emerald-50 px-1.5 py-0.2 rounded-md">
                                + 3 product
                            </span>
                        </div>
                    </div>

                    <!-- Metric 2: Product Revenue -->
                    <div class="p-4 space-y-1.5">
                        <div class="flex items-center space-x-1.5 text-xs font-medium text-zinc-500">
                            <span>Product Revenue</span>
                            <i data-lucide="info" class="w-3 h-3 text-zinc-400"></i>
                        </div>
                        <div class="text-2xl font-bold text-zinc-950 tracking-tight">
                            $15,490
                        </div>
                        <div class="flex items-center space-x-1.5 text-[11px] text-zinc-400">
                            <span>vs last month</span>
                            <span class="inline-flex items-center text-emerald-600 font-bold bg-emerald-50 px-1.5 py-0.2 rounded-md">
                                + 9%
                            </span>
                        </div>
                    </div>

                    <!-- Metric 3: Product Sold -->
                    <div class="p-4 space-y-1.5">
                        <div class="flex items-center space-x-1.5 text-xs font-medium text-zinc-500">
                            <span>Product Sold</span>
                            <i data-lucide="info" class="w-3 h-3 text-zinc-400"></i>
                        </div>
                        <div class="text-2xl font-bold text-zinc-950 tracking-tight">
                            2,355
                        </div>
                        <div class="flex items-center space-x-1.5 text-[11px] text-zinc-400">
                            <span>vs last month</span>
                            <span class="inline-flex items-center text-emerald-600 font-bold bg-emerald-50 px-1.5 py-0.2 rounded-md">
                                + 7%
                            </span>
                        </div>
                    </div>

                    <!-- Metric 4: Avg. Monthly Sales -->
                    <div class="p-4 space-y-1.5">
                        <div class="flex items-center space-x-1.5 text-xs font-medium text-zinc-500">
                            <span>Avg. Monthly Sales</span>
                            <i data-lucide="info" class="w-3 h-3 text-zinc-400"></i>
                        </div>
                        <div class="text-2xl font-bold text-zinc-950 tracking-tight">
                            890
                        </div>
                        <div class="flex items-center space-x-1.5 text-[11px] text-zinc-400">
                            <span>vs last month</span>
                            <span class="inline-flex items-center text-emerald-600 font-bold bg-emerald-50 px-1.5 py-0.2 rounded-md">
                                + 5%
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Uxerflow Product Data Table (Exact visual layout and badges) -->
            <div class="bg-white rounded-2xl border border-zinc-200/80 shadow-2xs overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-zinc-50/60 border-b border-zinc-200/80 text-[11px] font-semibold text-zinc-500">
                                <th class="py-2.5 px-3.5 w-10">
                                    <input type="checkbox" class="w-3.5 h-3.5 rounded text-orange-600 border-zinc-300 focus:ring-0">
                                </th>
                                <th class="py-2.5 px-3.5 font-semibold text-zinc-700">Product</th>
                                <th class="py-2.5 px-3.5 font-semibold text-zinc-700">Price</th>
                                <th class="py-2.5 px-3.5 font-semibold text-zinc-700">Sales</th>
                                <th class="py-2.5 px-3.5 font-semibold text-zinc-700">Revenue</th>
                                <th class="py-2.5 px-3.5 font-semibold text-zinc-700">Stock</th>
                                <th class="py-2.5 px-3.5 font-semibold text-zinc-700">Status</th>
                                <th class="py-2.5 px-3.5 font-semibold text-zinc-700">Rating</th>
                                <th class="py-2.5 px-3.5 text-center w-8 text-zinc-400 font-bold">+</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-100 text-zinc-700 text-xs">
                            @php
                                $sampleProducts = [
                                    ['name' => 'Uxerflow T-Shirt #10 - White', 'price' => '$1.35', 'sales' => '471 pcs', 'revenue' => '$635.85', 'stock' => 100, 'status' => 'In Stock', 'badge' => 'bg-[#F0F2FE] text-[#5552D9] border-[#E2E5FD]', 'rating' => '5.0', 'checked' => false],
                                    ['name' => 'Uxerflow T-Shirt #10 - Black', 'price' => '$1.35', 'sales' => '402 pcs', 'revenue' => '$544.05', 'stock' => 0, 'status' => 'Out of Stock', 'badge' => 'bg-[#FDF2F2] text-[#D84545] border-[#FCDADA]', 'rating' => '5.0', 'checked' => true],
                                    ['name' => 'Uxerflow T-Shirt #19 - White', 'price' => '$1.35', 'sales' => '455 pcs', 'revenue' => '$645.25', 'stock' => 20, 'status' => 'Restock', 'badge' => 'bg-[#FFF8EB] text-[#B87A04] border-[#FDEBC8]', 'rating' => '4.9', 'checked' => false],
                                    ['name' => 'SmartHome Hub', 'price' => '$150', 'sales' => '7 pcs', 'revenue' => '$1,050.00', 'stock' => 12, 'status' => 'In Stock', 'badge' => 'bg-[#F0F2FE] text-[#5552D9] border-[#E2E5FD]', 'rating' => '4.8', 'checked' => true],
                                    ['name' => 'UltraSound Wireless Earbuds', 'price' => '$200', 'sales' => '5 pcs', 'revenue' => '$1,000.00', 'stock' => 0, 'status' => 'Out of Stock', 'badge' => 'bg-[#FDF2F2] text-[#D84545] border-[#FCDADA]', 'rating' => '4.8', 'checked' => false],
                                    ['name' => 'ProVision 4K Monitor', 'price' => '$400.25', 'sales' => '1 pcs', 'revenue' => '$400.25', 'stock' => 3, 'status' => 'Restock', 'badge' => 'bg-[#FFF8EB] text-[#B87A04] border-[#FDEBC8]', 'rating' => '5.0', 'checked' => false],
                                    ['name' => 'Uxerflow Retro Wave Shirt', 'price' => '$1.35', 'sales' => '120 pcs', 'revenue' => '$162.40', 'stock' => 20, 'status' => 'In Stock', 'badge' => 'bg-[#F0F2FE] text-[#5552D9] border-[#E2E5FD]', 'rating' => '4.7', 'checked' => false],
                                    ['name' => 'EchoWave Bluetooth Speaker', 'price' => '$55.5', 'sales' => '10 pcs', 'revenue' => '$555.00', 'stock' => 20, 'status' => 'In Stock', 'badge' => 'bg-[#F0F2FE] text-[#5552D9] border-[#E2E5FD]', 'rating' => '5.0', 'checked' => false],
                                ];
                            @endphp

                            @foreach($sampleProducts as $p)
                                <tr class="transition-colors {{ $p['checked'] ? 'bg-[#FFF7F2] border-l-4 border-l-orange-500' : 'hover:bg-zinc-50/70' }}">
                                    <td class="py-2.5 px-3.5">
                                        <input
                                            type="checkbox"
                                            {{ $p['checked'] ? 'checked' : '' }}
                                            class="w-3.5 h-3.5 rounded text-orange-600 border-zinc-300 focus:ring-0 cursor-pointer"
                                        >
                                    </td>
                                    <td class="py-2.5 px-3.5 font-medium text-zinc-900 truncate max-w-xs">
                                        {{ $p['name'] }}
                                    </td>
                                    <td class="py-2.5 px-3.5 text-zinc-600 font-mono">{{ $p['price'] }}</td>
                                    <td class="py-2.5 px-3.5 text-zinc-600">{{ $p['sales'] }}</td>
                                    <td class="py-2.5 px-3.5 text-zinc-900 font-semibold font-mono">{{ $p['revenue'] }}</td>
                                    <td class="py-2.5 px-3.5 text-zinc-600">{{ $p['stock'] }}</td>
                                    <td class="py-2.5 px-3.5">
                                        <span class="inline-block px-2 py-0.5 rounded-md text-[10px] font-medium border {{ $p['badge'] }}">
                                            {{ $p['status'] }}
                                        </span>
                                    </td>
                                    <td class="py-2.5 px-3.5">
                                        <div class="flex items-center space-x-1 font-semibold text-zinc-700">
                                            <span class="text-amber-400 text-xs">★</span>
                                            <span>{{ $p['rating'] }}</span>
                                        </div>
                                    </td>
                                    <td class="py-2.5 px-3.5 text-center text-zinc-400">+</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Add Column Bottom Row -->
                <div class="px-3.5 py-2 border-t border-zinc-100 bg-white flex items-center">
                    <span class="inline-flex items-center space-x-1.5 text-[11px] font-semibold text-zinc-400">
                        <i data-lucide="plus" class="w-3 h-3"></i>
                        <span>Add Column</span>
                    </span>
                </div>
            </div>

            <!-- Bottom Floating Action Bar & Numeric Pagination (Exact as Screenshot) -->
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-1 text-xs text-zinc-500">
                <!-- Showing Per Page Dropdown -->
                <div class="flex items-center space-x-2">
                    <span>Showing per page</span>
                    <select class="px-2 py-0.5 bg-white border border-zinc-200 rounded-lg text-xs font-semibold text-zinc-800 focus:outline-none">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                    </select>
                </div>

                <!-- Center Numeric Pagination with Active Orange Pill "1" -->
                <div class="flex items-center space-x-1 font-semibold">
                    <span class="px-2 py-0.5 text-zinc-400">«</span>
                    <span class="px-2 py-0.5 text-zinc-400"><</span>
                    <span class="w-6 h-6 rounded-md bg-orange-600 text-white font-bold flex items-center justify-center text-xs shadow-xs">
                        1
                    </span>
                    <span class="w-6 h-6 rounded-md text-zinc-600 hover:bg-zinc-100 flex items-center justify-center text-xs cursor-pointer">2</span>
                    <span class="w-6 h-6 rounded-md text-zinc-600 hover:bg-zinc-100 flex items-center justify-center text-xs cursor-pointer">3</span>
                    <span class="px-1 text-zinc-400">...</span>
                    <span class="w-6 h-6 rounded-md text-zinc-600 hover:bg-zinc-100 flex items-center justify-center text-xs cursor-pointer">25</span>
                    <span class="px-2 py-0.5 text-zinc-400 cursor-pointer">></span>
                    <span class="px-2 py-0.5 text-zinc-400 cursor-pointer">»</span>
                </div>

                <!-- Go to Page Input -->
                <div class="flex items-center space-x-2">
                    <span>Go to page</span>
                    <input type="text" value="1" class="w-8 px-1 py-0.5 bg-white border border-zinc-200 rounded-lg text-xs text-center font-bold text-zinc-800 focus:outline-none">
                    <button type="button" class="font-bold text-zinc-800 hover:text-orange-600 transition-colors cursor-pointer">
                        Go >
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Client-side Scripts -->
    <script>
        function fillCredentials(email, password) {
            const emailInput = document.getElementById('email');
            const passInput = document.getElementById('password');
            emailInput.value = email;
            passInput.value = password;

            emailInput.classList.add('ring-2', 'ring-orange-500/40');
            passInput.classList.add('ring-2', 'ring-orange-500/40');
            setTimeout(() => {
                emailInput.classList.remove('ring-2', 'ring-orange-500/40');
                passInput.classList.remove('ring-2', 'ring-orange-500/40');
            }, 500);
        }

        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.setAttribute('data-lucide', 'eye-off');
            } else {
                passwordInput.type = 'password';
                eyeIcon.setAttribute('data-lucide', 'eye');
            }
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        }

        function togglePreviewStats(checkbox) {
            const container = document.getElementById('preview-statistics');
            if (container) {
                if (checkbox.checked) {
                    container.classList.remove('hidden');
                } else {
                    container.classList.add('hidden');
                }
            }
        }
    </script>
</x-layouts.auth>
