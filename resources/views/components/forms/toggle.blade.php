@props(['name', 'checked' => false, 'label' => null, 'description' => null])

<label class="flex cursor-pointer items-start justify-between gap-4 py-2">
    <span class="min-w-0">
        @if ($label)
            <span class="block text-sm font-medium text-slate-700 dark:text-slate-200">{{ $label }}</span>
        @endif
        @if ($description)
            <span class="mt-0.5 block text-xs text-slate-500 dark:text-slate-400">{{ $description }}</span>
        @endif
    </span>

    <span class="relative mt-0.5 inline-flex h-6 w-11 shrink-0 items-center">
        <input type="checkbox" name="{{ $name }}" value="1" @checked($checked) class="peer sr-only">
        <span class="absolute inset-0 rounded-full bg-slate-200 transition-colors duration-200 peer-checked:bg-slate-600 dark:bg-slate-700"></span>
        <span class="absolute left-1 h-4 w-4 rounded-full bg-white shadow-sm transition-transform duration-200 peer-checked:translate-x-5"></span>
    </span>
</label>
