@props(['repository'])

<a
    href="{{ route('repositories.show', $repository) }}"
    class="flex items-center justify-between gap-4 px-5 py-4 transition-colors duration-150 hover:bg-slate-50 dark:hover:bg-slate-800/60"
>
    <div class="flex min-w-0 items-center gap-3">
        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-slate-900 text-white dark:bg-slate-700">
            <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 3.75c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125" />
            </svg>
        </span>

        <div class="min-w-0">
            <div class="flex items-center gap-2">
                <p
                    @if ($repository->description) title="{{ $repository->description }}" @endif
                    class="truncate text-sm font-semibold text-slate-900 dark:text-white"
                >{{ $repository->full_name }}</p>
                @if ($repository->is_private)
                    <span class="rounded-full bg-slate-100 px-2 py-0.5 text-[11px] font-medium text-slate-500 dark:bg-slate-700 dark:text-slate-300">Private</span>
                @endif
            </div>
            <p class="mt-0.5 text-xs text-slate-500 dark:text-slate-400">
                {{ $repository->language ?? 'Unknown' }} &middot; {{ $repository->default_branch }} &middot;
                {{ $repository->last_synced_at?->diffForHumans() ?? 'never synced' }}
            </p>
        </div>
    </div>

    <div class="flex shrink-0 items-center gap-4 text-xs text-slate-500 dark:text-slate-400">
        <span class="hidden sm:inline">{{ $repository->pull_requests_count }} PRs</span>
        <span class="hidden sm:inline">{{ $repository->analyses_count }} scans</span>
        @if (($repository->findings_count ?? 0) > 0)
            <x-dashboard.severity-badge severity="high">
                {{ $repository->findings_count }} open
            </x-dashboard.severity-badge>
        @else
            <span class="inline-flex items-center gap-1 rounded-full bg-emerald-100 px-2 py-0.5 text-xs font-medium text-emerald-700 dark:bg-emerald-500/15 dark:text-emerald-400">Clean</span>
        @endif
    </div>
</a>
