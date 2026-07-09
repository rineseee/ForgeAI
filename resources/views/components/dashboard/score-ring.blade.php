@props(['score', 'size' => 96, 'label' => null])

@php
$radius = 40;
$circumference = 2 * M_PI * $radius;
$clamped = max(0, min(100, $score));
$offset = $circumference * (1 - $clamped / 100);
$color = match (true) {
    $clamped >= 80 => 'text-emerald-500',
    $clamped >= 50 => 'text-amber-500',
    default => 'text-red-500',
};
@endphp

<div class="flex flex-col items-center" style="width: {{ $size }}px">
    <svg viewBox="0 0 100 100" width="{{ $size }}" height="{{ $size }}" class="-rotate-90">
        <circle cx="50" cy="50" r="{{ $radius }}" fill="none" stroke-width="8" class="stroke-slate-100 dark:stroke-slate-800" />
        <circle
            cx="50" cy="50" r="{{ $radius }}" fill="none" stroke-width="8" stroke-linecap="round"
            class="{{ $color }} transition-all duration-700 ease-out"
            stroke="currentColor"
            stroke-dasharray="{{ $circumference }}"
            stroke-dashoffset="{{ $offset }}"
        />
    </svg>
    <div class="-mt-[3.75rem] flex flex-col items-center">
        <span class="text-2xl font-bold text-slate-900 dark:text-white">{{ round($clamped) }}</span>
        @if ($label)
            <span class="mt-8 text-xs text-slate-500 dark:text-slate-400">{{ $label }}</span>
        @endif
    </div>
</div>
