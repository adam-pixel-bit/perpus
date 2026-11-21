<x-layouts.app title="Audit Log Aktivitas - SIPUS Digital">
    <x-slot:pageTitle>Audit Log Aktivitas Sistem</x-slot:pageTitle>

    <!-- Subheader Toolbar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-2 border-b border-zinc-200/70">
        <div class="flex items-center space-x-2 text-xs text-zinc-600 font-medium">
            <div class="flex items-center space-x-1.5 px-3 py-1.5 bg-white border border-zinc-200/80 rounded-xl shadow-2xs">
                <i data-lucide="shield-alert" class="w-3.5 h-3.5 text-zinc-500"></i>
                <span class="font-semibold text-zinc-800">Security & Activity Audit Trail</span>
            </div>
            <span class="text-xs text-zinc-400">Total {{ $auditLogs->total() }} jejak audit tercatat</span>
        </div>
    </div>

    <!-- Filter & Search Bar -->
    <div class="bg-white rounded-2xl border border-zinc-200/80 p-3 sm:p-4 shadow-2xs">
        <form method="GET" action="{{ route('admin.audit-logs.index') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-2.5">
            <!-- Search Keyword -->
            <div class="relative sm:col-span-2">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-zinc-400">
                    <i data-lucide="search" class="w-3.5 h-3.5"></i>
                </div>
                <input
                    type="text"
                    name="search"
                    value="{{ $search }}"
                    placeholder="Cari deskripsi, IP address, atau nama pengguna..."
                    class="w-full pl-8.5 pr-4 py-1.5 text-xs bg-zinc-50/70 border border-zinc-200/80 rounded-xl focus:outline-none focus:ring-1 focus:ring-zinc-400 focus:bg-white text-zinc-900 placeholder-zinc-400"
                >
            </div>

            <!-- Action Filter -->
            <div>
                <select name="action" class="w-full px-3 py-1.5 text-xs bg-zinc-50/70 border border-zinc-200/80 rounded-xl focus:outline-none focus:ring-1 focus:ring-zinc-400 focus:bg-white text-zinc-700">
                    <option value="all">Semua Jenis Aksi</option>
                    @foreach($availableActions as $act)
                        <option value="{{ $act }}" {{ $action === $act ? 'selected' : '' }}>{{ ucwords(str_replace('_', ' ', $act)) }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Submit Filter & Reset -->
            <div class="flex items-center space-x-2">
                <button type="submit" class="flex-1 py-1.5 bg-zinc-950 hover:bg-zinc-800 text-white text-xs font-semibold rounded-xl transition-colors shadow-2xs cursor-pointer">
                    Filter
                </button>
                @if($search || ($action && $action !== 'all'))
                    <a href="{{ route('admin.audit-logs.index') }}" class="p-1.5 bg-zinc-100 hover:bg-zinc-200 text-zinc-600 rounded-xl transition-colors" title="Reset Filter">
                        <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Audit Logs Table -->
    <div class="bg-white rounded-2xl border border-zinc-200/80 overflow-hidden shadow-2xs">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-zinc-50/80 border-b border-zinc-200/80 text-[11px] font-semibold text-zinc-500 uppercase tracking-wider">
                    <tr>
                        <th class="px-4 py-3">Waktu</th>
                        <th class="px-4 py-3">Pengguna</th>
                        <th class="px-4 py-3">Aksi</th>
                        <th class="px-4 py-3">Deskripsi Aktivitas</th>
                        <th class="px-4 py-3">IP & Client</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200/70">
                    @forelse($auditLogs as $log)
                        <tr class="hover:bg-zinc-50/60 transition-colors">
                            <td class="px-4 py-3 whitespace-nowrap text-zinc-500 font-mono text-[11px]">
                                {{ $log->created_at->format('d/m/Y H:i:s') }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                @if($log->user)
                                    <div class="flex items-center space-x-2">
                                        <div class="w-6 h-6 rounded-full bg-zinc-100 border border-zinc-200 flex items-center justify-center font-bold text-zinc-700 text-[10px]">
                                            {{ strtoupper(substr($log->user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="font-semibold text-zinc-900">{{ $log->user->name }}</p>
                                            <p class="text-[10px] text-zinc-400">{{ $log->user->email }}</p>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-zinc-400 italic">Sistem / Tamu</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-medium font-mono
                                    @if(str_contains($log->action, 'delete') || str_contains($log->action, 'throttled') || str_contains($log->action, 'reject'))
                                        bg-rose-50 text-rose-700 border border-rose-200
                                    @elseif(str_contains($log->action, 'create') || str_contains($log->action, 'approve'))
                                        bg-emerald-50 text-emerald-700 border border-emerald-200
                                    @elseif(str_contains($log->action, 'login'))
                                        bg-sky-50 text-sky-700 border border-sky-200
                                    @else
                                        bg-zinc-100 text-zinc-700 border border-zinc-200
                                    @endif
                                ">
                                    {{ $log->action }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-zinc-700 font-medium max-w-md">
                                {{ $log->description }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-zinc-500 font-mono text-[11px]">
                                <span class="block text-zinc-800 font-medium">{{ $log->ip_address ?? '127.0.0.1' }}</span>
                                <span class="block text-[10px] text-zinc-400 truncate max-w-xs" title="{{ $log->user_agent }}">
                                    {{ Str::limit($log->user_agent, 28) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-zinc-400">
                                <i data-lucide="shield-check" class="w-8 h-8 mx-auto mb-2 text-zinc-300"></i>
                                <p>Belum ada jejak audit yang sesuai dengan filter.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($auditLogs->hasPages())
            <div class="px-4 py-3 border-t border-zinc-200/80 bg-zinc-50/50">
                {{ $auditLogs->links() }}
            </div>
        @endif
    </div>
</x-layouts.app>
