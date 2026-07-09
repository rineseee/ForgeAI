@props(['status'])

@php
$styles = [
    'completed' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/15 dark:text-emerald-400',
    'running' => 'bg-indigo-100 text-indigo-700 dark:bg-indigo-500/15 dark:text-indigo-400',
    'queued' => 'bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-300',
    'failed' => 'bg-red-100 text-red-700 dark:bg-red-500/15 dark:text-red-400',
][$status] ?? 'bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-300';
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center gap-1 rounded-full px-2 py-0.5 text-xs font-medium capitalize $styles"]) }}>
    <span class="h-1.5 w-1.5 rounded-full bg-current"></span>
    {{ $status }}
</span>
