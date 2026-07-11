<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-slate-800 dark:text-white">{{ __('Repositories') }}</h2>
    </x-slot>

    <div class="py-8">
        <div
            x-data="{
                search: '',
                language: '',
                visibility: '',
                sort: 'date_desc',
                apply() {
                    const list = this.$refs.list;
                    const rows = Array.from(list.children);

                    rows.forEach(row => {
                        const matchesSearch = !this.search || row.dataset.name.toLowerCase().includes(this.search.toLowerCase());
                        const matchesLang = !this.language || row.dataset.lang === this.language;
                        const matchesVisibility = !this.visibility || row.dataset.visibility === this.visibility;
                        row.style.display = (matchesSearch && matchesLang && matchesVisibility) ? '' : 'none';
                    });

                    rows.sort((a, b) => {
                        if (this.sort === 'name_asc') return a.dataset.name.localeCompare(b.dataset.name);
                        if (this.sort === 'name_desc') return b.dataset.name.localeCompare(a.dataset.name);
                        if (this.sort === 'date_asc') return a.dataset.date - b.dataset.date;
                        return b.dataset.date - a.dataset.date;
                    });
                    rows.forEach(row => list.appendChild(row));
                }
            }"
            x-init="apply()"
            class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8"
        >
            @if (session('status'))
                <div class="mb-4 rounded-lg bg-emerald-50 px-4 py-3 text-sm text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400">
                    {{ session('status') }}
                </div>
            @endif

            <!-- Toolbar -->
            <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center">
                <div class="relative flex-1">
                    <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 10.5a6.5 6.5 0 11-13 0 6.5 6.5 0 0113 0z" /></svg>
                    <input
                        type="search" x-model="search" x-on:input.debounce.150ms="apply()"
                        placeholder="Search repositories..."
                        class="w-full rounded-lg border-slate-300 bg-white py-2 pl-9 pr-3 text-sm shadow-sm placeholder:text-slate-400 focus:border-slate-500 focus:ring-slate-500 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100 dark:placeholder:text-slate-500"
                    >
                </div>

                <select x-model="language" x-on:change="apply()" class="rounded-lg border-slate-300 bg-white text-sm shadow-sm focus:border-slate-500 focus:ring-slate-500 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100">
                    <option value="">All languages</option>
                    @foreach ($repositories->pluck('language')->filter()->unique()->sort() as $lang)
                        <option value="{{ $lang }}">{{ $lang }}</option>
                    @endforeach
                </select>

                <select x-model="visibility" x-on:change="apply()" class="rounded-lg border-slate-300 bg-white text-sm shadow-sm focus:border-slate-500 focus:ring-slate-500 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100">
                    <option value="">All visibility</option>
                    <option value="public">Public</option>
                    <option value="private">Private</option>
                </select>

                <select x-model="sort" x-on:change="apply()" class="rounded-lg border-slate-300 bg-white text-sm shadow-sm focus:border-slate-500 focus:ring-slate-500 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100">
                    <option value="date_desc">Recently synced</option>
                    <option value="date_asc">Oldest synced</option>
                    <option value="name_asc">Name (A-Z)</option>
                    <option value="name_desc">Name (Z-A)</option>
                </select>

                @if ($hasGithubConnection)
                    <form method="post" action="{{ route('repositories.sync') }}">
                        @csrf
                        <x-secondary-button type="submit">{{ __('Sync repositories') }}</x-secondary-button>
                    </form>
                @else
                    <a href="{{ route('github.connect') }}" class="inline-flex items-center justify-center gap-2 rounded-lg bg-gradient-to-r from-slate-600 to-slate-700 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition-all duration-150 hover:from-slate-500 hover:to-slate-600 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2 dark:focus:ring-offset-slate-900">
                        {{ __('Connect GitHub') }}
                    </a>
                @endif

                <form method="post" action="{{ route('repositories.import') }}" class="flex items-center gap-2">
                    @csrf
                    <input
                        type="text" name="repository" required
                        placeholder="owner/repo or GitHub URL"
                        class="w-56 rounded-lg border-slate-300 bg-white py-2 px-3 text-sm shadow-sm placeholder:text-slate-400 focus:border-slate-500 focus:ring-slate-500 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100 dark:placeholder:text-slate-500"
                    >
                    <x-secondary-button type="submit">{{ __('Import public repo') }}</x-secondary-button>
                </form>
            </div>

            <div class="rounded-xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
                @if ($repositories->isEmpty())
                    <x-dashboard.empty-state
                        :title="$hasGithubConnection ? 'No repositories imported' : 'No repositories connected'"
                        :description="$hasGithubConnection ? 'Sync your GitHub account to import repositories.' : 'Connect a GitHub account to get started.'"
                    >
                        <x-slot name="action">
                            @if ($hasGithubConnection)
                                <form method="post" action="{{ route('repositories.sync') }}">
                                    @csrf
                                    <x-primary-button type="submit">{{ __('Import repositories') }}</x-primary-button>
                                </form>
                            @else
                                <a href="{{ route('github.connect') }}" class="inline-flex items-center justify-center gap-2 rounded-lg bg-gradient-to-r from-slate-600 to-slate-700 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition-all duration-150 hover:from-slate-500 hover:to-slate-600 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2 dark:focus:ring-offset-slate-900">
                                    {{ __('Connect GitHub') }}
                                </a>
                            @endif
                        </x-slot>
                    </x-dashboard.empty-state>
                @else
                    <div x-ref="list" class="divide-y divide-slate-100 dark:divide-slate-800">
                        @foreach ($repositories as $repository)
                            <div data-name="{{ $repository->full_name }}" data-lang="{{ $repository->language }}" data-visibility="{{ $repository->visibility }}" data-date="{{ $repository->last_synced_at?->timestamp ?? 0 }}">
                                <x-dashboard.repository-row :repository="$repository" />
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="mt-4">
                {{ $repositories->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
