@php
$typeLabels = [
    'code_review' => 'Code Review',
    'security' => 'Security',
    'quality' => 'Full Analysis',
    'tech_debt' => 'Technical Debt',
    'documentation' => 'Documentation',
];
$hasCategories = $lastAnalysis && $lastAnalysis->categories->isNotEmpty();
$debtScore = optional($lastAnalysis?->metrics->firstWhere('metric_key', 'debt_score'))->metric_value['value'] ?? null;
$complexityScore = optional($lastAnalysis?->metrics->firstWhere('metric_key', 'complexity_score'))->metric_value['value'] ?? null;
$duplicationPct = optional($lastAnalysis?->metrics->firstWhere('metric_key', 'duplication_percent'))->metric_value['value'] ?? null;
$qualityScore = match (true) {
    $hasCategories => round($lastAnalysis->categories->avg('score')),
    $debtScore !== null => round(100 - $debtScore, 1),
    default => null,
};
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="min-w-0">
                <a href="{{ route('repositories.index') }}" class="inline-flex items-center gap-1 text-xs font-medium text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" /></svg>
                    {{ __('Repositories') }}
                </a>

                <div class="mt-1 flex flex-wrap items-center gap-2">
                    <h2 class="truncate text-xl font-semibold leading-tight text-slate-800 dark:text-white">{{ $repository->full_name }}</h2>
                    <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[11px] font-medium capitalize {{ $repository->is_private ? 'bg-slate-100 text-slate-500 dark:bg-slate-700 dark:text-slate-300' : 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/15 dark:text-emerald-400' }}">
                        {{ $repository->visibility }}
                    </span>
                </div>

                @if ($repository->description)
                    <p class="mt-1 max-w-2xl truncate text-sm text-slate-500 dark:text-slate-400">{{ $repository->description }}</p>
                @endif
            </div>

            <div class="flex shrink-0 items-center gap-3">
                @if ($repository->html_url)
                    <a
                        href="{{ $repository->html_url }}" target="_blank" rel="noopener noreferrer"
                        class="inline-flex items-center justify-center gap-2 rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition-all duration-150 hover:bg-slate-50 hover:shadow focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700 dark:focus:ring-offset-slate-900"
                    >
                        {{ __('View on GitHub') }}
                    </a>
                @endif

                <form method="post" action="{{ route('repositories.analyze', $repository) }}">
                    @csrf
                    <x-primary-button type="submit">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.456-2.456L14.25 6l1.035-.259a3.375 3.375 0 002.456-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 00-2.456 2.456z" /></svg>
                        {{ __('Analyze Repository') }}
                    </x-primary-button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="rounded-lg bg-emerald-50 px-4 py-3 text-sm text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400">
                    {{ session('status') }}
                </div>
            @endif

            <!-- Repository Statistics -->
            <div class="grid grid-cols-2 gap-4 sm:grid-cols-4">
                <x-dashboard.stat-card label="Analyses" :value="$repository->analyses_count" accent="indigo">
                    <x-slot name="icon">
                        <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 01-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0115 18.257V17.25m6-12V15a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 15V5.25m18 0A2.25 2.25 0 0018.75 3H5.25A2.25 2.25 0 003 5.25m18 0V12a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 12V5.25" /></svg>
                    </x-slot>
                </x-dashboard.stat-card>

                <x-dashboard.stat-card label="Pull Requests" :value="$repository->pull_requests_count" accent="amber">
                    <x-slot name="icon">
                        <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21v-9m0 0a2.25 2.25 0 100-4.5 2.25 2.25 0 000 4.5zm9 9a2.25 2.25 0 100-4.5 2.25 2.25 0 000 4.5zm0 0V9a4.5 4.5 0 00-4.5-4.5H9.75" /></svg>
                    </x-slot>
                </x-dashboard.stat-card>

                <x-dashboard.stat-card label="Commits" :value="$repository->commits_count" accent="emerald">
                    <x-slot name="icon">
                        <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm-.375 5.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" /></svg>
                    </x-slot>
                </x-dashboard.stat-card>

                <x-dashboard.stat-card label="Branches" :value="$branches->count()" accent="red">
                    <x-slot name="icon">
                        <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 3v12m0 0a3 3 0 103 3m-3-3a3 3 0 11-3 3m3-15a3 3 0 100-6 3 3 0 000 6zm12 0a3 3 0 100-6 3 3 0 000 6zm0 0v3a3 3 0 01-3 3h-3" /></svg>
                    </x-slot>
                </x-dashboard.stat-card>
            </div>

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                <!-- Left column -->
                <div class="space-y-6 lg:col-span-2">
                    <!-- Last Analysis -->
                    <x-dashboard.card title="Last Analysis" description="Most recent AI analysis results for this repository.">
                        @if ($lastAnalysis)
                            <div class="flex flex-col gap-6 sm:flex-row sm:items-center">
                                @if ($qualityScore !== null)
                                    <x-dashboard.score-ring :score="$qualityScore" size="88" label="Overall" />
                                @endif

                                <div class="min-w-0 flex-1 space-y-3">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <span class="text-sm font-semibold text-slate-900 dark:text-white">{{ $typeLabels[$lastAnalysis->type] ?? ucfirst($lastAnalysis->type) }}</span>
                                        <x-dashboard.status-badge :status="$lastAnalysis->status" />
                                        @if ($lastAnalysis->critical_findings_count > 0)
                                            <x-dashboard.severity-badge severity="high">{{ $lastAnalysis->critical_findings_count }} critical/high</x-dashboard.severity-badge>
                                        @endif
                                    </div>

                                    <p class="text-xs text-slate-500 dark:text-slate-400">
                                        {{ $lastAnalysis->completed_at?->diffForHumans() ?? 'In progress' }}
                                        @if ($lastAnalysis->model_used)
                                            &middot; {{ $lastAnalysis->model_used }}
                                        @endif
                                    </p>

                                    @if ($hasCategories)
                                        <div class="flex flex-wrap gap-2">
                                            @foreach ($lastAnalysis->categories as $category)
                                                <span class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-2 py-0.5 text-[11px] font-medium text-slate-600 dark:bg-slate-800 dark:text-slate-300">
                                                    {{ \App\Models\AnalysisCategory::LABELS[$category->category] ?? $category->category }}: {{ $category->score }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @else
                                        @if ($complexityScore !== null)
                                            <x-dashboard.progress-bar :value="$complexityScore" label="Complexity" color="bg-amber-500" />
                                        @endif
                                        @if ($duplicationPct !== null)
                                            <x-dashboard.progress-bar :value="$duplicationPct" label="Duplication" color="bg-red-500" />
                                        @endif
                                    @endif

                                    @if ($lastAnalysis->status !== 'running')
                                        <a href="{{ route('analyses.show', $lastAnalysis) }}" class="inline-flex items-center gap-1 text-xs font-semibold text-slate-600 hover:text-slate-500 dark:text-slate-400">
                                            {{ __('View full report') }}
                                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @else
                            <x-dashboard.empty-state title="No analyses yet" description="Run your first AI analysis to see quality, security and technical debt scores here." />
                        @endif
                    </x-dashboard.card>

                    <!-- Recent Commits -->
                    <x-dashboard.card title="Recent Commits">
                        @if ($recentCommits->isEmpty())
                            <x-dashboard.empty-state title="No commits synced" description="Commit history will appear here after your next sync." />
                        @else
                            <div class="divide-y divide-slate-100 dark:divide-slate-800">
                                @foreach ($recentCommits as $commit)
                                    <x-dashboard.commit-row :commit="$commit" />
                                @endforeach
                            </div>
                        @endif
                    </x-dashboard.card>
                </div>

                <!-- Right column -->
                <div class="space-y-6">
                    <!-- Repository Information -->
                    <x-dashboard.card title="Repository Information">
                        <dl class="divide-y divide-slate-100 dark:divide-slate-800">
                            <x-dashboard.info-row label="Owner">{{ $repository->owner ?? '—' }}</x-dashboard.info-row>
                            <x-dashboard.info-row label="Visibility"><span class="capitalize">{{ $repository->visibility }}</span></x-dashboard.info-row>
                            <x-dashboard.info-row label="Default branch">{{ $repository->default_branch }}</x-dashboard.info-row>
                            <x-dashboard.info-row label="Main language">{{ $repository->language ?? 'Unknown' }}</x-dashboard.info-row>
                            <x-dashboard.info-row label="Last updated">{{ $repository->github_updated_at?->diffForHumans() ?? '—' }}</x-dashboard.info-row>
                            <x-dashboard.info-row label="Last synced">{{ $repository->last_synced_at?->diffForHumans() ?? 'Never' }}</x-dashboard.info-row>
                        </dl>
                    </x-dashboard.card>

                    <!-- AI Status -->
                    <x-dashboard.card title="AI Status">
                        @php
                            $aiStatus = $lastAnalysis->status ?? 'idle';
                            $aiCopy = [
                                'idle' => 'Ready to run your first analysis.',
                                'queued' => 'Waiting in the queue to start.',
                                'running' => 'The AI is currently analyzing this repository.',
                                'completed' => 'The latest analysis finished successfully.',
                                'failed' => 'The latest analysis failed. You can try again.',
                            ][$aiStatus] ?? 'Ready to run your first analysis.';
                        @endphp
                        <div class="flex items-center gap-3">
                            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-slate-900 text-white dark:bg-slate-700">
                                <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.456-2.456L14.25 6l1.035-.259a3.375 3.375 0 002.456-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 00-2.456 2.456z" /></svg>
                            </span>
                            <div class="min-w-0">
                                <x-dashboard.status-badge :status="$aiStatus" />
                                <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">{{ $aiCopy }}</p>
                            </div>
                        </div>

                        @if ($lastAnalysis?->triggeredBy)
                            <p class="mt-3 text-xs text-slate-500 dark:text-slate-400">
                                Triggered by {{ $lastAnalysis->triggeredBy->name }}
                            </p>
                        @endif
                    </x-dashboard.card>

                    <!-- Languages -->
                    <x-dashboard.card title="Languages">
                        @if ($repository->language)
                            <div class="space-y-2">
                                <div class="flex items-center justify-between text-sm">
                                    <span class="font-medium text-slate-700 dark:text-slate-200">{{ $repository->language }}</span>
                                    <span class="text-slate-500 dark:text-slate-400">100%</span>
                                </div>
                                <div class="flex h-2 w-full overflow-hidden rounded-full bg-slate-100 dark:bg-slate-800">
                                    <div class="h-2 rounded-full bg-slate-600"></div>
                                </div>
                            </div>
                        @else
                            <x-dashboard.empty-state title="No language detected" />
                        @endif
                    </x-dashboard.card>

                    <!-- Branches -->
                    <x-dashboard.card title="Branches">
                        @if ($branches->isEmpty())
                            <x-dashboard.empty-state title="No branches synced" />
                        @else
                            <div class="divide-y divide-slate-100 dark:divide-slate-800">
                                @foreach ($branches as $branch)
                                    <x-dashboard.branch-row :branch="$branch" />
                                @endforeach
                            </div>
                        @endif
                    </x-dashboard.card>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
