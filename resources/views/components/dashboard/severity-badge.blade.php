@props(['severity'])

@php
$styles = [
    'critical' => 'bg-red-100 text-red-700 dark:bg-red-500/15 dark:text-red-400',
    'high' => 'bg-orange-100 text-orange-700 dark:bg-orange-500/15 dark:text-orange-400',
    'medium' => 'bg-amber-100 text-amber-700 dark:bg-amber-500/15 dark:text-amber-400',
    'low' => 'bg-sky-100 text-sky-700 dark:bg-sky-500/15 dark:text-sky-400',
    'info' => 'bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-300',
][$severity] ?? 'bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-300';
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium capitalize $styles"]) }}>
    {{ $slot->isNotEmpty() ? $slot : $severity }}
</span>
