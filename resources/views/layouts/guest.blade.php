<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? config('app.name', 'Forge AI') }}</title>

        @include('partials.theme-init')

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-slate-900 antialiased dark:text-slate-100">
        <div class="grid min-h-screen bg-slate-50 lg:grid-cols-2 dark:bg-slate-950">
            <!-- Branding panel -->
            <div class="relative hidden flex-col justify-between overflow-hidden bg-gradient-to-br from-indigo-700 via-indigo-600 to-violet-700 px-12 py-12 text-white lg:flex">
                <div class="absolute -top-24 -right-24 h-96 w-96 rounded-full bg-white/10 blur-3xl"></div>
                <div class="absolute bottom-0 left-0 h-72 w-72 -translate-x-1/3 translate-y-1/3 rounded-full bg-violet-400/20 blur-3xl"></div>

                <a href="/" class="relative flex items-center gap-3">
                    <x-application-logo class="h-10 w-10 fill-current text-white" />
                    <span class="text-xl font-bold tracking-tight">Forge AI</span>
                </a>

                <div class="relative max-w-md">
                    <h1 class="text-3xl font-bold leading-tight">
                        Ship better code with AI-powered engineering insight.
                    </h1>
                    <p class="mt-4 text-indigo-100">
                        Connect your GitHub repositories and get automated code review, security analysis, and technical debt reports — in minutes, not sprints.
                    </p>

                    <ul class="mt-8 space-y-3 text-sm text-indigo-100">
                        <li class="flex items-center gap-3">
                            <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-white/15">
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                            </span>
                            AI code review on every pull request
                        </li>
                        <li class="flex items-center gap-3">
                            <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-white/15">
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                            </span>
                            Automated security & technical debt scans
                        </li>
                        <li class="flex items-center gap-3">
                            <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-white/15">
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                            </span>
                            Professional reports, ready to share
                        </li>
                    </ul>
                </div>

                <p class="relative text-xs text-indigo-200">&copy; {{ date('Y') }} Rinesa Krasniqi. All rights reserved.</p>
            </div>

            <!-- Form panel -->
            <div class="relative flex flex-col items-center justify-center px-6 py-12 sm:px-10">
                <button
                    type="button"
                    x-data
                    x-on:click="$store.theme.toggle()"
                    class="absolute right-4 top-4 inline-flex h-9 w-9 items-center justify-center rounded-lg text-slate-500 transition hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-800"
                >
                    <svg x-show="!$store.theme.dark" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-6.364-.386 1.591-1.591M3 12h2.25m.386-6.364 1.591 1.591M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" />
                    </svg>
                    <svg x-show="$store.theme.dark" x-cloak class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z" />
                    </svg>
                </button>

                <div class="w-full max-w-sm">
                    <a href="/" class="mb-8 flex items-center justify-center gap-2 lg:hidden">
                        <x-application-logo class="h-9 w-9 fill-current text-indigo-600 dark:text-indigo-400" />
                        <span class="text-lg font-bold text-slate-900 dark:text-white">Forge AI</span>
                    </a>

                    <div class="rounded-2xl bg-white px-6 py-8 shadow-xl shadow-slate-200/60 ring-1 ring-slate-900/5 sm:px-8 dark:bg-slate-900 dark:shadow-none dark:ring-slate-800">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
