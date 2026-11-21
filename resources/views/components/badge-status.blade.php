@props(['status'])

@php
    $status = strtolower($status);
    $configs = [
        'pending' => [
            'label' => 'Menunggu Persetujuan',
            'bg' => 'bg-[#FEF6E8] text-[#D97706]', // Restock pastel amber
        ],
        'approved' => [
            'label' => 'Disetujui',
            'bg' => 'bg-[#EEF2FF] text-[#4F46E5]', // Pastel Indigo
        ],
        'borrowed' => [
            'label' => 'Sedang Dipinjam',
            'bg' => 'bg-[#F0FDF4] text-[#16A34A]', // Pastel Emerald
        ],
        'returned' => [
            'label' => 'Sudah Dikembalikan',
            'bg' => 'bg-zinc-100 text-zinc-600',
        ],
        'rejected' => [
            'label' => 'Ditolak',
            'bg' => 'bg-[#FEECEC] text-[#DC2626]', // Pastel Rose
        ],
        'overdue' => [
            'label' => 'Terlambat',
            'bg' => 'bg-[#FEE2E2] text-[#B91C1C] ring-1 ring-red-300',
        ],
    ];

    $cfg = $configs[$status] ?? [
        'label' => ucfirst($status),
        'bg' => 'bg-zinc-100 text-zinc-700',
    ];
@endphp

<span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-semibold tracking-tight {{ $cfg['bg'] }}">
    {{ $cfg['label'] }}
</span>
