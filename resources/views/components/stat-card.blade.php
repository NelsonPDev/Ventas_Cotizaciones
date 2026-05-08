@props(['label', 'value', 'tone' => 'emerald'])

@php
    $tones = [
        'emerald' => 'border-emerald-200 bg-emerald-50 text-emerald-700',
        'sky' => 'border-sky-200 bg-sky-50 text-sky-700',
        'amber' => 'border-amber-200 bg-amber-50 text-amber-700',
        'rose' => 'border-rose-200 bg-rose-50 text-rose-700',
    ];
@endphp

<article class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
    <div class="mb-4 inline-flex rounded-lg border px-3 py-1 text-xs font-semibold {{ $tones[$tone] ?? $tones['emerald'] }}">
        {{ $label }}
    </div>
    <p class="text-2xl font-semibold text-slate-950">{{ $value }}</p>
</article>
