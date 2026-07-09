@props(['report'])

@php
$formatColors = [
    'pdf' => 'bg-red-50 text-red-600 dark:bg-red-500/10 dark:text-red-400',
    'csv' => 'bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400',
];
@endphp

<div class="flex items-center justify-between gap-3 px-5 py-4 transition-colors duration-150 hover:bg-slate-50 dark:hover:bg-slate-800/60">
    <div class="flex min-w-0 items-center gap-3">
        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg text-xs font-bold uppercase {{ $formatColors[$report->format] ?? 'bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-300' }}">
            {{ $report->format }}
        </span>
        <div class="min-w-0">
            <p class="truncate text-sm font-semibold text-slate-900 dark:text-white">{{ $report->title }}</p>
            <p class="mt-0.5 text-xs text-slate-500 dark:text-slate-400">
                {{ $report->repository->name ?? 'All repositories' }} &middot; {{ $report->generated_at?->diffForHumans() }}
            </p>
        </div>
    </div>

    <button type="button" class="shrink-0 rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-medium text-slate-600 transition hover:bg-slate-100 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-700">
        View
    </button>
</div>
