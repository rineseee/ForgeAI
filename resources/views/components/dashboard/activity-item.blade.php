@props(['activity'])

@php
$icons = [
    'repository.imported' => ['bg-indigo-100 text-indigo-600 dark:bg-indigo-500/15 dark:text-indigo-400', 'M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375'],
    'analysis.completed' => ['bg-emerald-100 text-emerald-600 dark:bg-emerald-500/15 dark:text-emerald-400', 'M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
    'report.generated' => ['bg-amber-100 text-amber-600 dark:bg-amber-500/15 dark:text-amber-400', 'M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m5.231 13.481L15 17.25m-1.519-3.75L12 15.75l-1.481-1.5M8.25 21h7.5a2.25 2.25 0 002.25-2.25V7.5a4.5 4.5 0 00-4.5-4.5H6.75a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 006.75 21z'],
];
[$iconClass, $iconPath] = $icons[$activity->action] ?? ['bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-300', 'M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z'];

$labels = [
    'repository.imported' => 'imported repository',
    'analysis.completed' => 'completed an analysis on',
    'report.generated' => 'generated a report for',
];
$label = $labels[$activity->action] ?? $activity->action;
@endphp

<div class="flex items-start gap-3 px-5 py-3.5">
    <span class="mt-0.5 flex h-7 w-7 shrink-0 items-center justify-center rounded-full {{ $iconClass }}">
        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $iconPath }}" />
        </svg>
    </span>

    <div class="min-w-0 flex-1">
        <p class="text-sm text-slate-700 dark:text-slate-300">
            <span class="font-medium text-slate-900 dark:text-white">{{ $activity->user->name ?? 'Someone' }}</span>
            {{ $label }}
            <span class="font-medium text-slate-900 dark:text-white">{{ $activity->properties['name'] ?? $activity->properties['repository'] ?? $activity->properties['title'] ?? '' }}</span>
        </p>
        <p class="mt-0.5 text-xs text-slate-400 dark:text-slate-500">{{ $activity->created_at->diffForHumans() }}</p>
    </div>
</div>
