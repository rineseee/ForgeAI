<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-slate-800 dark:text-white">
            {{ __('Dashboard') }}
        </h2>
        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
            {{ __('Welcome back, :name.', ['name' => Auth::user()->name]) }}
        </p>
    </x-slot>

    <div
        x-data="{ loading: true }"
        x-init="setTimeout(() => loading = false, 500)"
        class="py-8"
    >
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">

            @if (! $team)
                <x-dashboard.empty-state
                    title="You're not part of a team yet"
                    description="Create or join a team to start connecting repositories and running AI analyses."
                />
            @else
                <!-- Welcome banner + quick actions -->
                <div class="overflow-hidden rounded-2xl bg-gradient-to-r from-slate-600 to-slate-700 px-6 py-6 text-white shadow-sm sm:px-8">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h3 class="text-lg font-semibold">{{ __('Welcome back, :name', ['name' => Auth::user()->name]) }}</h3>
                            <p class="mt-1 text-sm text-slate-100">
                                {{ __("You're viewing :team.", ['team' => $team->name]) }}
                            </p>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            <a href="{{ route('repositories.index') }}" class="inline-flex items-center gap-1.5 rounded-lg bg-white/15 px-3.5 py-2 text-xs font-semibold backdrop-blur transition hover:bg-white/25">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                                Connect Repository
                            </a>
                            <a href="{{ route('analyses.index') }}" class="inline-flex items-center gap-1.5 rounded-lg bg-white/15 px-3.5 py-2 text-xs font-semibold backdrop-blur transition hover:bg-white/25">
                                Run Analysis
                            </a>
                            <a href="{{ route('reports.index') }}" class="inline-flex items-center gap-1.5 rounded-lg bg-white/15 px-3.5 py-2 text-xs font-semibold backdrop-blur transition hover:bg-white/25">
                                Generate Report
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Stat cards -->
                <div x-show="loading" class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    @for ($i = 0; $i < 4; $i++) <x-dashboard.skeleton-card /> @endfor
                </div>

                @php
                    $scoreAccent = fn ($score) => $score >= 80 ? 'emerald' : ($score >= 50 ? 'amber' : 'red');
                @endphp

                <div x-show="!loading" x-cloak x-transition.opacity class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    <x-dashboard.stat-card label="Total Repositories" :value="$stats['repositories']" accent="indigo">
                        <x-slot name="icon">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375" />
                            </svg>
                        </x-slot>
                    </x-dashboard.stat-card>

                    <x-dashboard.stat-card label="Total Analyses" :value="$stats['analyses']" accent="indigo">
                        <x-slot name="icon">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </x-slot>
                    </x-dashboard.stat-card>

                    <x-dashboard.stat-card label="Reports Generated" :value="$stats['reports']" accent="amber">
                        <x-slot name="icon">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                            </svg>
                        </x-slot>
                    </x-dashboard.stat-card>

                    <x-dashboard.stat-card label="Average Health Score" :value="$stats['healthScore']" :accent="$scoreAccent($stats['healthScore'])">
                        <x-slot name="icon">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                            </svg>
                        </x-slot>
                    </x-dashboard.stat-card>
                </div>

                <div x-show="!loading" x-cloak x-transition.opacity class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                    <x-dashboard.stat-card label="Security Score" :value="$stats['securityScore']" :accent="$scoreAccent($stats['securityScore'])">
                        <x-slot name="icon">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                            </svg>
                        </x-slot>
                    </x-dashboard.stat-card>

                    <x-dashboard.stat-card label="Code Quality Score" :value="$stats['codeQualityScore']" :accent="$scoreAccent($stats['codeQualityScore'])">
                        <x-slot name="icon">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 6.75L22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25m7.5-3l-4.5 16.5" />
                            </svg>
                        </x-slot>
                    </x-dashboard.stat-card>

                    <x-dashboard.stat-card label="Technical Debt" :value="$stats['technicalDebtScore']" :accent="$scoreAccent($stats['technicalDebtScore'])">
                        <x-slot name="icon">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
                            </svg>
                        </x-slot>
                    </x-dashboard.stat-card>
                </div>

                <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                    <!-- Left column -->
                    <div class="space-y-6 lg:col-span-2">
                        <!-- Repository Overview -->
                        <div class="rounded-xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
                            <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4 dark:border-slate-800">
                                <h3 class="text-sm font-semibold text-slate-900 dark:text-white">{{ __('Repository Overview') }}</h3>
                                <a href="{{ route('repositories.index') }}" class="text-xs font-medium text-slate-600 hover:text-slate-500 dark:text-slate-400">{{ __('View all') }}</a>
                            </div>
                            <div class="divide-y divide-slate-100 dark:divide-slate-800">
                                @forelse ($repositories as $repository)
                                    <x-dashboard.repository-row :repository="$repository" />
                                @empty
                                    <x-dashboard.empty-state
                                        title="No repositories connected"
                                        description="Connect a GitHub repository to start seeing AI-powered insights."
                                    >
                                        <x-slot name="action">
                                            <a href="{{ route('repositories.index') }}" class="inline-flex items-center gap-1.5 rounded-lg bg-slate-600 px-3.5 py-2 text-xs font-semibold text-white transition hover:bg-slate-500">
                                                Connect Repository
                                            </a>
                                        </x-slot>
                                    </x-dashboard.empty-state>
                                @endforelse
                            </div>
                        </div>

                        <!-- Recent Analyses -->
                        <div class="rounded-xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
                            <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4 dark:border-slate-800">
                                <h3 class="text-sm font-semibold text-slate-900 dark:text-white">{{ __('Recent Analyses') }}</h3>
                                <a href="{{ route('analyses.index') }}" class="text-xs font-medium text-slate-600 hover:text-slate-500 dark:text-slate-400">{{ __('View all') }}</a>
                            </div>
                            <div class="divide-y divide-slate-100 dark:divide-slate-800">
                                @forelse ($recentAnalyses as $analysis)
                                    <x-dashboard.analysis-row :analysis="$analysis" />
                                @empty
                                    <x-dashboard.empty-state title="No analyses have been run" description="Run your first AI analysis to see results here." />
                                @endforelse
                            </div>
                        </div>

                        <!-- AI Analysis Summary -->
                        <div class="rounded-xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
                            <div class="border-b border-slate-200 px-5 py-4 dark:border-slate-800">
                                <h3 class="text-sm font-semibold text-slate-900 dark:text-white">{{ __('AI Analysis Summary') }}</h3>
                            </div>
                            <div class="p-5">
                                @if ($analysisTypeSummary->isEmpty())
                                    <x-dashboard.empty-state title="No analyses yet" description="Analysis type breakdown will appear here once scans have run." />
                                @else
                                    @php
                                        $typeLabels = ['code_review' => 'Code Review', 'security' => 'Security', 'quality' => 'Full Analysis', 'tech_debt' => 'Technical Debt', 'documentation' => 'Documentation'];
                                        $typeColors = ['#4f46e5', '#059669', '#d97706', '#e11d48', '#0284c7'];
                                    @endphp
                                    <div class="grid grid-cols-1 items-center gap-6 sm:grid-cols-2">
                                        <x-dashboard.chart-donut
                                            :labels="$analysisTypeSummary->keys()->map(fn ($t) => $typeLabels[$t] ?? ucfirst($t))->values()->all()"
                                            :values="$analysisTypeSummary->values()->all()"
                                            :colors="array_slice($typeColors, 0, $analysisTypeSummary->count())"
                                            :height="200"
                                        />
                                        <ul class="space-y-2.5">
                                            @foreach ($analysisTypeSummary as $type => $count)
                                                <li class="flex items-center justify-between text-sm">
                                                    <span class="flex items-center gap-2 text-slate-600 dark:text-slate-300">
                                                        <span class="h-2.5 w-2.5 rounded-full" style="background-color: {{ $typeColors[$loop->index % count($typeColors)] }}"></span>
                                                        {{ $typeLabels[$type] ?? ucfirst($type) }}
                                                    </span>
                                                    <span class="font-semibold text-slate-900 dark:text-white">{{ $count }}</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Analysis History -->
                        <div class="rounded-xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
                            <div class="border-b border-slate-200 px-5 py-4 dark:border-slate-800">
                                <h3 class="text-sm font-semibold text-slate-900 dark:text-white">{{ __('Analysis History') }}</h3>
                                <p class="mt-0.5 text-xs text-slate-500 dark:text-slate-400">{{ __('Completed analyses over the last 8 weeks.') }}</p>
                            </div>
                            <div class="p-5">
                                @if ($analysisHistory->isEmpty())
                                    <x-dashboard.empty-state title="No analysis history yet" description="Completed analyses will build up a trend here." />
                                @else
                                    @php
                                        $historyLabels = $analysisHistory->keys()->map(function ($period) {
                                            [$year, $week] = explode('-', $period);
                                            return 'Wk '.(int) $week;
                                        })->values()->all();
                                    @endphp
                                    <x-dashboard.chart-bar :labels="$historyLabels" :values="$analysisHistory->values()->all()" color="#059669" :height="200" />
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Right column -->
                    <div class="space-y-6">
                        <!-- Recent Activity -->
                        <div class="rounded-xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
                            <div class="border-b border-slate-200 px-5 py-4 dark:border-slate-800">
                                <h3 class="text-sm font-semibold text-slate-900 dark:text-white">{{ __('Recent Activity') }}</h3>
                            </div>
                            <div class="max-h-96 divide-y divide-slate-100 overflow-y-auto dark:divide-slate-800">
                                @forelse ($recentActivity as $activity)
                                    <x-dashboard.activity-item :activity="$activity" />
                                @empty
                                    <x-dashboard.empty-state title="No activity yet" description="Team activity will show up here." />
                                @endforelse
                            </div>
                        </div>

                        <!-- Repository Distribution -->
                        <div class="rounded-xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
                            <div class="border-b border-slate-200 px-5 py-4 dark:border-slate-800">
                                <h3 class="text-sm font-semibold text-slate-900 dark:text-white">{{ __('Repository Distribution') }}</h3>
                            </div>
                            <div class="p-5">
                                @if ($repositoryDistribution->isEmpty())
                                    <x-dashboard.empty-state title="No languages detected" description="Sync repositories to see language distribution." />
                                @else
                                    @php $langColors = ['#4f46e5', '#059669', '#d97706', '#e11d48', '#0284c7', '#7c3aed', '#0d9488']; @endphp
                                    <div class="grid grid-cols-1 items-center gap-6 sm:grid-cols-2">
                                        <x-dashboard.chart-donut
                                            :labels="$repositoryDistribution->keys()->values()->all()"
                                            :values="$repositoryDistribution->values()->all()"
                                            :colors="array_slice($langColors, 0, $repositoryDistribution->count())"
                                            :height="200"
                                        />
                                        <ul class="space-y-2.5">
                                            @foreach ($repositoryDistribution as $language => $count)
                                                <li class="flex items-center justify-between text-sm">
                                                    <span class="flex items-center gap-2 text-slate-600 dark:text-slate-300">
                                                        <span class="h-2.5 w-2.5 rounded-full" style="background-color: {{ $langColors[$loop->index % count($langColors)] }}"></span>
                                                        {{ $language }}
                                                    </span>
                                                    <span class="font-semibold text-slate-900 dark:text-white">{{ $count }}</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Average Health Score -->
                        <div class="rounded-xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
                            <div class="border-b border-slate-200 px-5 py-4 dark:border-slate-800">
                                <h3 class="text-sm font-semibold text-slate-900 dark:text-white">{{ __('Average Health Score') }}</h3>
                            </div>
                            <div class="flex items-center justify-center p-6">
                                <x-dashboard.score-ring :score="$stats['healthScore']" label="Health Score" />
                            </div>
                        </div>

                        <!-- AI Reports Preview -->
                        <div class="rounded-xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
                            <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4 dark:border-slate-800">
                                <h3 class="text-sm font-semibold text-slate-900 dark:text-white">{{ __('AI Reports') }}</h3>
                                <a href="{{ route('reports.index') }}" class="text-xs font-medium text-slate-600 hover:text-slate-500 dark:text-slate-400">{{ __('View all') }}</a>
                            </div>
                            <div class="divide-y divide-slate-100 dark:divide-slate-800">
                                @forelse ($recentReports as $report)
                                    <x-dashboard.report-card :report="$report" />
                                @empty
                                    <x-dashboard.empty-state title="No reports generated" description="Generate your first report to share with your team." />
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
