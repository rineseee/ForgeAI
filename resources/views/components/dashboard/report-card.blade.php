@props(['report'])

@php
$score = $report->categories->isNotEmpty() ? round($report->categories->avg('score')) : null;
$scoreColor = match (true) {
    $score === null => 'bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-300',
    $score >= 80 => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/15 dark:text-emerald-400',
    $score >= 50 => 'bg-amber-100 text-amber-700 dark:bg-amber-500/15 dark:text-amber-400',
    default => 'bg-red-100 text-red-700 dark:bg-red-500/15 dark:text-red-400',
};
@endphp

<a href="{{ route('analyses.show', $report) }}" class="flex items-center justify-between gap-3 px-5 py-4 transition-colors duration-150 hover:bg-slate-50 dark:hover:bg-slate-800/60">
    <div class="flex min-w-0 items-center gap-3">
        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg text-sm font-bold {{ $scoreColor }}">
            {{ $score ?? '—' }}
        </span>
        <div class="min-w-0">
            <p class="truncate text-sm font-semibold text-slate-900 dark:text-white">{{ $report->repository->full_name ?? 'Unknown repository' }}</p>
            <p class="mt-0.5 text-xs text-slate-500 dark:text-slate-400">
                {{ $report->completed_at?->diffForHumans() }}
                @if ($report->model_used)
                    &middot; {{ $report->model_used }}
                @endif
            </p>
        </div>
    </div>

    <svg class="h-4 w-4 shrink-0 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
</a>
