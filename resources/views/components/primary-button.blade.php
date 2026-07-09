<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center gap-2 rounded-lg bg-gradient-to-r from-indigo-600 to-violet-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition-all duration-150 hover:from-indigo-500 hover:to-violet-500 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 active:from-indigo-700 active:to-violet-700 disabled:cursor-not-allowed disabled:opacity-50 dark:focus:ring-offset-slate-900']) }}>
    {{ $slot }}
</button>
