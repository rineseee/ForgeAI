@props(['branch'])

<div class="flex items-center justify-between gap-3 py-2.5 first:pt-0 last:pb-0">
    <div class="flex min-w-0 items-center gap-2.5">
        <svg class="h-4 w-4 shrink-0 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 3v12m0 0a3 3 0 103 3m-3-3a3 3 0 11-3 3m3-15a3 3 0 100-6 3 3 0 000 6zm12 0a3 3 0 100-6 3 3 0 000 6zm0 0v3a3 3 0 01-3 3h-3" />
        </svg>
        <span class="truncate font-mono text-sm text-slate-700 dark:text-slate-200">{{ $branch->name }}</span>
    </div>

    @if ($branch->is_default)
        <span class="inline-flex shrink-0 items-center rounded-full bg-indigo-100 px-2 py-0.5 text-[11px] font-medium text-indigo-700 dark:bg-indigo-500/15 dark:text-indigo-400">Default</span>
    @endif
</div>
