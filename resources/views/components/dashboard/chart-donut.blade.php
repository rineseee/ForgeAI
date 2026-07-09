@props(['labels' => [], 'values' => [], 'colors' => [], 'height' => 220])

@php
$chartId = 'donut-'.Str::random(8);
@endphp

<div
    x-data="{
        init() {
            new Chart(this.$refs.canvas, {
                type: 'doughnut',
                data: {
                    labels: @js($labels),
                    datasets: [{
                        data: @js($values),
                        backgroundColor: @js($colors),
                        borderWidth: 0,
                        hoverOffset: 6,
                    }],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '70%',
                    plugins: {
                        legend: { display: false },
                        tooltip: { padding: 10, cornerRadius: 8 },
                    },
                },
            });
        }
    }"
>
    <canvas x-ref="canvas" id="{{ $chartId }}" style="height: {{ $height }}px"></canvas>
</div>
