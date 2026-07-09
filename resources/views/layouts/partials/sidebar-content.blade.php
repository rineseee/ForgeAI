@props(['collapsible' => false])

@php
$navItems = [
    ['route' => 'dashboard', 'label' => 'Dashboard', 'icon' => 'M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25'],
    ['route' => 'repositories.index', 'label' => 'Repositories', 'icon' => 'M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 3.75c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125'],
    ['route' => 'analyses.index', 'label' => 'Analyses', 'icon' => 'M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.75c0 5.592 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.75h-.152c-3.196 0-6.1-1.248-8.25-3.286z'],
    ['route' => 'reports.index', 'label' => 'Reports', 'icon' => 'M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z'],
];
@endphp

<div class="flex h-16 shrink-0 items-center gap-2 border-b border-slate-200 px-5 dark:border-slate-800">
    <x-application-logo class="h-7 w-7 shrink-0 fill-current text-indigo-600 dark:text-indigo-400" />
    @if ($collapsible)
        <span x-show="!sidebarCollapsed" x-cloak class="truncate text-lg font-bold tracking-tight text-slate-900 dark:text-white">Forge AI</span>
    @else
        <span class="truncate text-lg font-bold tracking-tight text-slate-900 dark:text-white">Forge AI</span>
    @endif
</div>

<nav class="flex-1 space-y-1 overflow-y-auto overflow-x-hidden px-3 py-4">
    @foreach ($navItems as $item)
        <x-dashboard.nav-link :href="route($item['route'])" :active="request()->routeIs($item['route'])" :collapsible="$collapsible" :title="$item['label']">
            <x-slot name="icon">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}" />
                </svg>
            </x-slot>
            {{ $item['label'] }}
        </x-dashboard.nav-link>
    @endforeach

    <div class="my-3 border-t border-slate-100 dark:border-slate-800"></div>

    <x-dashboard.nav-link :href="route('profile.edit')" :active="request()->routeIs('profile.edit')" :collapsible="$collapsible" title="Settings">
        <x-slot name="icon">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.28z M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
        </x-slot>
        Settings
    </x-dashboard.nav-link>
</nav>

@if ($collapsible)
    <button
        type="button"
        x-on:click="sidebarCollapsed = !sidebarCollapsed; localStorage.setItem('sidebarCollapsed', sidebarCollapsed)"
        class="flex h-11 shrink-0 items-center gap-3 border-t border-slate-200 px-5 text-slate-400 transition hover:bg-slate-100 hover:text-slate-600 dark:border-slate-800 dark:hover:bg-slate-800 dark:hover:text-slate-300"
    >
        <svg class="h-4 w-4 shrink-0 transition-transform duration-200" :class="{ 'rotate-180': sidebarCollapsed }" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
        </svg>
        <span x-show="!sidebarCollapsed" x-cloak class="text-xs font-medium">Collapse</span>
    </button>
@endif

@if ($user = auth()->user())
    <div class="border-t border-slate-200 p-4 dark:border-slate-800">
        <div @class(['flex items-center gap-3 rounded-lg bg-slate-50 px-3 py-2.5 dark:bg-slate-800/60', 'justify-center px-2' => $collapsible])
            @if ($collapsible) :class="sidebarCollapsed ? 'justify-center px-2' : ''" @endif
        >
            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-indigo-500 to-violet-500 text-xs font-semibold text-white">
                {{ \Illuminate\Support\Str::of($user->name)->substr(0, 1)->upper() }}
            </span>
            @if ($collapsible)
                <div x-show="!sidebarCollapsed" x-cloak class="min-w-0">
                    <p class="truncate text-sm font-medium text-slate-800 dark:text-slate-200">{{ $user->currentTeam->name ?? 'No team' }}</p>
                    <p class="truncate text-xs capitalize text-slate-500 dark:text-slate-400">{{ $user->getRoleNames()->first() ?? 'Member' }}</p>
                </div>
            @else
                <div class="min-w-0">
                    <p class="truncate text-sm font-medium text-slate-800 dark:text-slate-200">{{ $user->currentTeam->name ?? 'No team' }}</p>
                    <p class="truncate text-xs capitalize text-slate-500 dark:text-slate-400">{{ $user->getRoleNames()->first() ?? 'Member' }}</p>
                </div>
            @endif
        </div>
    </div>
@endif
