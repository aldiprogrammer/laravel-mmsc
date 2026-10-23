@props([
    'tone' => 'slate',
])

@php
    $tones = [
        'blue' => 'bg-blue-50 text-blue-700 ring-blue-100',
        'emerald' => 'bg-emerald-50 text-emerald-700 ring-emerald-100',
        'amber' => 'bg-amber-50 text-amber-700 ring-amber-100',
        'rose' => 'bg-rose-50 text-rose-700 ring-rose-100',
        'indigo' => 'bg-indigo-50 text-indigo-700 ring-indigo-100',
        'sky' => 'bg-sky-50 text-sky-700 ring-sky-100',
        'slate' => 'bg-slate-100 text-slate-600 ring-slate-200',
    ];
@endphp

<span {!! $attributes->merge(['class' => 'inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-[11px] font-medium ring-1 ring-inset '.($tones[$tone] ?? $tones['slate'])]) !!}>
    {{ $slot }}
</span>
