@props(['analysis'])

@php
$typeLabels = [
    'code_review' => 'Code Review',
    'security' => 'Security',
    'quality' => 'Code Quality',
    'tech_debt' => 'Technical Debt',
    'documentation' => 'Documentation',
];
@endphp

<div class="flex items-center justify-between gap-4 px-5 py-4 transition-colors duration-150 hover:bg-slate-50 dark:hover:bg-slate-800/60">
    <div class="min-w-0">
        <p class="truncate text-sm font-semibold text-slate-900 dark:text-white">
            {{ $typeLabels[$analysis->type] ?? ucfirst($analysis->type) }}
            <span class="font-normal text-slate-400 dark:text-slate-600">&middot;</span>
            <span class="font-normal text-slate-500 dark:text-slate-400">{{ $analysis->repository->name ?? '—' }}</span>
        </p>
        <p class="mt-0.5 text-xs text-slate-500 dark:text-slate-400">
            {{ $analysis->completed_at?->diffForHumans() ?? 'in progress' }}
            @if ($analysis->model_used)
                &middot; {{ $analysis->model_used }}
            @endif
        </p>
    </div>

    <div class="flex shrink-0 items-center gap-3">
        @if ($analysis->critical_findings_count > 0)
            <x-dashboard.severity-badge severity="high">
                {{ $analysis->critical_findings_count }} issue{{ $analysis->critical_findings_count === 1 ? '' : 's' }}
            </x-dashboard.severity-badge>
        @endif
        <x-dashboard.status-badge :status="$analysis->status" />
    </div>
</div>
