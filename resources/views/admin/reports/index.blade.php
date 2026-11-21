<x-layouts.app title="Laporan Sistem">
    <!-- Subheader Toolbar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-2 border-b border-zinc-200/70 no-print">
        <div class="flex items-center space-x-2 text-xs text-zinc-600 font-medium">
            <div class="flex items-center space-x-1.5 px-3 py-1.5 bg-white border border-zinc-200/80 rounded-xl shadow-2xs">
                <i data-lucide="bar-chart-2" class="w-3.5 h-3.5 text-zinc-500"></i>
                <span class="font-semibold text-zinc-800">Circulation Reports</span>
                <i data-lucide="chevron-down" class="w-3 h-3 text-zinc-400"></i>
            </div>
            <span class="text-xs text-zinc-400">Period: {{ date('d M Y', strtotime($startDate)) }} — {{ date('d M Y', strtotime($endDate)) }}</span>
        </div>

        <div class="flex items-center space-x-2">
            <a
                href="{{ route('admin.reports.export', ['start_date' => $startDate, 'end_date' => $endDate]) }}"
                class="flex items-center space-x-1.5 px-3.5 py-1.5 text-xs font-semibold text-white bg-zinc-950 hover:bg-zinc-800 rounded-xl shadow-2xs transition-all"
            >
                <i data-lucide="download" class="w-3.5 h-3.5"></i>
                <span>Export CSV</span>
            </a>
            <button
                type="button"
                onclick="window.print()"
                class="flex items-center space-x-1.5 px-3 py-1.5 text-xs font-semibold text-zinc-700 bg-white border border-zinc-200/80 hover:bg-zinc-50 rounded-xl shadow-2xs transition-all"
                title="Print Report"
            >
                <i data-lucide="printer" class="w-3.5 h-3.5 text-zinc-500"></i>
                <span>Print</span>
            </button>
        </div>
    </div>

    <!-- Filter & Date Range Bar -->
    <div class="bg-white rounded-2xl border border-zinc-200/80 p-3 sm:p-4 shadow-2xs no-print">
        <form method="GET" action="{{ route('admin.reports.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-2.5 items-end">
            <!-- Start Date -->
            <div>
                <label class="block text-[11px] font-semibold text-zinc-700 mb-1">From Date</label>
                <input
                    type="date"
                    name="start_date"
                    value="{{ $startDate }}"
                    class="w-full px-3 py-1.5 text-xs bg-zinc-50/70 border border-zinc-200/80 rounded-xl focus:outline-none focus:ring-1 focus:ring-zinc-400 focus:bg-white text-zinc-700"
                >
            </div>

            <!-- End Date -->
            <div>
                <label class="block text-[11px] font-semibold text-zinc-700 mb-1">To Date</label>
                <input
                    type="date"
                    name="end_date"
                    value="{{ $endDate }}"
                    class="w-full px-3 py-1.5 text-xs bg-zinc-50/70 border border-zinc-200/80 rounded-xl focus:outline-none focus:ring-1 focus:ring-zinc-400 focus:bg-white text-zinc-700"
                >
            </div>

            <!-- Report Type -->
            <div>
                <label class="block text-[11px] font-semibold text-zinc-700 mb-1">Report Focus</label>
                <select name="type" class="w-full px-3 py-1.5 text-xs bg-zinc-50/70 border border-zinc-200/80 rounded-xl focus:outline-none focus:ring-1 focus:ring-zinc-400 focus:bg-white text-zinc-700">
                    <option value="borrowings" {{ $reportType === 'borrowings' ? 'selected' : '' }}>Lending Transactions</option>
                    <option value="popular" {{ $reportType === 'popular' ? 'selected' : '' }}>Top Borrowed Titles</option>
                    <option value="users" {{ $reportType === 'users' ? 'selected' : '' }}>Member Activity</option>
                </select>
            </div>

            <!-- Filter Button -->
            <div>
                <button type="submit" class="w-full py-1.5 bg-zinc-950 hover:bg-zinc-800 text-white text-xs font-semibold rounded-xl transition-colors shadow-2xs">
                    Apply Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Divided Horizontal KPI Statistics Strip (Exact Reference UI) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 divide-y sm:divide-y-0 sm:divide-x divide-zinc-200/80 bg-white border border-zinc-200/80 rounded-2xl p-4 sm:p-5 shadow-2xs">
        <div class="sm:px-4 first:pl-0 last:pr-0 py-3 sm:py-0">
            <div class="flex items-center space-x-1.5 text-xs text-zinc-400 font-medium">
                <span>Total Circulation</span>
                <i data-lucide="info" class="w-3.5 h-3.5 text-zinc-300"></i>
            </div>
            <div class="text-2xl font-bold tracking-tight text-zinc-900 mt-1">
                {{ number_format($totalTransactions) }}
            </div>
            <div class="flex items-center space-x-1.5 text-[11px] text-zinc-400 mt-1">
                <span>period total</span>
                <span class="text-emerald-600 bg-emerald-50 px-1.5 py-0.2 rounded font-medium">Recorded</span>
            </div>
        </div>

        <div class="sm:px-4 py-3 sm:py-0">
            <div class="flex items-center space-x-1.5 text-xs text-zinc-400 font-medium">
                <span>Books Returned</span>
                <i data-lucide="info" class="w-3.5 h-3.5 text-zinc-300"></i>
            </div>
            <div class="text-2xl font-bold tracking-tight text-zinc-900 mt-1">
                {{ number_format($totalReturned) }}
            </div>
            <div class="flex items-center space-x-1.5 text-[11px] text-zinc-400 mt-1">
                <span>completed</span>
                <span class="text-emerald-600 bg-emerald-50 px-1.5 py-0.2 rounded font-medium">Archived</span>
            </div>
        </div>

        <div class="sm:px-4 py-3 sm:py-0">
            <div class="flex items-center space-x-1.5 text-xs text-zinc-400 font-medium">
                <span>Overdue Cases</span>
                <i data-lucide="info" class="w-3.5 h-3.5 text-zinc-300"></i>
            </div>
            <div class="text-2xl font-bold tracking-tight text-zinc-900 mt-1">
                {{ number_format($totalOverdue) }}
            </div>
            <div class="flex items-center space-x-1.5 text-[11px] text-zinc-400 mt-1">
                <span>delinquencies</span>
                <span class="{{ $totalOverdue > 0 ? 'text-red-600 bg-[#FEECEC]' : 'text-zinc-600 bg-zinc-100' }} px-1.5 py-0.2 rounded font-medium">
                    {{ $totalOverdue > 0 ? 'Requires Action' : 'All Clear' }}
                </span>
            </div>
        </div>

        <div class="sm:px-4 last:pr-0 py-3 sm:py-0">
            <div class="flex items-center space-x-1.5 text-xs text-zinc-400 font-medium">
                <span>Fines Collected</span>
                <i data-lucide="info" class="w-3.5 h-3.5 text-zinc-300"></i>
            </div>
            <div class="text-2xl font-bold tracking-tight text-zinc-900 mt-1">
                Rp {{ number_format($totalFines, 0, ',', '.') }}
            </div>
            <div class="flex items-center space-x-1.5 text-[11px] text-zinc-400 mt-1">
                <span>revenue</span>
                <span class="text-brand-orange bg-[#FFF7F2] px-1.5 py-0.2 rounded font-medium">Settled</span>
            </div>
        </div>
    </div>

    <!-- Report Table Content based on Type -->
    @if($reportType === 'borrowings')
        <div class="bg-white rounded-2xl border border-zinc-200/80 shadow-2xs overflow-hidden">
            <div class="p-4 sm:p-5 border-b border-zinc-100 flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-sm text-zinc-900">Circulation & Lending Transactions</h3>
                    <p class="text-xs text-zinc-400 mt-0.5">Timeframe: {{ date('d M Y', strtotime($startDate)) }} to {{ date('d M Y', strtotime($endDate)) }}</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="border-b border-zinc-100 text-[11px] font-medium text-zinc-400">
                            <th class="py-3 px-4">Transaction Code</th>
                            <th class="py-3 px-4">Borrower</th>
                            <th class="py-3 px-4">Book & ISBN</th>
                            <th class="py-3 px-4">Loan Date</th>
                            <th class="py-3 px-4">Due Date</th>
                            <th class="py-3 px-4">Returned Date</th>
                            <th class="py-3 px-4">Status</th>
                            <th class="py-3 px-4 text-right">Fines</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100/70 text-zinc-700">
                        @forelse($borrowings as $b)
                            <tr class="hover:bg-zinc-50/60 transition-colors">
                                <td class="py-3.5 px-4 font-mono font-medium text-zinc-900">{{ $b->borrowing_code }}</td>
                                <td class="py-3.5 px-4 font-semibold text-zinc-900">{{ $b->user->name ?? '-' }}</td>
                                <td class="py-3.5 px-4">
                                    <span class="font-medium text-zinc-800 block truncate max-w-xs">{{ $b->book->title ?? '-' }}</span>
                                    <span class="text-[10px] text-zinc-400 font-mono">ISBN: {{ $b->book->isbn ?? '-' }}</span>
                                </td>
                                <td class="py-3.5 px-4 text-zinc-500">{{ $b->borrow_date->format('d/m/Y') }}</td>
                                <td class="py-3.5 px-4 text-zinc-500">{{ $b->return_due_date->format('d/m/Y') }}</td>
                                <td class="py-3.5 px-4 text-zinc-500">{{ $b->actual_return_date ? $b->actual_return_date->format('d/m/Y') : '-' }}</td>
                                <td class="py-3.5 px-4">
                                    <x-badge-status :status="$b->isOverdue() ? 'overdue' : $b->status" />
                                </td>
                                <td class="py-3.5 px-4 text-right font-semibold {{ $b->fines > 0 ? 'text-red-600' : 'text-zinc-400' }}">
                                    {{ $b->fines > 0 ? 'Rp ' . number_format($b->fines, 0, ',', '.') : '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-8 text-center text-zinc-400">Tidak ada data transaksi peminjaman pada rentang tanggal ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($borrowings->hasPages())
                <div class="p-3.5 border-t border-zinc-100 bg-white no-print">
                    {{ $borrowings->links() }}
                </div>
            @endif
        </div>
    @elseif($reportType === 'popular')
        <div class="bg-white rounded-2xl border border-zinc-200/80 shadow-2xs overflow-hidden">
            <div class="p-4 sm:p-5 border-b border-zinc-100">
                <h3 class="font-bold text-sm text-zinc-900">Top 10 Most Borrowed Books</h3>
                <p class="text-xs text-zinc-400 mt-0.5">Based on circulation frequency during selected period</p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="border-b border-zinc-100 text-[11px] font-medium text-zinc-400">
                            <th class="py-3 px-4">Rank</th>
                            <th class="py-3 px-4">Book Title</th>
                            <th class="py-3 px-4">Category</th>
                            <th class="py-3 px-4">Author & Publisher</th>
                            <th class="py-3 px-4">Total Stock</th>
                            <th class="py-3 px-4 text-right">Frequency</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100/70 text-zinc-700">
                        @forelse($popularBooks as $index => $book)
                            <tr class="hover:bg-zinc-50/60 transition-colors">
                                <td class="py-3.5 px-4">
                                    <span class="w-5 h-5 rounded-full bg-zinc-100 text-zinc-600 font-bold flex items-center justify-center text-[10px]">
                                        {{ $index + 1 }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4">
                                    <div class="flex items-center space-x-2.5">
                                        <div class="w-8 h-10 rounded bg-zinc-100 overflow-hidden flex-shrink-0 border border-zinc-200/80">
                                            <img src="{{ $book->cover_url }}" alt="" class="w-full h-full object-cover">
                                        </div>
                                        <div>
                                            <span class="font-semibold text-zinc-900 block">{{ $book->title }}</span>
                                            <span class="font-mono text-[10px] text-zinc-400">ISBN: {{ $book->isbn }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3.5 px-4 font-medium text-zinc-700">{{ $book->category->name ?? '-' }}</td>
                                <td class="py-3.5 px-4 text-zinc-500">{{ $book->author }} ({{ $book->publisher }})</td>
                                <td class="py-3.5 px-4 font-medium text-zinc-700">{{ $book->stock }} copies</td>
                                <td class="py-3.5 px-4 text-right">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-semibold bg-[#EEF2FF] text-[#4F46E5]">
                                        {{ $book->borrowings_count }}x borrowed
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-8 text-center text-zinc-400">Tidak ada catatan peminjaman pada periode ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="bg-white rounded-2xl border border-zinc-200/80 shadow-2xs overflow-hidden">
            <div class="p-4 sm:p-5 border-b border-zinc-100">
                <h3 class="font-bold text-sm text-zinc-900">Member Borrowing Activity</h3>
                <p class="text-xs text-zinc-400 mt-0.5">Members with the highest circulation participation</p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="border-b border-zinc-100 text-[11px] font-medium text-zinc-400">
                            <th class="py-3 px-4">Rank</th>
                            <th class="py-3 px-4">Member Name</th>
                            <th class="py-3 px-4">Email & Phone</th>
                            <th class="py-3 px-4">Address</th>
                            <th class="py-3 px-4 text-right">Total Borrowed</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100/70 text-zinc-700">
                        @forelse($activeUsers as $index => $u)
                            <tr class="hover:bg-zinc-50/60 transition-colors">
                                <td class="py-3.5 px-4">
                                    <span class="w-5 h-5 rounded-full bg-zinc-100 text-zinc-600 font-bold flex items-center justify-center text-[10px]">
                                        {{ $index + 1 }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 font-semibold text-zinc-900">{{ $u->name }}</td>
                                <td class="py-3.5 px-4">
                                    <span class="text-zinc-800 block">{{ $u->email }}</span>
                                    <span class="text-zinc-400 text-[10px] block">{{ $u->phone ?: '-' }}</span>
                                </td>
                                <td class="py-3.5 px-4 text-zinc-500 max-w-xs truncate">{{ $u->address ?: '-' }}</td>
                                <td class="py-3.5 px-4 text-right">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-semibold bg-[#F0FDF4] text-[#16A34A]">
                                        {{ $u->borrowings_count }} books
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-8 text-center text-zinc-400">Tidak ada catatan peminjaman anggota pada periode ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</x-layouts.app>
