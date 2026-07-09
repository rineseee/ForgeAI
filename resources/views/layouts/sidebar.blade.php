<!-- Mobile backdrop -->
<div
    x-show="sidebarOpen"
    x-transition:enter="transition-opacity ease-linear duration-200"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition-opacity ease-linear duration-150"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-40 bg-slate-900/50 backdrop-blur-sm lg:hidden"
    x-on:click="sidebarOpen = false"
    style="display: none;"
    x-cloak
></div>

<!-- Mobile off-canvas drawer -->
<aside
    x-show="sidebarOpen"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="-translate-x-full"
    x-transition:enter-end="translate-x-0"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="translate-x-0"
    x-transition:leave-end="-translate-x-full"
    class="fixed inset-y-0 left-0 z-50 flex w-64 flex-col border-r border-slate-200 bg-white lg:hidden dark:border-slate-800 dark:bg-slate-900"
    style="display: none;"
    x-cloak
>
    @include('layouts.partials.sidebar-content', ['collapsible' => false])
</aside>

<!-- Desktop static sidebar -->
<aside
    class="hidden shrink-0 flex-col border-r border-slate-200 bg-white transition-all duration-200 ease-in-out lg:flex dark:border-slate-800 dark:bg-slate-900"
    :class="sidebarCollapsed ? 'lg:w-20' : 'lg:w-64'"
>
    @include('layouts.partials.sidebar-content', ['collapsible' => true])
</aside>
