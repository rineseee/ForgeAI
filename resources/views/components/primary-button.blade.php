<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center gap-2 rounded-lg bg-gradient-to-r from-slate-600 to-slate-700 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition-all duration-150 hover:from-slate-500 hover:to-slate-600 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2 active:from-slate-700 active:to-slate-800 disabled:cursor-not-allowed disabled:opacity-50 dark:focus:ring-offset-slate-900']) }}>
    {{ $slot }}
</button>
