@props(['labels' => [], 'values' => [], 'color' => '#4f46e5', 'height' => 220])

<div
    x-data="{
        init() {
            new Chart(this.$refs.canvas, {
                type: 'bar',
                data: {
                    labels: @js($labels),
                    datasets: [{
                        data: @js($values),
                        backgroundColor: @js($color),
                        borderRadius: 6,
                        maxBarThickness: 36,
                    }],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: { padding: 10, cornerRadius: 8 },
                    },
                    scales: {
                        x: { grid: { display: false } },
                        y: { beginAtZero: true, grid: { color: $store.theme.dark ? 'rgba(255,255,255,0.06)' : 'rgba(0,0,0,0.06)' } },
                    },
                },
            });
        }
    }"
>
    <canvas x-ref="canvas" style="height: {{ $height }}px"></canvas>
</div>
