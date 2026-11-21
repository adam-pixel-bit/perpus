@props([
    'title',
    'value',
    'subtitle' => null,
    'icon' => 'activity',
    'variant' => 'blue', // blue, emerald, amber, rose, indigo, purple
])

@php
    $variantClasses = [
        'blue' => ['bg' => 'bg-blue-50', 'text' => 'text-blue-600', 'border' => 'border-blue-100'],
        'emerald' => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-600', 'border' => 'border-emerald-100'],
        'amber' => ['bg' => 'bg-amber-50', 'text' => 'text-amber-600', 'border' => 'border-amber-100'],
        'rose' => ['bg' => 'bg-rose-50', 'text' => 'text-rose-600', 'border' => 'border-rose-100'],
        'indigo' => ['bg' => 'bg-indigo-50', 'text' => 'text-indigo-600', 'border' => 'border-indigo-100'],
        'purple' => ['bg' => 'bg-purple-50', 'text' => 'text-purple-600', 'border' => 'border-purple-100'],
    ];

    $v = $variantClasses[$variant] ?? $variantClasses['blue'];
@endphp

<div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs hover:shadow-md transition-shadow flex items-center justify-between">
    <div class="space-y-1">
        <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">{{ $title }}</p>
        <p class="text-2xl font-extrabold text-slate-900 tracking-tight">{{ $value }}</p>
        @if($subtitle)
            <p class="text-xs text-slate-400 font-medium">{{ $subtitle }}</p>
        @endif
    </div>
    <div class="p-3.5 rounded-2xl {{ $v['bg'] }} {{ $v['border'] }} border flex items-center justify-center">
        <i data-lucide="{{ $icon }}" class="w-6 h-6 {{ $v['text'] }}"></i>
    </div>
</div>
