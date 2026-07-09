<x-guest-layout>
    <div class="mb-6 text-center">
        <h2 class="text-xl font-bold text-slate-900 dark:text-white">Welcome back</h2>
        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Log in to your Forge AI workspace</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <x-forms.floating-input
            id="email" name="email" type="email" label="Email"
            :value="old('email')" required autofocus autocomplete="username"
            :error="$errors->first('email')"
        />
        <x-input-error :messages="$errors->get('email')" class="-mt-2" />

        <x-forms.floating-input
            id="password" name="password" type="password" label="Password"
            required autocomplete="current-password"
            :error="$errors->first('password')"
        />
        <x-input-error :messages="$errors->get('password')" class="-mt-2" />

        <!-- Remember Me -->
        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center gap-2">
                <input id="remember_me" type="checkbox" class="rounded border-slate-300 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:border-slate-600 dark:bg-slate-800" name="remember">
                <span class="text-sm text-slate-600 dark:text-slate-400">{{ __('Remember me') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif
        </div>

        <x-primary-button class="w-full">
            {{ __('Log in') }}
        </x-primary-button>
    </form>

    @if (Route::has('register'))
        <p class="mt-6 text-center text-sm text-slate-500 dark:text-slate-400">
            {{ __("Don't have an account?") }}
            <a href="{{ route('register') }}" class="font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400">{{ __('Sign up') }}</a>
        </p>
    @endif
</x-guest-layout>
