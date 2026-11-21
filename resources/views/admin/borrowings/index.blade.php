<x-layouts.app title="Manajemen Peminjaman">
    <!-- Subheader Toolbar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-2 border-b border-zinc-200/70">
        <div class="flex items-center flex-wrap gap-2 text-xs text-zinc-600 font-medium">
            <div class="flex items-center space-x-1.5 px-3 py-1.5 bg-white border border-zinc-200/80 rounded-xl shadow-2xs">
                <i data-lucide="layers" class="w-3.5 h-3.5 text-zinc-500"></i>
                <span class="font-semibold text-zinc-800">Borrowings Table</span>
                <i data-lucide="chevron-down" class="w-3 h-3 text-zinc-400"></i>
            </div>

            <!-- Status Tabs -->
            <a href="{{ route('admin.borrowings.index') }}" class="px-3 py-1.5 rounded-xl text-xs font-semibold transition-all {{ empty($status) || $status === 'all' ? 'bg-zinc-950 text-white shadow-2xs' : 'bg-white text-zinc-600 border border-zinc-200/80 hover:bg-zinc-50' }}">
                All ({{ $borrowedCount + $pendingCount + $overdueCount + $returnedCount }})
            </a>
            <a href="{{ route('admin.borrowings.index', ['status' => 'pending']) }}" class="px-3 py-1.5 rounded-xl text-xs font-semibold transition-all flex items-center space-x-1.5 {{ $status === 'pending' ? 'bg-brand-orange text-white shadow-2xs' : 'bg-white text-zinc-600 border border-zinc-200/80 hover:bg-zinc-50' }}">
                <span>Pending</span>
                @if($pendingCount > 0)
                    <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ $status === 'pending' ? 'bg-white text-brand-orange font-bold' : 'bg-[#FEF6E8] text-amber-700 font-bold' }}">
                        {{ $pendingCount }}
                    </span>
                @endif
            </a>
            <a href="{{ route('admin.borrowings.index', ['status' => 'borrowed']) }}" class="px-3 py-1.5 rounded-xl text-xs font-semibold transition-all flex items-center space-x-1.5 {{ $status === 'borrowed' ? 'bg-zinc-950 text-white shadow-2xs' : 'bg-white text-zinc-600 border border-zinc-200/80 hover:bg-zinc-50' }}">
                <span>Active Loans</span>
                <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ $status === 'borrowed' ? 'bg-white text-zinc-950 font-bold' : 'bg-zinc-100 text-zinc-700 font-bold' }}">
                    {{ $borrowedCount }}
                </span>
            </a>
            <a href="{{ route('admin.borrowings.index', ['status' => 'overdue']) }}" class="px-3 py-1.5 rounded-xl text-xs font-semibold transition-all flex items-center space-x-1.5 {{ $status === 'overdue' ? 'bg-[#EF4444] text-white shadow-2xs' : 'bg-white text-zinc-600 border border-zinc-200/80 hover:bg-zinc-50' }}">
                <span>Overdue</span>
                @if($overdueCount > 0)
                    <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ $status === 'overdue' ? 'bg-white text-red-600 font-bold' : 'bg-[#FEECEC] text-red-600 font-bold' }}">
                        {{ $overdueCount }}
                    </span>
                @endif
            </a>
            <a href="{{ route('admin.borrowings.index', ['status' => 'returned']) }}" class="px-3 py-1.5 rounded-xl text-xs font-semibold transition-all {{ $status === 'returned' ? 'bg-zinc-950 text-white shadow-2xs' : 'bg-white text-zinc-600 border border-zinc-200/80 hover:bg-zinc-50' }}">
                <span>Returned ({{ $returnedCount }})</span>
            </a>
        </div>

        <div class="flex items-center space-x-2">
            <a href="{{ route('admin.reports.index') }}" class="flex items-center space-x-1.5 px-3 py-1.5 text-xs font-semibold text-zinc-700 bg-white border border-zinc-200/80 rounded-xl hover:bg-zinc-50 shadow-2xs transition-all">
                <i data-lucide="download" class="w-3.5 h-3.5 text-zinc-500"></i>
                <span>Export</span>
            </a>
        </div>
    </div>

    <!-- Filter & Search Bar -->
    <div class="bg-white rounded-2xl border border-zinc-200/80 p-3 sm:p-4 shadow-2xs">
        <form method="GET" action="{{ route('admin.borrowings.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-2.5">
            @if($status && $status !== 'all')
                <input type="hidden" name="status" value="{{ $status }}">
            @endif

            <!-- Keyword -->
            <div class="relative lg:col-span-2">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-zinc-400">
                    <i data-lucide="search" class="w-3.5 h-3.5"></i>
                </div>
                <input
                    type="text"
                    name="search"
                    value="{{ $search }}"
                    placeholder="Search transaction code, member, book..."
                    class="w-full pl-8.5 pr-4 py-1.5 text-xs bg-zinc-50/70 border border-zinc-200/80 rounded-xl focus:outline-none focus:ring-1 focus:ring-zinc-400 focus:bg-white text-zinc-900 placeholder-zinc-400"
                >
            </div>

            <!-- Start Date -->
            <div>
                <input
                    type="date"
                    name="start_date"
                    value="{{ $startDate }}"
                    placeholder="From Date"
                    class="w-full px-3 py-1.5 text-xs bg-zinc-50/70 border border-zinc-200/80 rounded-xl focus:outline-none focus:ring-1 focus:ring-zinc-400 focus:bg-white text-zinc-700"
                >
            </div>

            <!-- End Date -->
            <div>
                <input
                    type="date"
                    name="end_date"
                    value="{{ $endDate }}"
                    placeholder="To Date"
                    class="w-full px-3 py-1.5 text-xs bg-zinc-50/70 border border-zinc-200/80 rounded-xl focus:outline-none focus:ring-1 focus:ring-zinc-400 focus:bg-white text-zinc-700"
                >
            </div>

            <!-- Buttons -->
            <div class="flex items-center space-x-2">
                <button type="submit" class="flex-1 py-1.5 bg-zinc-950 hover:bg-zinc-800 text-white text-xs font-semibold rounded-xl transition-colors shadow-2xs">
                    Filter
                </button>
                @if($search || $startDate || $endDate)
                    <a href="{{ route('admin.borrowings.index', ['status' => $status]) }}" class="p-1.5 bg-zinc-100 hover:bg-zinc-200 text-zinc-600 rounded-xl transition-colors" title="Reset Filter">
                        <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Borrowings Table (Styled like Uxerflow Product Table) -->
    <div class="bg-white rounded-2xl border border-zinc-200/80 shadow-2xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="border-b border-zinc-100 text-[11px] font-medium text-zinc-400">
                        <th class="py-3 px-4 w-10">
                            <input type="checkbox" class="w-3.5 h-3.5 rounded border-zinc-300 text-brand-orange focus:ring-0">
                        </th>
                        <th class="py-3 px-4">Code & Date</th>
                        <th class="py-3 px-4">Borrower</th>
                        <th class="py-3 px-4">Book Title & ISBN</th>
                        <th class="py-3 px-4">Loan Schedule</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4">Fines & Note</th>
                        <th class="py-3 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100/70 text-zinc-700">
                    @forelse($borrowings as $b)
                        <tr class="hover:bg-zinc-50/60 transition-colors {{ $b->isOverdue() && $b->status === 'borrowed' ? 'bg-[#FFF7F2]/40' : '' }}">
                            <td class="py-3.5 px-4">
                                <input type="checkbox" class="w-3.5 h-3.5 rounded border-zinc-300 text-brand-orange focus:ring-0">
                            </td>

                            <!-- Code -->
                            <td class="py-3.5 px-4 font-mono font-medium text-zinc-900">
                                {{ $b->borrowing_code }}
                                <span class="text-[10px] text-zinc-400 block font-sans font-normal">{{ $b->created_at->format('d M Y, H:i') }}</span>
                            </td>

                            <!-- User -->
                            <td class="py-3.5 px-4">
                                <div class="flex items-center space-x-2">
                                    <div class="w-6 h-6 rounded-full bg-zinc-100 flex items-center justify-center text-[10px] font-bold text-zinc-600 border border-zinc-200">
                                        {{ strtoupper(substr($b->user->name ?? 'U', 0, 1)) }}
                                    </div>
                                    <div class="truncate max-w-[140px]">
                                        <span class="font-semibold text-zinc-900 block truncate">{{ $b->user->name ?? 'User Dihapus' }}</span>
                                        <span class="text-[10px] text-zinc-400 block truncate">{{ $b->user->email ?? '-' }}</span>
                                    </div>
                                </div>
                            </td>

                            <!-- Book -->
                            <td class="py-3.5 px-4">
                                <span class="font-medium text-zinc-800 block truncate max-w-[200px]">{{ $b->book->title ?? 'Buku Dihapus' }}</span>
                                <span class="text-[10px] font-mono text-zinc-400 block">ISBN: {{ $b->book->isbn ?? '-' }}</span>
                            </td>

                            <!-- Dates -->
                            <td class="py-3.5 px-4">
                                <div class="space-y-0.5">
                                    <span class="text-zinc-600 block text-[11px]">Out: <strong class="text-zinc-800">{{ $b->borrow_date->format('d M Y') }}</strong></span>
                                    <span class="block text-[11px] {{ $b->isOverdue() ? 'text-red-600 font-bold' : 'text-zinc-500' }}">
                                        Due: <strong>{{ $b->return_due_date->format('d M Y') }}</strong>
                                    </span>
                                    @if($b->actual_return_date)
                                        <span class="text-[10px] text-emerald-600 font-medium block">
                                            Ret: {{ $b->actual_return_date->format('d M Y') }}
                                        </span>
                                    @endif
                                </div>
                            </td>

                            <!-- Status Badge -->
                            <td class="py-3.5 px-4">
                                <x-badge-status :status="$b->isOverdue() ? 'overdue' : $b->status" />
                                @if($b->isOverdue() && $b->status === 'borrowed')
                                    <span class="text-[10px] text-red-600 font-semibold block mt-1">
                                        Overdue {{ $b->daysOverdue() }}d
                                    </span>
                                @endif
                            </td>

                            <!-- Fines / Note -->
                            <td class="py-3.5 px-4 max-w-xs">
                                @if($b->fines > 0)
                                    <span class="font-semibold text-red-600 block">Rp {{ number_format($b->fines, 0, ',', '.') }}</span>
                                @endif
                                <span class="text-[10px] text-zinc-400 block truncate" title="{{ $b->notes }}">
                                    {{ $b->notes ?: 'No note' }}
                                </span>
                            </td>

                            <!-- Actions -->
                            <td class="py-3.5 px-4 text-right">
                                <div class="inline-flex items-center justify-end space-x-1.5">
                                    @if($b->status === 'pending')
                                        <!-- Approve -->
                                        <form method="POST" action="{{ route('admin.borrowings.approve', $b->id) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="px-2.5 py-1 bg-zinc-950 hover:bg-zinc-800 text-white rounded-lg font-semibold text-[11px] shadow-2xs transition-colors">
                                                Approve
                                            </button>
                                        </form>

                                        <!-- Reject Trigger -->
                                        <button
                                            type="button"
                                            onclick="openRejectModal({{ $b->id }}, '{{ $b->borrowing_code }}')"
                                            class="px-2 py-1 bg-white hover:bg-red-50 text-red-600 border border-zinc-200/80 hover:border-red-200 rounded-lg font-semibold text-[11px] transition-colors"
                                        >
                                            Reject
                                        </button>
                                    @elseif(in_array($b->status, ['borrowed', 'approved']))
                                        <!-- Process Return Trigger -->
                                        <button
                                            type="button"
                                            onclick="openReturnModal({{ $b->id }}, '{{ $b->borrowing_code }}', '{{ addslashes($b->book->title ?? '') }}', {{ $b->isOverdue() ? $b->daysOverdue() * 2000 : 0 }})"
                                            class="px-2.5 py-1 bg-zinc-950 hover:bg-zinc-800 text-white rounded-lg font-semibold text-[11px] shadow-2xs transition-colors flex items-center space-x-1"
                                        >
                                            <i data-lucide="corner-down-left" class="w-3 h-3"></i>
                                            <span>Return</span>
                                        </button>
                                    @else
                                        <span class="text-[11px] text-zinc-400">Completed</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-12 text-center text-zinc-400">
                                <i data-lucide="inbox" class="w-8 h-8 mx-auto mb-2 text-zinc-300"></i>
                                <p class="font-semibold text-zinc-700">No transactions found</p>
                                <p class="text-xs text-zinc-400 mt-1">Try another filter or search term.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($borrowings->hasPages())
            <div class="p-3.5 border-t border-zinc-100 bg-white">
                {{ $borrowings->links() }}
            </div>
        @endif
    </div>

    <!-- Reject Modal -->
    <div id="reject-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-zinc-900/40 backdrop-blur-xs hidden">
        <div class="bg-white rounded-2xl border border-zinc-200 shadow-xl max-w-md w-full p-5 space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-zinc-100">
                <div class="flex items-center space-x-2">
                    <div class="w-7 h-7 rounded-lg bg-[#FEECEC] text-red-600 flex items-center justify-center">
                        <i data-lucide="x-circle" class="w-4 h-4"></i>
                    </div>
                    <h3 class="font-bold text-sm text-zinc-900">Reject Borrow Request</h3>
                </div>
                <button type="button" onclick="closeRejectModal()" class="text-zinc-400 hover:text-zinc-600 p-1">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>

            <p class="text-xs text-zinc-500">
                You are about to reject loan request <strong id="reject-code" class="text-zinc-900"></strong>. The member will be notified.
            </p>

            <form id="reject-form" method="POST" action="">
                @csrf
                @method('PATCH')
                <div>
                    <label for="admin_note" class="block text-[11px] font-semibold text-zinc-700 mb-1">
                        Reason for Rejection <span class="text-red-500">*</span>
                    </label>
                    <textarea
                        name="admin_note"
                        rows="3"
                        required
                        placeholder="e.g. Member has unpaid overdue fees or outstanding copies..."
                        class="w-full px-3 py-2 text-xs bg-zinc-50/70 border border-zinc-200/80 rounded-xl focus:outline-none focus:ring-1 focus:ring-zinc-400 focus:bg-white text-zinc-900 resize-none"
                    ></textarea>
                </div>

                <div class="pt-3 flex items-center justify-end space-x-2">
                    <button type="button" onclick="closeRejectModal()" class="px-3.5 py-1.5 text-xs font-semibold text-zinc-600 bg-zinc-100 hover:bg-zinc-200 rounded-xl transition-colors">
                        Cancel
                    </button>
                    <button type="submit" class="px-3.5 py-1.5 bg-red-600 hover:bg-red-700 text-white text-xs font-semibold rounded-xl shadow-2xs transition-colors">
                        Confirm Rejection
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Return Modal -->
    <div id="return-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-zinc-900/40 backdrop-blur-xs hidden">
        <div class="bg-white rounded-2xl border border-zinc-200 shadow-xl max-w-md w-full p-5 space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-zinc-100">
                <div class="flex items-center space-x-2">
                    <div class="w-7 h-7 rounded-lg bg-zinc-100 text-zinc-800 flex items-center justify-center">
                        <i data-lucide="corner-down-left" class="w-4 h-4"></i>
                    </div>
                    <h3 class="font-bold text-sm text-zinc-900">Process Book Return</h3>
                </div>
                <button type="button" onclick="closeReturnModal()" class="text-zinc-400 hover:text-zinc-600 p-1">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>

            <div>
                <p class="text-xs text-zinc-400">Book Title:</p>
                <p id="return-book-title" class="text-xs font-bold text-zinc-900 mt-0.5"></p>
                <p class="text-[10px] text-zinc-400 font-mono mt-0.5">Code: <span id="return-code"></span></p>
            </div>

            <form id="return-form" method="POST" action="" class="space-y-3">
                @csrf
                @method('PATCH')

                <div>
                    <label for="return-fines" class="block text-[11px] font-semibold text-zinc-700 mb-1">
                        Overdue / Damage Fines (Rp)
                    </label>
                    <input
                        type="number"
                        id="return-fines"
                        name="fines"
                        value="0"
                        min="0"
                        step="500"
                        class="w-full px-3 py-1.5 text-xs bg-zinc-50/70 border border-zinc-200/80 rounded-xl focus:outline-none focus:ring-1 focus:ring-zinc-400 focus:bg-white text-zinc-900 font-semibold"
                    >
                    <p class="text-[10px] text-zinc-400 mt-0.5">Auto-calculated Rp 2.000/day for overdue days.</p>
                </div>

                <div>
                    <label for="return_note" class="block text-[11px] font-semibold text-zinc-700 mb-1">
                        Book Condition Notes
                    </label>
                    <textarea
                        id="return_note"
                        name="admin_note"
                        rows="2"
                        placeholder="e.g. Received in pristine condition, all pages intact..."
                        class="w-full px-3 py-2 text-xs bg-zinc-50/70 border border-zinc-200/80 rounded-xl focus:outline-none focus:ring-1 focus:ring-zinc-400 focus:bg-white text-zinc-900 resize-none"
                    >Buku diterima dalam kondisi baik dan lengkap.</textarea>
                </div>

                <div class="pt-2 flex items-center justify-end space-x-2">
                    <button type="button" onclick="closeReturnModal()" class="px-3.5 py-1.5 text-xs font-semibold text-zinc-600 bg-zinc-100 hover:bg-zinc-200 rounded-xl transition-colors">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-1.5 bg-zinc-950 hover:bg-zinc-800 text-white text-xs font-semibold rounded-xl shadow-2xs transition-colors">
                        Confirm Return
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- JavaScript for Modals -->
    <script>
        function openRejectModal(id, code) {
            document.getElementById('reject-code').innerText = code;
            document.getElementById('reject-form').action = '/admin/borrowings/' + id + '/reject';
            document.getElementById('reject-modal').classList.remove('hidden');
        }

        function closeRejectModal() {
            document.getElementById('reject-modal').classList.add('hidden');
        }

        function openReturnModal(id, code, title, suggestedFines) {
            document.getElementById('return-code').innerText = code;
            document.getElementById('return-book-title').innerText = title;
            document.getElementById('return-fines').value = suggestedFines;
            document.getElementById('return-form').action = '/admin/borrowings/' + id + '/return';
            document.getElementById('return-modal').classList.remove('hidden');
        }

        function closeReturnModal() {
            document.getElementById('return-modal').classList.add('hidden');
        }
    </script>
</x-layouts.app>
