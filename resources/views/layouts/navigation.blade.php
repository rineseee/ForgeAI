<header class="sticky top-0 z-30 flex h-16 shrink-0 items-center gap-4 border-b border-slate-200 bg-white/80 px-4 backdrop-blur-md print:hidden sm:px-6 lg:px-8 dark:border-slate-800 dark:bg-slate-900/80">
    <!-- Mobile: hamburger + compact brand -->
    <button
        x-on:click="sidebarOpen = true"
        type="button"
        class="-ml-1 inline-flex items-center justify-center rounded-md p-2 text-slate-500 transition hover:bg-slate-100 lg:hidden dark:text-slate-400 dark:hover:bg-slate-800"
    >
        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
    </button>

    <a href="{{ route('dashboard') }}" class="flex items-center gap-2 lg:hidden">
        <x-application-logo class="h-7 w-7 fill-current text-slate-600 dark:text-slate-400" />
    </a>

    <!-- Search -->
    <div class="hidden max-w-md flex-1 sm:block">
        <div class="relative">
            <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400 dark:text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 10.5a6.5 6.5 0 11-13 0 6.5 6.5 0 0113 0z" />
            </svg>
            <input
                type="search"
                placeholder="Search repositories, analyses, reports..."
                class="w-full rounded-lg border-slate-200 bg-slate-50 py-2 pl-9 pr-3 text-sm text-slate-700 placeholder:text-slate-400 focus:border-slate-500 focus:bg-white focus:ring-slate-500 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200 dark:placeholder:text-slate-500 dark:focus:bg-slate-800 dark:focus:border-slate-500"
            >
        </div>
    </div>

    <div class="ml-auto flex items-center gap-2 sm:gap-3">
        @if (Auth::user()->currentTeam)
            <span class="hidden items-center gap-1.5 rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600 sm:inline-flex dark:bg-slate-800 dark:text-slate-300">
                {{ Auth::user()->currentTeam->name }}
            </span>
        @endif

        @php $currentRole = Auth::user()->getRoleNames()->first(); @endphp
        @if ($currentRole)
            <span @class([
                'hidden sm:inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-semibold capitalize',
                'bg-slate-100 text-slate-700 dark:bg-slate-500/15 dark:text-slate-400' => $currentRole === 'owner',
                'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/15 dark:text-emerald-400' => $currentRole === 'developer',
            ])>
                {{ $currentRole }}
            </span>
        @endif

        <!-- Theme toggle -->
        <button
            type="button"
            x-on:click="$store.theme.toggle()"
            class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-slate-500 transition hover:bg-slate-100 focus:outline-none dark:text-slate-400 dark:hover:bg-slate-800"
        >
            <svg x-show="!$store.theme.dark" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-6.364-.386 1.591-1.591M3 12h2.25m.386-6.364 1.591 1.591M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" />
            </svg>
            <svg x-show="$store.theme.dark" x-cloak class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z" />
            </svg>
        </button>

        <x-dropdown align="right" width="48">
            <x-slot name="trigger">
                <button class="inline-flex items-center gap-2 rounded-lg px-2 py-1.5 text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-slate-900 focus:outline-none dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-white">
                    <span class="flex h-7 w-7 items-center justify-center rounded-full bg-gradient-to-br from-slate-500 to-slate-600 text-xs font-semibold text-white">
                        {{ Str::of(Auth::user()->name)->substr(0, 1)->upper() }}
                    </span>
                    <span class="hidden sm:block">{{ Auth::user()->name }}</span>

                    <svg class="h-4 w-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            </x-slot>

            <x-slot name="content">
                <x-dropdown-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-dropdown-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-dropdown-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-dropdown-link>
                </form>
            </x-slot>
        </x-dropdown>
    </div>
</header>
