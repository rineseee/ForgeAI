<section>
    <header>
        <h2 class="text-lg font-semibold text-slate-900 dark:text-white">
            {{ __('Theme Preference') }}
        </h2>

        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
            {{ __('Sets your default appearance. You can still toggle dark mode anytime from the top bar.') }}
        </p>
    </header>

    <form
        method="post"
        action="{{ route('preferences.theme.update') }}"
        class="mt-6"
        x-on:submit="localStorage.removeItem('theme')"
    >
        @csrf
        @method('patch')

        <div class="grid grid-cols-3 gap-3">
            @foreach ([
                'light' => ['Light', 'M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-6.364-.386 1.591-1.591M3 12h2.25m.386-6.364 1.591 1.591M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z'],
                'dark' => ['Dark', 'M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z'],
                'system' => ['System', 'M9 17.25v1.007a3 3 0 01-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0115 18.257V17.25m6-12V15a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 15V5.25m18 0A2.25 2.25 0 0018.75 3H5.25A2.25 2.25 0 003 5.25m18 0V12a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 12V5.25'],
            ] as $value => [$label, $icon])
                <label class="relative flex cursor-pointer flex-col items-center gap-2 rounded-lg border border-slate-200 px-4 py-3 text-center transition-colors has-[:checked]:border-slate-500 has-[:checked]:bg-slate-50 dark:border-slate-700 dark:has-[:checked]:bg-slate-500/10">
                    <input type="radio" name="theme_preference" value="{{ $value }}" @checked($user->theme_preference === $value) class="sr-only">
                    <svg class="h-5 w-5 text-slate-500 dark:text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $icon }}" /></svg>
                    <span class="text-xs font-medium text-slate-700 dark:text-slate-200">{{ __($label) }}</span>
                </label>
            @endforeach
        </div>

        <div class="mt-4 flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'theme-preferences-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-slate-500 dark:text-slate-400"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
