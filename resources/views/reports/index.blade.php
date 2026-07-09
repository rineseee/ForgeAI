<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-slate-800 dark:text-white">{{ __('Reports') }}</h2>
    </x-slot>

    <div class="py-8">
        <div
            x-data="{
                search: '',
                format: '',
                sort: 'date_desc',
                apply() {
                    const list = this.$refs.list;
                    const rows = Array.from(list.children);

                    rows.forEach(row => {
                        const matchesSearch = !this.search || row.dataset.name.toLowerCase().includes(this.search.toLowerCase());
                        const matchesFormat = !this.format || row.dataset.format === this.format;
                        row.style.display = (matchesSearch && matchesFormat) ? '' : 'none';
                    });

                    rows.sort((a, b) => this.sort === 'date_asc' ? a.dataset.date - b.dataset.date : b.dataset.date - a.dataset.date);
                    rows.forEach(row => list.appendChild(row));
                }
            }"
            x-init="apply()"
            class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8"
        >
            <!-- Toolbar -->
            <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center">
                <div class="relative flex-1">
                    <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 10.5a6.5 6.5 0 11-13 0 6.5 6.5 0 0113 0z" /></svg>
                    <input
                        type="search" x-model="search" x-on:input.debounce.150ms="apply()"
                        placeholder="Search reports..."
                        class="w-full rounded-lg border-slate-300 bg-white py-2 pl-9 pr-3 text-sm shadow-sm placeholder:text-slate-400 focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100 dark:placeholder:text-slate-500"
                    >
                </div>

                <select x-model="format" x-on:change="apply()" class="rounded-lg border-slate-300 bg-white text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100">
                    <option value="">All formats</option>
                    <option value="pdf">PDF</option>
                    <option value="csv">CSV</option>
                </select>

                <select x-model="sort" x-on:change="apply()" class="rounded-lg border-slate-300 bg-white text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100">
                    <option value="date_desc">Newest first</option>
                    <option value="date_asc">Oldest first</option>
                </select>
            </div>

            <div class="rounded-xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
                @if ($reports->isEmpty())
                    <x-dashboard.empty-state title="No reports generated" description="Generate a report from any repository's analyses." />
                @else
                    <div x-ref="list" class="divide-y divide-slate-100 dark:divide-slate-800">
                        @foreach ($reports as $report)
                            @php
                                $latestAnalysis = $report->repository?->analyses()->with('findings', 'metrics')->latest('completed_at')->first();
                                $debtMetric = $latestAnalysis?->metrics->firstWhere('metric_key', 'debt_score');
                                $qualityScore = $debtMetric ? round(100 - ($debtMetric->metric_value['value'] ?? 0)) : null;
                                $findingsBySeverity = $latestAnalysis?->findings->groupBy('severity');
                                $exampleFinding = $latestAnalysis?->findings->first();
                                $readme = $report->repository?->documentationArtifacts()->where('type', 'readme')->first();
                            @endphp
                            <div
                                data-name="{{ $report->title }}"
                                data-format="{{ $report->format }}"
                                data-date="{{ $report->generated_at?->timestamp ?? 0 }}"
                                x-data="{ open: false }"
                            >
                                <button type="button" x-on:click="open = !open" class="flex w-full items-center justify-between gap-3 px-5 py-4 text-left transition-colors duration-150 hover:bg-slate-50 dark:hover:bg-slate-800/60">
                                    <div class="flex min-w-0 items-center gap-3">
                                        <span @class([
                                            'flex h-9 w-9 shrink-0 items-center justify-center rounded-lg text-xs font-bold uppercase',
                                            'bg-red-50 text-red-600 dark:bg-red-500/10 dark:text-red-400' => $report->format === 'pdf',
                                            'bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400' => $report->format === 'csv',
                                        ])>{{ $report->format }}</span>
                                        <div class="min-w-0">
                                            <p class="truncate text-sm font-semibold text-slate-900 dark:text-white">{{ $report->title }}</p>
                                            <p class="mt-0.5 text-xs text-slate-500 dark:text-slate-400">
                                                {{ $report->repository->name ?? 'All repositories' }} &middot; {{ $report->generated_at?->diffForHumans() }}
                                            </p>
                                        </div>
                                    </div>
                                    <svg class="h-4 w-4 shrink-0 text-slate-400 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>

                                <div x-show="open" x-cloak x-transition class="border-t border-slate-100 bg-slate-50/60 px-5 py-6 dark:border-slate-800 dark:bg-slate-950/40">
                                    @if (! $latestAnalysis)
                                        <p class="text-sm text-slate-500 dark:text-slate-400">No analysis data available for this report yet.</p>
                                    @else
                                        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                                            <div class="flex flex-col items-center justify-center rounded-xl bg-white p-5 shadow-sm dark:bg-slate-900">
                                                <x-dashboard.score-ring :score="$qualityScore ?? 0" label="Quality Score" :size="112" />
                                            </div>

                                            <div class="rounded-xl bg-white p-5 shadow-sm lg:col-span-2 dark:bg-slate-900">
                                                <p class="mb-3 text-xs font-semibold uppercase tracking-wide text-slate-400">Findings by Severity</p>
                                                <div class="space-y-3">
                                                    @foreach (['critical', 'high', 'medium', 'low'] as $severity)
                                                        <x-dashboard.progress-bar
                                                            :label="ucfirst($severity)"
                                                            :value="$findingsBySeverity[$severity]->count() ?? 0"
                                                            :max="max(1, $latestAnalysis->findings->count())"
                                                            :color="['critical' => 'bg-red-500', 'high' => 'bg-orange-500', 'medium' => 'bg-amber-500', 'low' => 'bg-sky-500'][$severity]"
                                                        />
                                                    @endforeach
                                                </div>
                                            </div>

                                            @if ($exampleFinding)
                                                <div class="rounded-xl bg-white p-5 shadow-sm lg:col-span-3 dark:bg-slate-900">
                                                    <div class="mb-3 flex items-center gap-2">
                                                        <x-dashboard.severity-badge :severity="$exampleFinding->severity" />
                                                        <p class="text-sm font-semibold text-slate-900 dark:text-white">{{ $exampleFinding->title }}</p>
                                                    </div>
                                                    <p class="mb-3 text-sm text-slate-600 dark:text-slate-400">{{ $exampleFinding->description }}</p>
                                                    @if ($exampleFinding->suggestion)
                                                        <pre class="overflow-x-auto rounded-lg bg-slate-950 p-4 text-xs text-slate-200"><code class="language-diff">// Suggested fix — {{ $exampleFinding->file_path }}
{{ $exampleFinding->suggestion }}</code></pre>
                                                    @endif
                                                </div>
                                            @endif

                                            @if ($readme)
                                                <div class="rounded-xl bg-white p-5 shadow-sm lg:col-span-3 dark:bg-slate-900">
                                                    <p class="mb-3 text-xs font-semibold uppercase tracking-wide text-slate-400">Repository README Preview</p>
                                                    <x-markdown :content="$readme->content" />
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="mt-4">
                {{ $reports->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
