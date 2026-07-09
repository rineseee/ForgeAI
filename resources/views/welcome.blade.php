<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Forge AI analyzes your GitHub repositories with AI — code review, security scanning, code quality, technical debt, and documentation, all in one place.">

    <title>Forge AI — AI-Powered Software Engineering Insights</title>

    @include('partials.theme-init')

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white font-sans text-slate-900 antialiased dark:bg-slate-950 dark:text-slate-100" x-data="{ mobileMenuOpen: false }">

    {{-- ============ HEADER ============ --}}
    <header class="sticky top-0 z-50 border-b border-slate-200/70 bg-white/80 backdrop-blur-md dark:border-slate-800/70 dark:bg-slate-950/80">
        <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
            <a href="/" class="flex items-center gap-2">
                <x-application-logo class="h-7 w-7 fill-current text-indigo-600 dark:text-indigo-400" />
                <span class="text-lg font-bold tracking-tight">Forge AI</span>
            </a>

            <nav class="hidden items-center gap-8 text-sm font-medium text-slate-600 md:flex dark:text-slate-300">
                <a href="#features" class="transition hover:text-slate-900 dark:hover:text-white">Features</a>
                <a href="#how-it-works" class="transition hover:text-slate-900 dark:hover:text-white">How it Works</a>
                <a href="#pricing" class="transition hover:text-slate-900 dark:hover:text-white">Pricing</a>
                <a href="#faq" class="transition hover:text-slate-900 dark:hover:text-white">FAQ</a>
            </nav>

            <div class="flex items-center gap-2">
                <button
                    type="button"
                    x-on:click="$store.theme.toggle()"
                    class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-slate-500 transition hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-800"
                    aria-label="Toggle theme"
                >
                    <svg x-show="!$store.theme.dark" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-6.364-.386 1.591-1.591M3 12h2.25m.386-6.364 1.591 1.591M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" /></svg>
                    <svg x-show="$store.theme.dark" x-cloak class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z" /></svg>
                </button>

                @auth
                    <a href="{{ url('/dashboard') }}" class="hidden rounded-lg bg-gradient-to-r from-indigo-600 to-violet-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:from-indigo-500 hover:to-violet-500 sm:inline-flex">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="hidden rounded-lg px-3 py-2 text-sm font-medium text-slate-600 transition hover:text-slate-900 sm:inline-flex dark:text-slate-300 dark:hover:text-white">
                        Log in
                    </a>
                    <a href="{{ route('register') }}" class="hidden rounded-lg bg-gradient-to-r from-indigo-600 to-violet-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:from-indigo-500 hover:to-violet-500 sm:inline-flex">
                        Get Started
                    </a>
                @endauth

                <button type="button" x-on:click="mobileMenuOpen = !mobileMenuOpen" class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-slate-500 hover:bg-slate-100 md:hidden dark:text-slate-400 dark:hover:bg-slate-800">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                        <path x-show="mobileMenuOpen" x-cloak stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <div x-show="mobileMenuOpen" x-cloak x-transition class="border-t border-slate-200 px-4 py-4 md:hidden dark:border-slate-800">
            <div class="flex flex-col gap-3 text-sm font-medium text-slate-600 dark:text-slate-300">
                <a href="#features" class="py-1">Features</a>
                <a href="#how-it-works" class="py-1">How it Works</a>
                <a href="#pricing" class="py-1">Pricing</a>
                <a href="#faq" class="py-1">FAQ</a>
                <div class="mt-2 flex gap-3 border-t border-slate-200 pt-4 dark:border-slate-800">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="flex-1 rounded-lg bg-gradient-to-r from-indigo-600 to-violet-600 px-4 py-2 text-center text-sm font-semibold text-white">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="flex-1 rounded-lg border border-slate-300 px-4 py-2 text-center text-sm font-semibold dark:border-slate-700">Log in</a>
                        <a href="{{ route('register') }}" class="flex-1 rounded-lg bg-gradient-to-r from-indigo-600 to-violet-600 px-4 py-2 text-center text-sm font-semibold text-white">Get Started</a>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    {{-- ============ HERO ============ --}}
    <section class="relative overflow-hidden">
        <div class="absolute inset-0 -z-10">
            <div class="absolute -top-40 left-1/2 h-[36rem] w-[64rem] -translate-x-1/2 rounded-full bg-gradient-to-br from-indigo-500/20 via-violet-500/10 to-transparent blur-3xl"></div>
        </div>

        <div class="mx-auto max-w-7xl px-4 pb-20 pt-20 text-center sm:px-6 lg:px-8 lg:pt-28">
            <a href="#features" class="mx-auto mb-6 inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-4 py-1.5 text-xs font-medium text-slate-600 shadow-sm transition hover:border-indigo-200 hover:text-indigo-600 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-300 dark:hover:border-indigo-500/40 dark:hover:text-indigo-400">
                <span class="flex h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                Now analyzing repositories with GPT-4.1
            </a>

            <h1 class="mx-auto max-w-4xl text-4xl font-extrabold tracking-tight sm:text-5xl lg:text-6xl">
                Ship better code with
                <span class="bg-gradient-to-r from-indigo-600 to-violet-600 bg-clip-text text-transparent">AI-powered</span>
                engineering insight.
            </h1>

            <p class="mx-auto mt-6 max-w-2xl text-lg text-slate-600 dark:text-slate-400">
                Forge AI connects to your GitHub repositories and automatically reviews code, hunts for security vulnerabilities,
                tracks technical debt, and writes your documentation — so your team ships with confidence, not guesswork.
            </p>

            <div class="mt-10 flex flex-col items-center justify-center gap-4 sm:flex-row">
                <a href="{{ route('register') }}" class="inline-flex items-center justify-center gap-2 rounded-lg bg-gradient-to-r from-indigo-600 to-violet-600 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-indigo-600/20 transition hover:from-indigo-500 hover:to-violet-500 hover:shadow-xl hover:shadow-indigo-600/30">
                    Get Started Free
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" /></svg>
                </a>
                <a href="#dashboard-preview" class="inline-flex items-center justify-center gap-2 rounded-lg border border-slate-300 bg-white px-6 py-3 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800">
                    Learn More
                </a>
            </div>

            <p class="mt-4 text-xs text-slate-400 dark:text-slate-500">No credit card required &middot; Free plan available &middot; Setup in under 5 minutes</p>
        </div>
    </section>

    {{-- ============ TRUSTED BY ============ --}}
    <section class="border-y border-slate-200 bg-slate-50/60 py-10 dark:border-slate-800 dark:bg-slate-900/40">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <p class="text-center text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                Trusted by engineering teams at
            </p>
            <div class="mt-6 grid grid-cols-2 items-center justify-items-center gap-8 sm:grid-cols-3 lg:grid-cols-6">
                @foreach (['Northwind', 'Initech', 'Globex', 'Hooli', 'Umbrella', 'Stark Labs'] as $company)
                    <span class="select-none text-lg font-bold tracking-tight text-slate-400 grayscale transition hover:text-slate-500 dark:text-slate-600 dark:hover:text-slate-400">{{ $company }}</span>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ============ FEATURES ============ --}}
    <section id="features" class="py-24">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-2xl text-center">
                <h2 class="text-3xl font-bold tracking-tight sm:text-4xl">Everything your team needs to ship quality code</h2>
                <p class="mt-4 text-slate-600 dark:text-slate-400">One platform, connected to your repositories, covering the full engineering quality lifecycle.</p>
            </div>

            <div class="mt-16 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                @php
                $features = [
                    ['title' => 'AI Code Review', 'desc' => 'Automated, context-aware review on every pull request — before your teammates even look at it.', 'icon' => 'M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'accent' => 'indigo'],
                    ['title' => 'Repository Analysis', 'desc' => 'Deep static analysis across your entire codebase, not just the files that changed.', 'icon' => 'M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375', 'accent' => 'violet'],
                    ['title' => 'Security Insights', 'desc' => 'Catch vulnerabilities, secrets, and unsafe patterns with severity-ranked findings.', 'icon' => 'M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z', 'accent' => 'red'],
                    ['title' => 'Code Quality Reports', 'desc' => 'Track complexity, duplication, and maintainability trends across every repository.', 'icon' => 'M9 17.25v1.5M12 17.25v1.5M15 17.25v1.5M3.75 3h16.5M4.5 3v14.25a2.25 2.25 0 002.25 2.25h10.5a2.25 2.25 0 002.25-2.25V3', 'accent' => 'emerald'],
                    ['title' => 'Technical Debt Detection', 'desc' => 'A prioritized, scored backlog of debt so your team knows exactly what to fix first.', 'icon' => 'M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25', 'accent' => 'amber'],
                    ['title' => 'AI Documentation', 'desc' => 'Auto-generated READMEs, API docs, and changelogs that stay in sync with your code.', 'icon' => 'M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25', 'accent' => 'indigo'],
                    ['title' => 'GitHub Integration', 'desc' => 'Connect in minutes with OAuth. Webhooks keep every branch and PR automatically in sync.', 'icon' => 'M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 3.75c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125', 'accent' => 'violet'],
                    ['title' => 'Performance Suggestions', 'desc' => 'AI-identified bottlenecks and optimization opportunities, with concrete fix suggestions.', 'icon' => 'M13 10V3L4 14h7v7l9-11h-7z', 'accent' => 'emerald'],
                ];
                $accentMap = [
                    'indigo' => 'bg-indigo-50 text-indigo-600 dark:bg-indigo-500/10 dark:text-indigo-400',
                    'violet' => 'bg-violet-50 text-violet-600 dark:bg-violet-500/10 dark:text-violet-400',
                    'red' => 'bg-red-50 text-red-600 dark:bg-red-500/10 dark:text-red-400',
                    'emerald' => 'bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400',
                    'amber' => 'bg-amber-50 text-amber-600 dark:bg-amber-500/10 dark:text-amber-400',
                ];
                @endphp

                @foreach ($features as $feature)
                    <div class="group rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition-all duration-200 hover:-translate-y-1 hover:shadow-lg dark:border-slate-800 dark:bg-slate-900">
                        <span class="flex h-11 w-11 items-center justify-center rounded-xl transition-transform duration-200 group-hover:scale-105 {{ $accentMap[$feature['accent']] }}">
                            <svg class="h-5.5 w-5.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $feature['icon'] }}" />
                            </svg>
                        </span>
                        <h3 class="mt-4 text-base font-semibold">{{ $feature['title'] }}</h3>
                        <p class="mt-2 text-sm leading-relaxed text-slate-600 dark:text-slate-400">{{ $feature['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ============ HOW IT WORKS ============ --}}
    <section id="how-it-works" class="border-y border-slate-200 bg-slate-50/60 py-24 dark:border-slate-800 dark:bg-slate-900/40">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-2xl text-center">
                <h2 class="text-3xl font-bold tracking-tight sm:text-4xl">From zero to insight in four steps</h2>
                <p class="mt-4 text-slate-600 dark:text-slate-400">No agents to install, no CI to rewire. Just connect and go.</p>
            </div>

            <div class="mt-16 grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ([
                    ['step' => '01', 'title' => 'Connect GitHub', 'desc' => 'Authorize Forge AI with a single OAuth click — read-only by default.'],
                    ['step' => '02', 'title' => 'Select Repository', 'desc' => 'Choose which repositories or organizations you want analyzed.'],
                    ['step' => '03', 'title' => 'Run AI Analysis', 'desc' => 'Our models scan code, history, and PRs for issues that matter.'],
                    ['step' => '04', 'title' => 'Receive Actionable Reports', 'desc' => 'Get prioritized findings and shareable reports, automatically.'],
                ] as $i => $item)
                    <div class="relative">
                        <div class="flex items-center gap-4">
                            <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-indigo-600 to-violet-600 text-sm font-bold text-white shadow-lg shadow-indigo-600/20">
                                {{ $item['step'] }}
                            </span>
                            @if (!$loop->last)
                                <div class="hidden h-px flex-1 bg-gradient-to-r from-indigo-300 to-transparent sm:block lg:hidden"></div>
                            @endif
                        </div>
                        <h3 class="mt-4 text-base font-semibold">{{ $item['title'] }}</h3>
                        <p class="mt-2 text-sm leading-relaxed text-slate-600 dark:text-slate-400">{{ $item['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ============ DASHBOARD PREVIEW ============ --}}
    <section id="dashboard-preview" class="py-24">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-2xl text-center">
                <h2 class="text-3xl font-bold tracking-tight sm:text-4xl">A dashboard built for signal, not noise</h2>
                <p class="mt-4 text-slate-600 dark:text-slate-400">Every repository, analysis, and report — one clean view.</p>
            </div>

            <div class="relative mt-16">
                <div class="absolute inset-0 -z-10 bg-gradient-to-t from-indigo-500/10 to-transparent blur-2xl"></div>

                <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-2xl shadow-slate-300/40 dark:border-slate-800 dark:bg-slate-900 dark:shadow-black/40">
                    <!-- fake window chrome -->
                    <div class="flex items-center gap-1.5 border-b border-slate-200 bg-slate-50 px-4 py-3 dark:border-slate-800 dark:bg-slate-900">
                        <span class="h-2.5 w-2.5 rounded-full bg-red-400"></span>
                        <span class="h-2.5 w-2.5 rounded-full bg-amber-400"></span>
                        <span class="h-2.5 w-2.5 rounded-full bg-emerald-400"></span>
                        <span class="mx-auto rounded-md bg-white px-3 py-0.5 text-xs text-slate-400 shadow-sm dark:bg-slate-800">app.forgeai.dev/dashboard</span>
                    </div>

                    <div class="grid grid-cols-1 gap-px bg-slate-100 p-px sm:grid-cols-4 dark:bg-slate-800">
                        @foreach ([
                            ['label' => 'Repositories', 'value' => '12', 'accent' => 'text-indigo-600 dark:text-indigo-400'],
                            ['label' => 'Analyses Run', 'value' => '248', 'accent' => 'text-emerald-600 dark:text-emerald-400'],
                            ['label' => 'Critical Findings', 'value' => '3', 'accent' => 'text-red-600 dark:text-red-400'],
                            ['label' => 'Reports Generated', 'value' => '34', 'accent' => 'text-amber-600 dark:text-amber-400'],
                        ] as $stat)
                            <div class="bg-white p-5 dark:bg-slate-900">
                                <p class="text-xs font-medium text-slate-500 dark:text-slate-400">{{ $stat['label'] }}</p>
                                <p class="mt-2 text-2xl font-bold {{ $stat['accent'] }}">{{ $stat['value'] }}</p>
                            </div>
                        @endforeach
                    </div>

                    <div class="grid grid-cols-1 gap-px bg-slate-100 p-px lg:grid-cols-3 dark:bg-slate-800">
                        <div class="bg-white p-6 lg:col-span-2 dark:bg-slate-900">
                            <p class="mb-4 text-sm font-semibold">Repository Overview</p>
                            <div class="space-y-3">
                                @foreach ([
                                    ['name' => 'acme/payments-api', 'lang' => 'PHP', 'status' => 'Clean', 'ok' => true],
                                    ['name' => 'acme/web-frontend', 'lang' => 'TypeScript', 'status' => '2 Open', 'ok' => false],
                                    ['name' => 'acme/infra-tools', 'lang' => 'Go', 'status' => 'Clean', 'ok' => true],
                                ] as $repo)
                                    <div class="flex items-center justify-between rounded-lg border border-slate-100 px-4 py-3 dark:border-slate-800">
                                        <div class="flex items-center gap-3">
                                            <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-900 text-white dark:bg-slate-700">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375" /></svg>
                                            </span>
                                            <div>
                                                <p class="text-sm font-medium">{{ $repo['name'] }}</p>
                                                <p class="text-xs text-slate-400">{{ $repo['lang'] }}</p>
                                            </div>
                                        </div>
                                        <span @class([
                                            'rounded-full px-2 py-0.5 text-xs font-medium',
                                            'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/15 dark:text-emerald-400' => $repo['ok'],
                                            'bg-orange-100 text-orange-700 dark:bg-orange-500/15 dark:text-orange-400' => ! $repo['ok'],
                                        ])>{{ $repo['status'] }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="bg-white p-6 dark:bg-slate-900">
                            <p class="mb-4 text-sm font-semibold">Security Overview</p>
                            <div class="space-y-3">
                                @foreach ([
                                    ['label' => 'Critical', 'pct' => 8, 'color' => 'bg-red-500'],
                                    ['label' => 'High', 'pct' => 22, 'color' => 'bg-orange-500'],
                                    ['label' => 'Medium', 'pct' => 45, 'color' => 'bg-amber-500'],
                                    ['label' => 'Low', 'pct' => 25, 'color' => 'bg-sky-500'],
                                ] as $bar)
                                    <div>
                                        <div class="mb-1 flex justify-between text-xs text-slate-500 dark:text-slate-400">
                                            <span>{{ $bar['label'] }}</span>
                                            <span>{{ $bar['pct'] }}%</span>
                                        </div>
                                        <div class="h-1.5 w-full rounded-full bg-slate-100 dark:bg-slate-800">
                                            <div class="h-1.5 rounded-full {{ $bar['color'] }}" style="width: {{ $bar['pct'] }}%"></div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ============ WHY FORGE AI ============ --}}
    <section class="border-y border-slate-200 bg-slate-50/60 py-24 dark:border-slate-800 dark:bg-slate-900/40">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-2xl text-center">
                <h2 class="text-3xl font-bold tracking-tight sm:text-4xl">Built for every part of the org</h2>
                <p class="mt-4 text-slate-600 dark:text-slate-400">Whether you're shipping solo or coordinating dozens of teams.</p>
            </div>

            <div class="mt-16 grid grid-cols-1 gap-6 lg:grid-cols-3">
                @foreach ([
                    ['title' => 'For Developers', 'desc' => 'Get instant, actionable feedback on your code without waiting for a human reviewer.', 'points' => ['Inline AI suggestions on every PR', 'Learn best practices as you code', 'Less time in review, more time building']],
                    ['title' => 'For Teams', 'desc' => 'Keep quality consistent across every repository and every contributor.', 'points' => ['Shared visibility into tech debt', 'Consistent review standards at scale', 'Faster onboarding with AI-generated docs']],
                    ['title' => 'For Companies', 'desc' => 'Reduce risk and prove engineering health with data, not anecdotes.', 'points' => ['Security posture across all repos', 'Exportable reports for stakeholders', 'Lower incident rates over time']],
                ] as $col)
                    <div class="rounded-2xl border border-slate-200 bg-white p-8 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <h3 class="text-lg font-semibold">{{ $col['title'] }}</h3>
                        <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">{{ $col['desc'] }}</p>
                        <ul class="mt-6 space-y-3">
                            @foreach ($col['points'] as $point)
                                <li class="flex items-start gap-2.5 text-sm text-slate-700 dark:text-slate-300">
                                    <svg class="mt-0.5 h-4 w-4 shrink-0 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                    {{ $point }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ============ TESTIMONIALS ============ --}}
    <section class="py-24">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-2xl text-center">
                <h2 class="text-3xl font-bold tracking-tight sm:text-4xl">Loved by engineering teams</h2>
            </div>

            <div class="mt-16 grid grid-cols-1 gap-6 lg:grid-cols-3">
                @foreach ([
                    ['name' => 'Amara Okafor', 'role' => 'Staff Engineer, Northwind', 'quote' => 'Forge AI caught a SQL injection risk in a PR three reviewers had already approved. It paid for itself in the first week.'],
                    ['name' => 'Daniel Kessler', 'role' => 'VP Engineering, Initech', 'quote' => 'The technical debt scoring gave us a real backlog instead of a vague feeling that "the code is bad somewhere."'],
                    ['name' => 'Priya Raman', 'role' => 'Lead Developer, Globex', 'quote' => 'Our onboarding time dropped noticeably once new hires had AI-generated docs to read instead of asking around Slack.'],
                ] as $t)
                    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <div class="flex gap-1 text-amber-400">
                            @for ($i = 0; $i < 5; $i++)
                                <svg class="h-4 w-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.958a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.368 2.447a1 1 0 00-.363 1.118l1.287 3.957c.3.922-.755 1.688-1.54 1.118l-3.367-2.446a1 1 0 00-1.176 0l-3.367 2.446c-.784.57-1.838-.196-1.539-1.118l1.287-3.957a1 1 0 00-.363-1.118L2.05 9.385c-.783-.57-.38-1.81.588-1.81h4.163a1 1 0 00.95-.69l1.286-3.958z"/></svg>
                            @endfor
                        </div>
                        <p class="mt-4 text-sm leading-relaxed text-slate-700 dark:text-slate-300">&ldquo;{{ $t['quote'] }}&rdquo;</p>
                        <div class="mt-6 flex items-center gap-3">
                            <span class="flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-br from-indigo-500 to-violet-500 text-sm font-semibold text-white">
                                {{ collect(explode(' ', $t['name']))->map(fn ($p) => $p[0])->join('') }}
                            </span>
                            <div>
                                <p class="text-sm font-semibold">{{ $t['name'] }}</p>
                                <p class="text-xs text-slate-500 dark:text-slate-400">{{ $t['role'] }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ============ PRICING ============ --}}
    <section id="pricing" class="border-y border-slate-200 bg-slate-50/60 py-24 dark:border-slate-800 dark:bg-slate-900/40">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-2xl text-center">
                <h2 class="text-3xl font-bold tracking-tight sm:text-4xl">Simple, transparent pricing</h2>
                <p class="mt-4 text-slate-600 dark:text-slate-400">Start free. Upgrade when your team needs more.</p>
            </div>

            <div class="mt-16 grid grid-cols-1 gap-8 lg:grid-cols-3">
                @foreach ([
                    ['name' => 'Free', 'price' => '$0', 'period' => 'forever', 'desc' => 'For individual developers trying Forge AI.', 'features' => ['1 repository', '10 AI analyses / month', 'Basic security scanning', 'Community support'], 'cta' => 'Get Started', 'popular' => false],
                    ['name' => 'Pro', 'price' => '$29', 'period' => 'per user / month', 'desc' => 'For teams shipping production code daily.', 'features' => ['Unlimited repositories', 'Unlimited AI analyses', 'Security + technical debt scoring', 'AI documentation generation', 'Priority support'], 'cta' => 'Start Free Trial', 'popular' => true],
                    ['name' => 'Enterprise', 'price' => 'Custom', 'period' => 'contact us', 'desc' => 'For organizations with advanced needs.', 'features' => ['Everything in Pro', 'SSO & audit logs', 'Custom AI model routing', 'Dedicated success manager', 'SLA-backed uptime'], 'cta' => 'Contact Sales', 'popular' => false],
                ] as $plan)
                    <div @class([
                        'relative rounded-2xl border p-8 shadow-sm transition',
                        'border-2 border-indigo-600 bg-white shadow-xl shadow-indigo-600/10 dark:bg-slate-900' => $plan['popular'],
                        'border-slate-200 bg-white dark:border-slate-800 dark:bg-slate-900' => ! $plan['popular'],
                    ])>
                        @if ($plan['popular'])
                            <span class="absolute -top-3 left-1/2 -translate-x-1/2 rounded-full bg-gradient-to-r from-indigo-600 to-violet-600 px-3 py-1 text-xs font-semibold text-white shadow">Most Popular</span>
                        @endif

                        <h3 class="text-lg font-semibold">{{ $plan['name'] }}</h3>
                        <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">{{ $plan['desc'] }}</p>
                        <p class="mt-6">
                            <span class="text-4xl font-extrabold">{{ $plan['price'] }}</span>
                            <span class="text-sm text-slate-500 dark:text-slate-400"> {{ $plan['period'] }}</span>
                        </p>

                        <a href="{{ route('register') }}" @class([
                            'mt-6 block rounded-lg px-4 py-2.5 text-center text-sm font-semibold shadow-sm transition',
                            'bg-gradient-to-r from-indigo-600 to-violet-600 text-white hover:from-indigo-500 hover:to-violet-500' => $plan['popular'],
                            'border border-slate-300 text-slate-700 hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800' => ! $plan['popular'],
                        ])>{{ $plan['cta'] }}</a>

                        <ul class="mt-8 space-y-3">
                            @foreach ($plan['features'] as $feature)
                                <li class="flex items-start gap-2.5 text-sm text-slate-700 dark:text-slate-300">
                                    <svg class="mt-0.5 h-4 w-4 shrink-0 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                    {{ $feature }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ============ FAQ ============ --}}
    <section id="faq" class="py-24">
        <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="text-3xl font-bold tracking-tight sm:text-4xl">Frequently asked questions</h2>
            </div>

            <div class="mt-12 space-y-3">
                @foreach ([
                    ['q' => 'How does Forge AI access my code?', 'a' => 'You connect via GitHub OAuth and grant read-only repository access. Forge AI never pushes commits or modifies your code without explicit action from you.'],
                    ['q' => 'What AI models power the analysis?', 'a' => 'Forge AI uses OpenAI models tuned with engineering-specific prompts for code review, security, and quality analysis, with more providers on the roadmap.'],
                    ['q' => 'Can I use Forge AI with private repositories?', 'a' => 'Yes. Private repositories are fully supported on every plan, including Free.'],
                    ['q' => 'Does Forge AI replace human code review?', 'a' => 'No — it augments it. Forge AI catches issues early and handles repetitive review work so your engineers can focus on architecture and product decisions.'],
                    ['q' => 'Is there a self-hosted option?', 'a' => 'Enterprise customers can discuss self-hosted and VPC deployment options with our sales team.'],
                ] as $i => $faq)
                    <div
                        x-data="{ open: {{ $i === 0 ? 'true' : 'false' }} }"
                        class="rounded-xl border border-slate-200 bg-white dark:border-slate-800 dark:bg-slate-900"
                    >
                        <button
                            type="button"
                            x-on:click="open = !open"
                            class="flex w-full items-center justify-between gap-4 px-5 py-4 text-left text-sm font-semibold"
                        >
                            {{ $faq['q'] }}
                            <svg class="h-4 w-4 shrink-0 text-slate-400 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="open" x-cloak x-transition class="px-5 pb-4 text-sm leading-relaxed text-slate-600 dark:text-slate-400">
                            {{ $faq['a'] }}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ============ FINAL CTA ============ --}}
    <section class="px-4 pb-24 sm:px-6 lg:px-8">
        <div class="relative mx-auto max-w-5xl overflow-hidden rounded-3xl bg-gradient-to-br from-indigo-700 via-indigo-600 to-violet-700 px-8 py-16 text-center text-white shadow-2xl shadow-indigo-600/30">
            <div class="absolute -top-24 -right-24 h-72 w-72 rounded-full bg-white/10 blur-3xl"></div>
            <div class="absolute -bottom-24 -left-24 h-72 w-72 rounded-full bg-violet-400/20 blur-3xl"></div>

            <h2 class="relative text-3xl font-bold tracking-tight sm:text-4xl">Ready to ship better code?</h2>
            <p class="relative mx-auto mt-4 max-w-xl text-indigo-100">
                Join engineering teams already using Forge AI to catch issues before they reach production.
            </p>
            <div class="relative mt-8 flex flex-col items-center justify-center gap-4 sm:flex-row">
                <a href="{{ route('register') }}" class="inline-flex items-center justify-center gap-2 rounded-lg bg-white px-6 py-3 text-sm font-semibold text-indigo-700 shadow-lg transition hover:bg-indigo-50">
                    Get Started Free
                </a>
                <a href="{{ route('login') }}" class="inline-flex items-center justify-center gap-2 rounded-lg border border-white/30 px-6 py-3 text-sm font-semibold text-white transition hover:bg-white/10">
                    Log in
                </a>
            </div>
        </div>
    </section>

    {{-- ============ FOOTER ============ --}}
    <footer class="border-t border-slate-200 bg-slate-50/60 dark:border-slate-800 dark:bg-slate-900/40">
        <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 gap-8 sm:grid-cols-3 lg:grid-cols-5">
                <div class="col-span-2 lg:col-span-1">
                    <a href="/" class="flex items-center gap-2">
                        <x-application-logo class="h-7 w-7 fill-current text-indigo-600 dark:text-indigo-400" />
                        <span class="text-lg font-bold tracking-tight">Forge AI</span>
                    </a>
                    <p class="mt-3 text-sm text-slate-500 dark:text-slate-400">AI-powered software engineering insight for teams that ship.</p>
                    <div class="mt-4 flex gap-3">
                        @foreach (['X', 'GH', 'IN'] as $social)
                            <a href="#" class="flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 text-xs font-semibold text-slate-500 transition hover:border-indigo-300 hover:text-indigo-600 dark:border-slate-700 dark:text-slate-400 dark:hover:border-indigo-500/40 dark:hover:text-indigo-400">
                                {{ $social }}
                            </a>
                        @endforeach
                    </div>
                </div>

                <div>
                    <p class="text-sm font-semibold">Product</p>
                    <ul class="mt-4 space-y-3 text-sm text-slate-500 dark:text-slate-400">
                        <li><a href="#features" class="hover:text-slate-900 dark:hover:text-white">Features</a></li>
                        <li><a href="#pricing" class="hover:text-slate-900 dark:hover:text-white">Pricing</a></li>
                        <li><a href="#dashboard-preview" class="hover:text-slate-900 dark:hover:text-white">Dashboard</a></li>
                        <li><a href="#faq" class="hover:text-slate-900 dark:hover:text-white">FAQ</a></li>
                    </ul>
                </div>

                <div>
                    <p class="text-sm font-semibold">Resources</p>
                    <ul class="mt-4 space-y-3 text-sm text-slate-500 dark:text-slate-400">
                        <li><a href="#" class="hover:text-slate-900 dark:hover:text-white">Documentation</a></li>
                        <li><a href="#" class="hover:text-slate-900 dark:hover:text-white">API Reference</a></li>
                        <li><a href="#" class="hover:text-slate-900 dark:hover:text-white">Changelog</a></li>
                        <li><a href="#" class="hover:text-slate-900 dark:hover:text-white">Status</a></li>
                    </ul>
                </div>

                <div>
                    <p class="text-sm font-semibold">Company</p>
                    <ul class="mt-4 space-y-3 text-sm text-slate-500 dark:text-slate-400">
                        <li><a href="#" class="hover:text-slate-900 dark:hover:text-white">About</a></li>
                        <li><a href="#" class="hover:text-slate-900 dark:hover:text-white">Contact</a></li>
                        <li><a href="#" class="hover:text-slate-900 dark:hover:text-white">Privacy Policy</a></li>
                        <li><a href="#" class="hover:text-slate-900 dark:hover:text-white">Terms of Service</a></li>
                    </ul>
                </div>
            </div>

            <div class="mt-12 flex flex-col items-center justify-between gap-4 border-t border-slate-200 pt-8 text-xs text-slate-400 sm:flex-row dark:border-slate-800">
                <p>&copy; {{ date('Y') }} Forge AI. All rights reserved.</p>
                <p>Built with Laravel &amp; Tailwind CSS.</p>
            </div>
        </div>
    </footer>

</body>
</html>
