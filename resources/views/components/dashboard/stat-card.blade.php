@props(['label', 'value', 'accent' => 'indigo'])

@php
$accents = [
    'indigo' => 'bg-indigo-50 text-indigo-600 dark:bg-indigo-500/10 dark:text-indigo-400',
    'red' => 'bg-red-50 text-red-600 dark:bg-red-500/10 dark:text-red-400',
    'emerald' => 'bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400',
    'amber' => 'bg-amber-50 text-amber-600 dark:bg-amber-500/10 dark:text-amber-400',
][$accent] ?? 'bg-indigo-50 text-indigo-600 dark:bg-indigo-500/10 dark:text-indigo-400';
@endphp

<div class="group rounded-xl border border-slate-200 bg-white p-5 shadow-sm transition-all duration-150 hover:-translate-y-0.5 hover:shadow-md dark:border-slate-800 dark:bg-slate-900">
    <div class="flex items-center justify-between">
        <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ $label }}</p>
        <span class="flex h-9 w-9 items-center justify-center rounded-lg transition-transform duration-150 group-hover:scale-105 {{ $accents }}">
            @isset($icon){{ $icon }}@endisset
        </span>
    </div>
    <p class="mt-3 text-2xl font-bold text-slate-900 dark:text-white">{{ $value }}</p>
    @isset($footer)
        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">{{ $footer }}</p>
    @endisset
</div>
