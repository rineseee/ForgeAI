<x-guest-layout>
    <div class="mb-6 text-center">
        <h2 class="text-xl font-bold text-slate-900 dark:text-white">Forgot your password?</h2>
        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
            {{ __('No problem. Enter your email and we\'ll send you a password reset link.') }}
        </p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
        @csrf

        <x-forms.floating-input
            id="email" name="email" type="email" label="Email"
            :value="old('email')" required autofocus
            :error="$errors->first('email')"
        />
        <x-input-error :messages="$errors->get('email')" class="-mt-2" />

        <x-primary-button class="w-full">
            {{ __('Email password reset link') }}
        </x-primary-button>
    </form>

    <p class="mt-6 text-center text-sm text-slate-500 dark:text-slate-400">
        <a href="{{ route('login') }}" class="font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400">{{ __('Back to log in') }}</a>
    </p>
</x-guest-layout>
