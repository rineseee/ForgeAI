<x-guest-layout>
    <div class="mb-6 text-center">
        <h2 class="text-xl font-bold text-slate-900 dark:text-white">Create your account</h2>
        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Start analyzing your repositories with AI</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <x-forms.floating-input
            id="name" name="name" type="text" label="Full name"
            :value="old('name')" required autofocus autocomplete="name"
            :error="$errors->first('name')"
        />
        <x-input-error :messages="$errors->get('name')" class="-mt-2" />

        <x-forms.floating-input
            id="email" name="email" type="email" label="Email"
            :value="old('email')" required autocomplete="username"
            :error="$errors->first('email')"
        />
        <x-input-error :messages="$errors->get('email')" class="-mt-2" />

        <x-forms.floating-input
            id="password" name="password" type="password" label="Password"
            required autocomplete="new-password"
            hint="At least 8 characters."
            :error="$errors->first('password')"
        />
        <x-input-error :messages="$errors->get('password')" class="-mt-2" />

        <x-forms.floating-input
            id="password_confirmation" name="password_confirmation" type="password" label="Confirm password"
            required autocomplete="new-password"
            :error="$errors->first('password_confirmation')"
        />
        <x-input-error :messages="$errors->get('password_confirmation')" class="-mt-2" />

        <p class="text-xs text-slate-500 dark:text-slate-400">
            {{ __('Creating an account also creates your own team, and you\'ll be assigned the Admin role.') }}
        </p>

        <x-primary-button class="w-full">
            {{ __('Create account') }}
        </x-primary-button>
    </form>

    <p class="mt-6 text-center text-sm text-slate-500 dark:text-slate-400">
        {{ __('Already have an account?') }}
        <a href="{{ route('login') }}" class="font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400">{{ __('Log in') }}</a>
    </p>
</x-guest-layout>
