<x-guest-layout>
    <div class="mb-6 text-center">
        <h2 class="text-xl font-bold text-slate-900 dark:text-white">Confirm your password</h2>
        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
            {{ __('This is a secure area. Please confirm your password before continuing.') }}
        </p>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-4">
        @csrf

        <x-forms.floating-input
            id="password" name="password" type="password" label="Password"
            required autocomplete="current-password"
            :error="$errors->first('password')"
        />
        <x-input-error :messages="$errors->get('password')" class="-mt-2" />

        <x-primary-button class="w-full">
            {{ __('Confirm') }}
        </x-primary-button>
    </form>
</x-guest-layout>
