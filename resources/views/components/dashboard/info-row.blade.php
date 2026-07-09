@props(['label'])

<div class="flex items-center justify-between gap-4 py-2.5 first:pt-0 last:pb-0">
    <dt class="text-sm text-slate-500 dark:text-slate-400">{{ $label }}</dt>
    <dd class="min-w-0 truncate text-sm font-medium text-slate-900 dark:text-white">{{ $slot }}</dd>
</div>
