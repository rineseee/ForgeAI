@props(['title' => null, 'description' => null])

<div {{ $attributes->merge(['class' => 'rounded-xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900']) }}>
    @if ($title || isset($action))
        <div class="flex items-center justify-between gap-3 border-b border-slate-100 px-5 py-4 dark:border-slate-800">
            <div class="min-w-0">
                @if ($title)
                    <h3 class="text-sm font-semibold text-slate-900 dark:text-white">{{ $title }}</h3>
                @endif
                @if ($description)
                    <p class="mt-0.5 text-xs text-slate-500 dark:text-slate-400">{{ $description }}</p>
                @endif
            </div>
            @isset($action)
                <div class="shrink-0">{{ $action }}</div>
            @endisset
        </div>
    @endif

    <div class="p-5">
        {{ $slot }}
    </div>
</div>
