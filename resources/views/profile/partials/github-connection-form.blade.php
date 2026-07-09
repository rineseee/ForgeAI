@php $connection = $githubConnection ?? null; @endphp

<section>
    <header>
        <h2 class="text-lg font-semibold text-slate-900 dark:text-white">
            {{ __('GitHub Connection') }}
        </h2>

        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
            {{ __('Connect your GitHub account to import and sync repositories.') }}
        </p>
    </header>

    <div class="mt-6 flex items-center justify-between gap-4 rounded-lg border border-slate-200 px-4 py-3 dark:border-slate-700">
        <div class="flex min-w-0 items-center gap-3">
            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-indigo-500 to-violet-500 text-xs font-semibold text-white">
                <svg class="h-4.5 w-4.5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 .5C5.65.5.5 5.65.5 12c0 5.09 3.29 9.4 7.86 10.93.57.1.78-.25.78-.55 0-.27-.01-1-.02-1.96-3.2.7-3.87-1.54-3.87-1.54-.53-1.33-1.29-1.69-1.29-1.69-1.05-.72.08-.7.08-.7 1.16.08 1.78 1.19 1.78 1.19 1.03 1.77 2.71 1.26 3.37.96.1-.75.4-1.26.73-1.55-2.55-.29-5.23-1.28-5.23-5.68 0-1.25.45-2.28 1.19-3.08-.12-.29-.52-1.46.11-3.04 0 0 .97-.31 3.18 1.18a11 11 0 0 1 5.79 0c2.2-1.49 3.17-1.18 3.17-1.18.63 1.58.23 2.75.11 3.04.74.8 1.19 1.83 1.19 3.08 0 4.41-2.69 5.38-5.25 5.67.41.36.78 1.06.78 2.14 0 1.55-.01 2.79-.01 3.17 0 .3.2.66.79.55A11.5 11.5 0 0 0 23.5 12c0-6.35-5.15-11.5-11.5-11.5Z"/></svg>
            </span>

            <div class="min-w-0">
                @if ($connection)
                    <p class="truncate text-sm font-semibold text-slate-900 dark:text-white">{{ $connection->nickname ?? __('GitHub account connected') }}</p>
                    <p class="mt-0.5 text-xs text-slate-500 dark:text-slate-400">
                        {{ __('Connected') }} {{ $connection->connected_at?->diffForHumans() }}
                    </p>
                @else
                    <p class="text-sm font-semibold text-slate-900 dark:text-white">{{ __('Not connected') }}</p>
                    <p class="mt-0.5 text-xs text-slate-500 dark:text-slate-400">{{ __('No GitHub account linked yet.') }}</p>
                @endif
            </div>
        </div>

        @if ($connection)
            <form method="post" action="{{ route('github.disconnect') }}">
                @csrf
                @method('delete')
                <x-danger-button type="submit">{{ __('Disconnect') }}</x-danger-button>
            </form>
        @else
            <a href="{{ route('github.connect') }}" class="inline-flex items-center justify-center gap-2 rounded-lg bg-gradient-to-r from-indigo-600 to-violet-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition-all duration-150 hover:from-indigo-500 hover:to-violet-500 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-slate-900">
                {{ __('Connect GitHub') }}
            </a>
        @endif
    </div>

    @if (session('status') === 'github-disconnected')
        <p class="mt-3 text-sm text-slate-500 dark:text-slate-400">{{ __('GitHub account disconnected.') }}</p>
    @endif
</section>
