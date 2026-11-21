<x-layouts.app title="Detail Pengguna">
    <div class="space-y-6">
        <!-- Back Link & Action Bar -->
        <div class="flex items-center justify-between pb-2 border-b border-zinc-200/70">
            <a href="{{ route('admin.users.index') }}" class="inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold text-zinc-600 hover:text-zinc-950 bg-white border border-zinc-200/80 shadow-2xs transition-colors">
                <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                <span>Back to Users</span>
            </a>
            <div class="flex items-center space-x-2">
                <a href="{{ route('admin.users.edit', $user->id) }}" class="inline-flex items-center space-x-1.5 px-3.5 py-1.5 bg-zinc-950 hover:bg-zinc-800 text-white rounded-xl text-xs font-semibold shadow-2xs transition-all">
                    <i data-lucide="edit-3" class="w-3.5 h-3.5"></i>
                    <span>Edit Profile</span>
                </a>
            </div>
        </div>

        <!-- User Profile Card -->
        <div class="bg-white rounded-2xl border border-zinc-200/80 shadow-2xs p-5 sm:p-6">
            <div class="flex flex-col sm:flex-row items-start sm:items-center space-y-4 sm:space-y-0 sm:space-x-5 pb-5 border-b border-zinc-100">
                <div class="w-16 h-16 rounded-2xl {{ $user->isAdmin() ? 'bg-zinc-950 text-white' : 'bg-zinc-100 text-zinc-800' }} border border-zinc-200 font-bold flex items-center justify-center text-xl shadow-2xs">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div class="space-y-1">
                    <div class="flex items-center space-x-2">
                        <h1 class="text-xl font-bold text-zinc-900">{{ $user->name }}</h1>
                        @if($user->isAdmin())
                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-semibold bg-zinc-950 text-white">
                                Administrator
                            </span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-semibold bg-zinc-100 text-zinc-700">
                                Member
                            </span>
                        @endif

                        @if($user->is_active)
                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-semibold bg-[#F0FDF4] text-[#16A34A]">
                                Active
                            </span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-semibold bg-[#FEECEC] text-[#DC2626]">
                                Inactive
                            </span>
                        @endif
                    </div>
                    <p class="text-xs text-zinc-400 font-medium">{{ $user->email }} • Joined {{ $user->created_at->format('d M Y') }}</p>
                </div>
            </div>

            <!-- Profile Details Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5 pt-5 text-xs">
                <div class="p-3.5 rounded-xl bg-zinc-50/70 border border-zinc-200/80 space-y-0.5">
                    <span class="text-zinc-400 block font-medium text-[11px]">Phone Number</span>
                    <span class="font-semibold text-zinc-800 text-xs">{{ $user->phone ?: '-' }}</span>
                </div>
                <div class="p-3.5 rounded-xl bg-zinc-50/70 border border-zinc-200/80 space-y-0.5 sm:col-span-2">
                    <span class="text-zinc-400 block font-medium text-[11px]">Address</span>
                    <span class="font-medium text-zinc-800 text-xs">{{ $user->address ?: '-' }}</span>
                </div>
            </div>
        </div>

        <!-- Borrowing History of this User -->
        <div class="bg-white rounded-2xl border border-zinc-200/80 shadow-2xs overflow-hidden">
            <div class="p-4 sm:p-5 border-b border-zinc-100 flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-sm text-zinc-900">Borrowing History</h3>
                    <p class="text-xs text-zinc-400 mt-0.5">All transactions requested by {{ $user->name }}</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="border-b border-zinc-100 text-[11px] font-medium text-zinc-400">
                            <th class="py-3 px-4">Transaction Code</th>
                            <th class="py-3 px-4">Book Title</th>
                            <th class="py-3 px-4">Borrow Date</th>
                            <th class="py-3 px-4">Due Date</th>
                            <th class="py-3 px-4">Returned Date</th>
                            <th class="py-3 px-4">Status</th>
                            <th class="py-3 px-4">Fines</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100/70 text-zinc-700">
                        @forelse($borrowings as $b)
                            <tr class="hover:bg-zinc-50/60 transition-colors">
                                <td class="py-3 px-4 font-mono font-medium text-zinc-900">{{ $b->borrowing_code }}</td>
                                <td class="py-3 px-4 font-medium text-zinc-900 max-w-xs truncate">{{ $b->book->title ?? '-' }}</td>
                                <td class="py-3 px-4 text-zinc-500">{{ $b->borrow_date->format('d M Y') }}</td>
                                <td class="py-3 px-4 text-zinc-500">{{ $b->return_due_date->format('d M Y') }}</td>
                                <td class="py-3 px-4 text-zinc-500">{{ $b->actual_return_date ? $b->actual_return_date->format('d M Y') : '-' }}</td>
                                <td class="py-3 px-4">
                                    <x-badge-status :status="$b->isOverdue() ? 'overdue' : $b->status" />
                                </td>
                                <td class="py-3 px-4 font-semibold {{ $b->fines > 0 ? 'text-red-600 font-bold' : 'text-zinc-400' }}">
                                    {{ $b->fines > 0 ? 'Rp ' . number_format($b->fines, 0, ',', '.') : '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-8 text-center text-zinc-400">Belum ada riwayat transaksi peminjaman.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layouts.app>
