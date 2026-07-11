@php
$categoryColors = [
    'code_quality' => 'bg-slate-500',
    'security' => 'bg-red-500',
    'performance' => 'bg-amber-500',
    'architecture' => 'bg-slate-600',
    'documentation' => 'bg-sky-500',
    'technical_debt' => 'bg-emerald-500',
];
$chartLabels = $reports->map(fn ($report) => $report->repository->name.' · '.$report->completed_at?->format('M j'))->values();
$chartValues = $reports->map(fn ($report) => round($report->categories->avg('score')))->values();
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-slate-800 dark:text-white">{{ __('Reports') }}</h2>
        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ __('Every completed AI analysis, with full category scoring.') }}</p>
    </x-slot>

    <div class="py-8">
        <div
            x-data="{
                search: '',
                sort: 'date_desc',
                apply() {
                    const list = this.$refs.list;
                    const cards = Array.from(list.children);

                    cards.forEach(card => {
                        const matches = !this.search || card.dataset.name.toLowerCase().includes(this.search.toLowerCase());
                        card.style.display = matches ? '' : 'none';
                    });

                    cards.sort((a, b) => {
                        if (this.sort === 'score_desc') return b.dataset.score - a.dataset.score;
                        if (this.sort === 'score_asc') return a.dataset.score - b.dataset.score;
                        return this.sort === 'date_asc' ? a.dataset.date - b.dataset.date : b.dataset.date - a.dataset.date;
                    });
                    cards.forEach(card => list.appendChild(card));
                }
            }"
            x-init="apply()"
            class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8"
        >
            <!-- Toolbar -->
            <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center print:hidden">
                <div class="relative flex-1">
                    <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 10.5a6.5 6.5 0 11-13 0 6.5 6.5 0 0113 0z" /></svg>
                    <input
                        type="search" x-model="search" x-on:input.debounce.150ms="apply()"
                        placeholder="Search reports by repository..."
                        class="w-full rounded-lg border-slate-300 bg-white py-2 pl-9 pr-3 text-sm shadow-sm placeholder:text-slate-400 focus:border-slate-500 focus:ring-slate-500 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100 dark:placeholder:text-slate-500"
                    >
                </div>

                <select x-model="sort" x-on:change="apply()" class="rounded-lg border-slate-300 bg-white text-sm shadow-sm focus:border-slate-500 focus:ring-slate-500 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100">
                    <option value="date_desc">Newest first</option>
                    <option value="date_asc">Oldest first</option>
                    <option value="score_desc">Highest score</option>
                    <option value="score_asc">Lowest score</option>
                </select>
            </div>

            @if ($reports->isNotEmpty())
                <!-- Score trend chart -->
                <x-dashboard.card title="Overall Score by Report" description="Average of all six category scores per analysis." class="mb-6 print:hidden">
                    <x-dashboard.chart-bar :labels="$chartLabels" :values="$chartValues" color="#4f46e5" :height="220" />
                </x-dashboard.card>
            @endif

            @if ($reports->isEmpty())
                <div class="rounded-xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <x-dashboard.empty-state title="No reports yet" description="Run an AI analysis on a repository to generate your first report." />
                </div>
            @else
                <div x-ref="list" class="space-y-6">
                    @foreach ($reports as $report)
                        @php $overallScore = round($report->categories->avg('score')); @endphp
                        <div data-name="{{ $report->repository->full_name }}" data-date="{{ $report->completed_at?->timestamp ?? 0 }}" data-score="{{ $overallScore }}" class="break-inside-avoid">
                            <x-dashboard.card class="print:shadow-none print:border-slate-300">
                                <div class="flex flex-col gap-6 lg:flex-row lg:items-center">
                                    <!-- Repository + date + overall score -->
                                    <div class="flex items-center gap-5 lg:w-72 lg:shrink-0">
                                        <x-dashboard.score-ring :score="$overallScore" size="84" label="Overall" />
                                        <div class="min-w-0">
                                            <p class="truncate text-sm font-semibold text-slate-900 dark:text-white">{{ $report->repository->full_name }}</p>
                                            <p class="mt-0.5 text-xs text-slate-500 dark:text-slate-400">
                                                {{ $report->completed_at?->format('M j, Y') }} &middot; {{ $report->completed_at?->diffForHumans() }}
                                            </p>
                                            @if ($report->triggeredBy)
                                                <p class="mt-0.5 text-xs text-slate-500 dark:text-slate-400">by {{ $report->triggeredBy->name }}</p>
                                            @endif
                                            @if ($report->model_used)
                                                <span class="mt-1 inline-flex items-center rounded-full bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-500 dark:bg-slate-800 dark:text-slate-400" title="{{ __('Different AI models can score the same repository differently.') }}">
                                                    {{ $report->model_used }}
                                                </span>
                                            @endif
                                            <div class="mt-2 flex items-center gap-2 print:hidden">
                                                <a href="{{ route('analyses.show', $report) }}" class="inline-flex items-center gap-1 text-xs font-semibold text-slate-600 hover:text-slate-500 dark:text-slate-400">
                                                    {{ __('View full report') }}
                                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                                                </a>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Category scores -->
                                    <div class="grid flex-1 grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-6">
                                        @foreach (\App\Models\AnalysisCategory::CATEGORIES as $key)
                                            @php $category = $report->categories->firstWhere('category', $key); @endphp
                                            <div>
                                                <div class="flex items-center justify-between text-xs">
                                                    <span class="font-medium text-slate-600 dark:text-slate-300">{{ \App\Models\AnalysisCategory::LABELS[$key] }}</span>
                                                    <span class="font-semibold text-slate-900 dark:text-white">{{ $category?->score ?? '—' }}</span>
                                                </div>
                                                <x-dashboard.progress-bar :value="$category?->score ?? 0" :color="$categoryColors[$key]" class="mt-1.5" />
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                @php $highlight = $report->categories->sortBy('score')->first(); @endphp
                                @if ($highlight)
                                    <div class="mt-5 border-t border-slate-100 pt-4 dark:border-slate-800">
                                        <p class="mb-1 text-xs font-semibold uppercase tracking-wide text-slate-400">
                                            {{ __('Lowest scoring area') }}: {{ \App\Models\AnalysisCategory::LABELS[$highlight->category] }}
                                        </p>
                                        <x-markdown :content="$highlight->explanation" class="text-sm" />
                                    </div>
                                @endif
                            </x-dashboard.card>
                        </div>
                    @endforeach
                </div>

                <div class="mt-4 print:hidden">
                    {{ $reports->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
