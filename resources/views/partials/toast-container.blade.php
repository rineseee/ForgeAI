<div
    x-data
    class="pointer-events-none fixed inset-x-0 bottom-0 z-50 flex flex-col items-end gap-2 p-4 sm:p-6 print:hidden"
>
    <template x-for="toast in $store.toasts.items" :key="toast.id">
        <div
            x-show="true"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-2 sm:translate-y-0 sm:translate-x-4"
            x-transition:enter-end="opacity-100 translate-y-0 sm:translate-x-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="pointer-events-auto flex w-full max-w-sm items-start gap-3 rounded-xl border p-4 shadow-lg backdrop-blur"
            :class="{
                'border-emerald-200 bg-emerald-50/95 dark:border-emerald-500/20 dark:bg-slate-900/95': toast.type === 'success',
                'border-amber-200 bg-amber-50/95 dark:border-amber-500/20 dark:bg-slate-900/95': toast.type === 'warning',
                'border-red-200 bg-red-50/95 dark:border-red-500/20 dark:bg-slate-900/95': toast.type === 'error',
                'border-slate-200 bg-white/95 dark:border-slate-700 dark:bg-slate-900/95': toast.type === 'info',
            }"
        >
            <span
                class="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-full"
                :class="{
                    'bg-emerald-100 text-emerald-600 dark:bg-emerald-500/15 dark:text-emerald-400': toast.type === 'success',
                    'bg-amber-100 text-amber-600 dark:bg-amber-500/15 dark:text-amber-400': toast.type === 'warning',
                    'bg-red-100 text-red-600 dark:bg-red-500/15 dark:text-red-400': toast.type === 'error',
                    'bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-300': toast.type === 'info',
                }"
            >
                <svg x-show="toast.type === 'success'" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                <svg x-show="toast.type === 'warning'" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" /></svg>
                <svg x-show="toast.type === 'error'" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                <svg x-show="toast.type === 'info'" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" /></svg>
            </span>

            <p class="min-w-0 flex-1 text-sm text-slate-700 dark:text-slate-200" x-text="toast.message"></p>

            <button type="button" x-on:click="$store.toasts.dismiss(toast.id)" class="shrink-0 text-slate-400 transition hover:text-slate-600 dark:hover:text-slate-200">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>
    </template>
</div>

@if (session('toast'))
    <script>
        window.addEventListener('DOMContentLoaded', () => {
            window.dispatchEvent(new CustomEvent('toast', { detail: @json(session('toast')) }));
        });
    </script>
@endif
