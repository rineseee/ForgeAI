<section>
    <header>
        <h2 class="text-lg font-semibold text-slate-900 dark:text-white">
            {{ __('Notification Preferences') }}
        </h2>

        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
            {{ __('Choose which events show a toast notification.') }}
        </p>
    </header>

    <form method="post" action="{{ route('preferences.notifications.update') }}" class="mt-6">
        @csrf
        @method('patch')

        <div class="divide-y divide-slate-100 dark:divide-slate-800">
            <x-forms.toggle
                name="notify_analysis_complete"
                :checked="$user->notify_analysis_complete"
                label="Analysis finishes"
                description="Notify me when an AI analysis completes."
            />
            <x-forms.toggle
                name="notify_sync_complete"
                :checked="$user->notify_sync_complete"
                label="Repository sync completes"
                description="Notify me when repositories finish syncing from GitHub."
            />
            <x-forms.toggle
                name="notify_github_connected"
                :checked="$user->notify_github_connected"
                label="GitHub connection succeeds"
                description="Notify me when a GitHub account is connected."
            />
            <x-forms.toggle
                name="notify_on_errors"
                :checked="$user->notify_on_errors"
                label="Errors occur"
                description="Notify me when an analysis or sync fails."
            />
        </div>

        <div class="mt-4 flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'notification-preferences-updated')
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
