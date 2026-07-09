<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-slate-800 dark:text-white">{{ __('Analyses') }}</h2>
    </x-slot>

    <div class="py-8">
        <div
            x-data="{
                search: '',
                type: '',
                status: '',
                sort: 'date_desc',
                apply() {
                    const list = this.$refs.list;
                    const rows = Array.from(list.children);

                    rows.forEach(row => {
                        const matchesSearch = !this.search || row.dataset.name.toLowerCase().includes(this.search.toLowerCase());
                        const matchesType = !this.type || row.dataset.type === this.type;
                        const matchesStatus = !this.status || row.dataset.status === this.status;
                        row.style.display = (matchesSearch && matchesType && matchesStatus) ? '' : 'none';
                    });

                    rows.sort((a, b) => this.sort === 'date_asc' ? a.dataset.date - b.dataset.date : b.dataset.date - a.dataset.date);
                    rows.forEach(row => list.appendChild(row));
                }
            }"
            x-init="apply()"
            class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8"
        >
            <!-- Toolbar -->
            <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center">
                <div class="relative flex-1">
                    <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 10.5a6.5 6.5 0 11-13 0 6.5 6.5 0 0113 0z" /></svg>
                    <input
                        type="search" x-model="search" x-on:input.debounce.150ms="apply()"
                        placeholder="Search by repository or type..."
                        class="w-full rounded-lg border-slate-300 bg-white py-2 pl-9 pr-3 text-sm shadow-sm placeholder:text-slate-400 focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100 dark:placeholder:text-slate-500"
                    >
                </div>

                <select x-model="type" x-on:change="apply()" class="rounded-lg border-slate-300 bg-white text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100">
                    <option value="">All types</option>
                    <option value="code_review">Code Review</option>
                    <option value="security">Security</option>
                    <option value="quality">Code Quality</option>
                    <option value="tech_debt">Technical Debt</option>
                    <option value="documentation">Documentation</option>
                </select>

                <select x-model="status" x-on:change="apply()" class="rounded-lg border-slate-300 bg-white text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100">
                    <option value="">All statuses</option>
                    <option value="completed">Completed</option>
                    <option value="running">Running</option>
                    <option value="queued">Queued</option>
                    <option value="failed">Failed</option>
                </select>

                <select x-model="sort" x-on:change="apply()" class="rounded-lg border-slate-300 bg-white text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100">
                    <option value="date_desc">Newest first</option>
                    <option value="date_asc">Oldest first</option>
                </select>
            </div>

            <div class="rounded-xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
                @if ($analyses->isEmpty())
                    <x-dashboard.empty-state title="No analyses have been run" description="Run your first AI analysis on a connected repository." />
                @else
                    <div x-ref="list" class="divide-y divide-slate-100 dark:divide-slate-800">
                        @foreach ($analyses as $analysis)
                            <div
                                data-name="{{ ($analysis->repository->name ?? '').' '.$analysis->type }}"
                                data-type="{{ $analysis->type }}"
                                data-status="{{ $analysis->status }}"
                                data-date="{{ $analysis->completed_at?->timestamp ?? $analysis->created_at->timestamp }}"
                            >
                                <x-dashboard.analysis-row :analysis="$analysis" />
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="mt-4">
                {{ $analyses->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
