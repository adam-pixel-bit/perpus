@props(['status'])

@php
    $status = strtolower($status);
    $configs = [
        'pending' => [
            'label' => 'Menunggu Persetujuan',
            'bg' => 'bg-amber-50 text-amber-700 border-amber-200',
            'dot' => 'bg-amber-500',
        ],
        'approved' => [
            'label' => 'Disetujui',
            'bg' => 'bg-blue-50 text-blue-700 border-blue-200',
            'dot' => 'bg-blue-500',
        ],
        'borrowed' => [
            'label' => 'Sedang Dipinjam',
            'bg' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            'dot' => 'bg-emerald-500',
        ],
        'returned' => [
            'label' => 'Sudah Dikembalikan',
            'bg' => 'bg-slate-100 text-slate-700 border-slate-200',
            'dot' => 'bg-slate-400',
        ],
        'rejected' => [
            'label' => 'Ditolak',
            'bg' => 'bg-rose-50 text-rose-700 border-rose-200',
            'dot' => 'bg-rose-500',
        ],
        'overdue' => [
            'label' => 'Terlambat',
            'bg' => 'bg-red-50 text-red-700 border-red-200 animate-pulse',
            'dot' => 'bg-red-600',
        ],
    ];

    $cfg = $configs[$status] ?? [
        'label' => ucfirst($status),
        'bg' => 'bg-slate-50 text-slate-700 border-slate-200',
        'dot' => 'bg-slate-500',
    ];
@endphp

<span class="inline-flex items-center space-x-1.5 px-2.5 py-1 rounded-full text-xs font-semibold border {{ $cfg['bg'] }}">
    <span class="w-1.5 h-1.5 rounded-full {{ $cfg['dot'] }}"></span>
    <span>{{ $cfg['label'] }}</span>
</span>
