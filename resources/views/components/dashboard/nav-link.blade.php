@props(['href', 'active' => false, 'collapsible' => false])

<a
    href="{{ $href }}"
    @if ($collapsible) :class="sidebarCollapsed ? 'justify-center px-2' : ''" @endif
    {{ $attributes->merge([
        'class' => 'group relative flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors duration-150 ' .
            ($active
                ? 'bg-slate-50 text-slate-700 dark:bg-slate-500/10 dark:text-slate-400'
                : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-slate-100'),
    ]) }}
>
    @if ($active)
        <span class="absolute left-0 top-1/2 h-5 w-1 -translate-y-1/2 rounded-r-full bg-slate-600 dark:bg-slate-400"></span>
    @endif

    <span @class(['shrink-0', 'text-slate-600 dark:text-slate-400' => $active, 'text-slate-400 group-hover:text-slate-500 dark:text-slate-500 dark:group-hover:text-slate-300' => ! $active])>
        {{ $icon ?? '' }}
    </span>

    @if ($collapsible)
        <span x-show="!sidebarCollapsed" x-cloak x-transition.opacity.duration.150ms class="truncate">{{ $slot }}</span>
    @else
        <span class="truncate">{{ $slot }}</span>
    @endif
</a>
