@props(['analysis'])

@php
$typeLabels = [
    'code_review' => 'Code Review',
    'security' => 'Security',
    'quality' => 'Full Analysis',
    'tech_debt' => 'Technical Debt',
    'documentation' => 'Documentation',
];
$score = $analysis->relationLoaded('categories') && $analysis->categories->isNotEmpty()
    ? round($analysis->categories->avg('score'))
    : null;
@endphp

<a href="{{ route('analyses.show', $analysis) }}" class="flex items-center justify-between gap-4 px-5 py-4 transition-colors duration-150 hover:bg-slate-50 dark:hover:bg-slate-800/60">
    <div class="min-w-0">
        <p class="truncate text-sm font-semibold text-slate-900 dark:text-white">
            {{ $typeLabels[$analysis->type] ?? ucfirst($analysis->type) }}
            <span class="font-normal text-slate-400 dark:text-slate-600">&middot;</span>
            <span class="font-normal text-slate-500 dark:text-slate-400">{{ $analysis->repository->full_name ?? '—' }}</span>
        </p>
        <p class="mt-0.5 text-xs text-slate-500 dark:text-slate-400">
            {{ $analysis->completed_at?->diffForHumans() ?? 'in progress' }}
        </p>
    </div>

    <div class="flex shrink-0 items-center gap-3">
        @if ($analysis->model_used)
            <span class="hidden items-center rounded-full bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-500 dark:bg-slate-800 dark:text-slate-400 sm:inline-flex" title="{{ __('Different AI models can score the same repository differently.') }}">
                {{ $analysis->model_used }}
            </span>
        @endif
        @if ($score !== null)
            <span class="inline-flex items-center rounded-full bg-slate-100 px-2 py-0.5 text-xs font-semibold text-slate-700 dark:bg-slate-800 dark:text-slate-200">
                {{ $score }}
            </span>
        @endif
        <x-dashboard.status-badge :status="$analysis->status" />
    </div>
</a>
