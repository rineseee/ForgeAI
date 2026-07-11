@props(['commit'])

<div class="flex items-center gap-3 py-2.5 first:pt-0 last:pb-0">
    <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-slate-500 to-slate-600 text-[11px] font-semibold text-white">
        {{ strtoupper(substr($commit->author, 0, 1)) }}
    </span>

    <div class="min-w-0 flex-1">
        <p class="truncate text-sm text-slate-800 dark:text-slate-100">{{ $commit->message }}</p>
        <p class="mt-0.5 text-xs text-slate-500 dark:text-slate-400">
            {{ $commit->author }} &middot; {{ $commit->committed_at?->diffForHumans() }}
        </p>
    </div>

    <span class="shrink-0 rounded-md bg-slate-100 px-1.5 py-0.5 font-mono text-[11px] text-slate-500 dark:bg-slate-800 dark:text-slate-400">
        {{ substr($commit->sha, 0, 7) }}
    </span>
</div>
