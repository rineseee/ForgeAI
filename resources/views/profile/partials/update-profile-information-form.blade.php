<section>
    <header>
        <h2 class="text-lg font-semibold text-slate-900 dark:text-white">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <x-forms.floating-input
            id="name" name="name" type="text" label="Name"
            :value="old('name', $user->name)" required autofocus autocomplete="name"
            :error="$errors->first('name')"
        />
        <x-input-error class="-mt-2" :messages="$errors->get('name')" />

        <div>
            <x-forms.floating-input
                id="email" name="email" type="email" label="Email"
                :value="old('email', $user->email)" required autocomplete="username"
                :error="$errors->first('email')"
            />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-2 rounded-lg bg-amber-50 px-3 py-2 dark:bg-amber-500/10">
                    <p class="text-sm text-amber-800 dark:text-amber-400">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="font-medium text-amber-900 underline hover:text-amber-700 focus:outline-none dark:text-amber-300 dark:hover:text-amber-200">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 text-sm font-medium text-emerald-600 dark:text-emerald-400">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
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
