<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-slate-800 dark:text-white">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-3xl space-y-6 px-4 sm:px-6 lg:px-8">
            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-900/5 sm:p-8 dark:bg-slate-900 dark:ring-slate-800">
                @include('profile.partials.update-profile-information-form')
            </div>

            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-900/5 sm:p-8 dark:bg-slate-900 dark:ring-slate-800">
                @include('profile.partials.github-connection-form')
            </div>

            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-900/5 sm:p-8 dark:bg-slate-900 dark:ring-slate-800">
                @include('profile.partials.ai-preferences-form')
            </div>

            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-900/5 sm:p-8 dark:bg-slate-900 dark:ring-slate-800">
                @include('profile.partials.theme-preferences-form')
            </div>

            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-900/5 sm:p-8 dark:bg-slate-900 dark:ring-slate-800">
                @include('profile.partials.notification-preferences-form')
            </div>

            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-900/5 sm:p-8 dark:bg-slate-900 dark:ring-slate-800">
                @include('profile.partials.update-password-form')
            </div>

            <div class="rounded-2xl border border-red-100 bg-white p-6 shadow-sm sm:p-8 dark:border-red-500/20 dark:bg-slate-900">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</x-app-layout>
