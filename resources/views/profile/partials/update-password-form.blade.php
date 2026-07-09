<section>
    <header>
        <h2 class="text-lg font-semibold text-slate-900 dark:text-white">
            {{ __('Update Password') }}
        </h2>

        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <x-forms.floating-input
            id="update_password_current_password" name="current_password" type="password" label="Current password"
            autocomplete="current-password"
            :error="$errors->updatePassword->first('current_password')"
        />
        <x-input-error :messages="$errors->updatePassword->get('current_password')" class="-mt-2" />

        <x-forms.floating-input
            id="update_password_password" name="password" type="password" label="New password"
            autocomplete="new-password"
            :error="$errors->updatePassword->first('password')"
        />
        <x-input-error :messages="$errors->updatePassword->get('password')" class="-mt-2" />

        <x-forms.floating-input
            id="update_password_password_confirmation" name="password_confirmation" type="password" label="Confirm password"
            autocomplete="new-password"
            :error="$errors->updatePassword->first('password_confirmation')"
        />
        <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="-mt-2" />

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'password-updated')
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
