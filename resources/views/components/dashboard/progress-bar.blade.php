@props(['value', 'max' => 100, 'color' => 'bg-slate-600', 'label' => null])

@php
$pct = $max > 0 ? min(100, round(($value / $max) * 100)) : 0;
@endphp

<div>
    @if ($label)
        <div class="mb-1 flex items-center justify-between text-xs text-slate-500 dark:text-slate-400">
            <span>{{ $label }}</span>
            <span>{{ $pct }}%</span>
        </div>
    @endif
    <div class="h-1.5 w-full overflow-hidden rounded-full bg-slate-100 dark:bg-slate-800">
        <div class="h-1.5 rounded-full {{ $color }} transition-all duration-500 ease-out" style="width: {{ $pct }}%"></div>
    </div>
</div>
