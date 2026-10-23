@props([
    'label',
    'value',
    'hint',
    'icon' => 'dashboard',
    'tone' => 'blue',
])

@php
    $tones = [
        'blue' => [
            'icon' => 'bg-blue-50 text-blue-600 ring-blue-100',
            'bar' => 'bg-blue-500/70',
        ],
        'indigo' => [
            'icon' => 'bg-indigo-50 text-indigo-600 ring-indigo-100',
            'bar' => 'bg-indigo-500/70',
        ],
        'sky' => [
            'icon' => 'bg-sky-50 text-sky-600 ring-sky-100',
            'bar' => 'bg-sky-500/70',
        ],
        'cyan' => [
            'icon' => 'bg-cyan-50 text-cyan-600 ring-cyan-100',
            'bar' => 'bg-cyan-500/70',
        ],
    ];

    $palette = $tones[$tone] ?? $tones['blue'];
    $sparkline = [40, 65, 45, 80, 60, 90, 70];
@endphp

<div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition-shadow hover:shadow-md">
    <div class="flex items-start justify-between gap-4">
        <div>
            <p class="text-sm font-medium text-slate-500">{{ $label }}</p>
            <p class="mt-2 text-3xl font-semibold tracking-tight text-slate-900">{{ number_format($value) }}</p>
        </div>

        <span class="grid size-11 shrink-0 place-items-center rounded-xl ring-1 {{ $palette['icon'] }}">
            <x-admin.icon :name="$icon" class="size-5" />
        </span>
    </div>

    <div class="mt-5 flex items-end justify-between gap-4">
        <p class="flex items-center gap-1.5 text-xs text-slate-500">
            <span class="size-1.5 rounded-full {{ $palette['bar'] }}"></span>
            {{ $hint }}
        </p>

        <div class="flex h-6 items-end gap-1" aria-hidden="true">
            @foreach ($sparkline as $height)
                <span class="w-1.5 rounded-full {{ $palette['bar'] }}" style="height: {{ $height }}%"></span>
            @endforeach
        </div>
    </div>
</div>
