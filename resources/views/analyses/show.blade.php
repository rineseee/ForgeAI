@php
$overallScore = $analysis->categories->isNotEmpty() ? round($analysis->categories->avg('score')) : null;
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="min-w-0">
                <a href="{{ route('repositories.show', $analysis->repository) }}" class="inline-flex items-center gap-1 text-xs font-medium text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" /></svg>
                    {{ $analysis->repository->full_name }}
                </a>

                <div class="mt-1 flex flex-wrap items-center gap-2">
                    <h2 class="text-xl font-semibold leading-tight text-slate-800 dark:text-white">{{ __('Analysis Report') }}</h2>
                    <x-dashboard.status-badge :status="$analysis->status" />
                </div>

                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                    {{ $analysis->completed_at?->diffForHumans() ?? $analysis->started_at?->diffForHumans() }}
                    @if ($analysis->model_used)
                        &middot; {{ $analysis->model_used }}
                    @endif
                    @if ($analysis->triggeredBy)
                        &middot; triggered by {{ $analysis->triggeredBy->name }}
                    @endif
                </p>
            </div>

            <div class="flex shrink-0 items-center gap-3 print:hidden">
                <button type="button" onclick="window.print()" class="inline-flex items-center justify-center gap-2 rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition-all duration-150 hover:bg-slate-50 hover:shadow focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700 dark:focus:ring-offset-slate-900">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 011.913-.247m10.5 0a48.536 48.536 0 00-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5zm-3 0h.008v.008H15V10.5z" /></svg>
                    {{ __('Export / Print') }}
                </button>

                <form method="post" action="{{ route('repositories.analyze', $analysis->repository) }}">
                    @csrf
                    <x-secondary-button type="submit">{{ __('Run New Analysis') }}</x-secondary-button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
            @if ($analysis->status === 'failed')
                <x-dashboard.card>
                    <div class="flex items-center gap-4">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-red-100 text-red-600 dark:bg-red-500/15 dark:text-red-400">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" /></svg>
                        </span>
                        <div>
                            <p class="text-sm font-semibold text-slate-900 dark:text-white">{{ __('This analysis failed') }}</p>
                            <p class="mt-0.5 text-sm text-slate-500 dark:text-slate-400">{{ $analysis->failure_reason }}</p>
                        </div>
                    </div>
                </x-dashboard.card>
            @elseif ($analysis->categories->isEmpty())
                <x-dashboard.card>
                    <x-dashboard.empty-state title="No results yet" description="This analysis hasn't produced results." />
                </x-dashboard.card>
            @else
                <x-dashboard.card title="Overall Score" class="print:shadow-none print:border-slate-300">
                    <div class="flex items-center gap-6">
                        <x-dashboard.score-ring :score="$overallScore" size="96" label="Overall" />
                        <div class="grid flex-1 grid-cols-2 gap-3 sm:grid-cols-3">
                            @foreach ($analysis->categories as $category)
                                <div class="rounded-lg border border-slate-100 px-3 py-2 dark:border-slate-800">
                                    <p class="text-xs text-slate-500 dark:text-slate-400">{{ \App\Models\AnalysisCategory::LABELS[$category->category] ?? $category->category }}</p>
                                    <p class="mt-0.5 text-lg font-bold text-slate-900 dark:text-white">{{ $category->score }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </x-dashboard.card>

                <div class="grid grid-cols-1 gap-6 lg:grid-cols-2 print:grid-cols-2">
                    @foreach ($analysis->categories as $category)
                        <x-dashboard.card
                            :title="\App\Models\AnalysisCategory::LABELS[$category->category] ?? $category->category"
                            class="print:break-inside-avoid print:shadow-none print:border-slate-300"
                        >
                            <x-slot name="action">
                                <x-dashboard.progress-bar
                                    :value="$category->score"
                                    :color="$category->score >= 80 ? 'bg-emerald-500' : ($category->score >= 50 ? 'bg-amber-500' : 'bg-red-500')"
                                />
                            </x-slot>

                            <div class="space-y-5">
                                <p class="text-sm text-slate-600 dark:text-slate-300">{{ $category->explanation }}</p>

                                @if (! empty($category->problems))
                                    <div>
                                        <h4 class="flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">
                                            <svg class="h-3.5 w-3.5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m0 3.75h.007v.008H12v-.008zM21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                            {{ __('Problems') }}
                                        </h4>
                                        <ul class="mt-2 space-y-1.5">
                                            @foreach ($category->problems as $problem)
                                                <li class="text-sm text-slate-600 dark:text-slate-300">&bull; {{ $problem }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                @if (! empty($category->recommendations))
                                    <div>
                                        <h4 class="flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">
                                            <svg class="h-3.5 w-3.5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                                            {{ __('Recommendations') }}
                                        </h4>
                                        <ul class="mt-2 space-y-1.5">
                                            @foreach ($category->recommendations as $recommendation)
                                                <li class="text-sm text-slate-600 dark:text-slate-300">&bull; {{ $recommendation }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                @if (! empty($category->improvement_examples))
                                    <div>
                                        <h4 class="flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">
                                            <svg class="h-3.5 w-3.5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.25 6.75L22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25m7.5-3l-4.5 16.5" /></svg>
                                            {{ __('Improvement Examples') }}
                                        </h4>
                                        <div class="mt-2 space-y-2">
                                            @foreach ($category->improvement_examples as $example)
                                                <x-markdown :content="$example" class="rounded-lg bg-slate-50 p-3 text-xs dark:bg-slate-800/60" />
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </x-dashboard.card>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
