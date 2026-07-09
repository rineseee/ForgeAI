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
                <div class="overflow-hidden rounded-2xl bg-gradient-to-r from-indigo-600 to-violet-600 px-6 py-6 text-white shadow-sm sm:px-8">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h3 class="text-lg font-semibold">{{ __('Welcome back, :name', ['name' => Auth::user()->name]) }}</h3>
                            <p class="mt-1 text-sm text-indigo-100">
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

                <div x-show="!loading" x-cloak x-transition.opacity class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    <x-dashboard.stat-card label="Repositories" :value="$stats['repositories']" accent="indigo">
                        <x-slot name="icon">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375" />
                            </svg>
                        </x-slot>
                    </x-dashboard.stat-card>

                    <x-dashboard.stat-card label="Analyses Run" :value="$stats['analyses']" accent="emerald">
                        <x-slot name="icon">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </x-slot>
                    </x-dashboard.stat-card>

                    <x-dashboard.stat-card label="Critical Findings" :value="$stats['criticalFindings']" accent="red">
                        <x-slot name="icon">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
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
                </div>

                <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                    <!-- Left column -->
                    <div class="space-y-6 lg:col-span-2">
                        <!-- Repository Overview -->
                        <div class="rounded-xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
                            <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4 dark:border-slate-800">
                                <h3 class="text-sm font-semibold text-slate-900 dark:text-white">{{ __('Repository Overview') }}</h3>
                                <a href="{{ route('repositories.index') }}" class="text-xs font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400">{{ __('View all') }}</a>
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
                                            <a href="{{ route('repositories.index') }}" class="inline-flex items-center gap-1.5 rounded-lg bg-indigo-600 px-3.5 py-2 text-xs font-semibold text-white transition hover:bg-indigo-500">
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
                                <a href="{{ route('analyses.index') }}" class="text-xs font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400">{{ __('View all') }}</a>
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
                                        $typeLabels = ['code_review' => 'Code Review', 'security' => 'Security', 'quality' => 'Code Quality', 'tech_debt' => 'Technical Debt', 'documentation' => 'Documentation'];
                                        $typeColors = ['#6366f1', '#10b981', '#f59e0b', '#f43f5e', '#0ea5e9'];
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

                        <!-- Security Overview -->
                        <div class="rounded-xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
                            <div class="border-b border-slate-200 px-5 py-4 dark:border-slate-800">
                                <h3 class="text-sm font-semibold text-slate-900 dark:text-white">{{ __('Security Overview') }}</h3>
                            </div>
                            <div class="p-5">
                                @if ($severityBreakdown->isEmpty())
                                    <x-dashboard.empty-state title="No open findings" description="Great news — nothing to report." />
                                @else
                                    @php $severityColors = ['critical' => 'bg-red-500', 'high' => 'bg-orange-500', 'medium' => 'bg-amber-500', 'low' => 'bg-sky-500', 'info' => 'bg-slate-400']; @endphp
                                    <div class="space-y-4">
                                        @foreach (['critical', 'high', 'medium', 'low'] as $severity)
                                            <x-dashboard.progress-bar
                                                :label="ucfirst($severity)"
                                                :value="$severityBreakdown[$severity] ?? 0"
                                                :max="max(1, $severityBreakdown->sum())"
                                                :color="$severityColors[$severity]"
                                            />
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Code Quality Overview -->
                        <div class="rounded-xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
                            <div class="border-b border-slate-200 px-5 py-4 dark:border-slate-800">
                                <h3 class="text-sm font-semibold text-slate-900 dark:text-white">{{ __('Code Quality Overview') }}</h3>
                            </div>
                            <div class="flex items-center justify-center p-6">
                                <x-dashboard.score-ring :score="$qualityScore" label="Quality Score" />
                            </div>
                        </div>

                        <!-- AI Reports Preview -->
                        <div class="rounded-xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
                            <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4 dark:border-slate-800">
                                <h3 class="text-sm font-semibold text-slate-900 dark:text-white">{{ __('AI Reports') }}</h3>
                                <a href="{{ route('reports.index') }}" class="text-xs font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400">{{ __('View all') }}</a>
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
