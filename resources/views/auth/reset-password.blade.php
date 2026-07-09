<x-guest-layout>
    <div class="mb-6 text-center">
        <h2 class="text-xl font-bold text-slate-900 dark:text-white">Set a new password</h2>
        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Choose a strong password for your account</p>
    </div>

    <form method="POST" action="{{ route('password.store') }}" class="space-y-4">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <x-forms.floating-input
            id="email" name="email" type="email" label="Email"
            :value="old('email', $request->email)" required autofocus autocomplete="username"
            :error="$errors->first('email')"
        />
        <x-input-error :messages="$errors->get('email')" class="-mt-2" />

        <x-forms.floating-input
            id="password" name="password" type="password" label="New password"
            required autocomplete="new-password"
            :error="$errors->first('password')"
        />
        <x-input-error :messages="$errors->get('password')" class="-mt-2" />

        <x-forms.floating-input
            id="password_confirmation" name="password_confirmation" type="password" label="Confirm password"
            required autocomplete="new-password"
            :error="$errors->first('password_confirmation')"
        />
        <x-input-error :messages="$errors->get('password_confirmation')" class="-mt-2" />

        <x-primary-button class="w-full">
            {{ __('Reset password') }}
        </x-primary-button>
    </form>
</x-guest-layout>
