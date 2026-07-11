@props(['id', 'label', 'type' => 'text', 'name' => null, 'value' => null, 'required' => false, 'autocomplete' => null, 'error' => null, 'hint' => null])

@php
$name = $name ?? $id;
@endphp

<div
    x-data="{
        value: @js(old($name, $value)),
        touched: false,
        get invalid() { return this.touched && {{ $required ? 'true' : 'false' }} && !this.value; },
    }"
    class="relative"
>
    <input
        x-model="value"
        x-on:blur="touched = true"
        type="{{ $type }}"
        id="{{ $id }}"
        name="{{ $name }}"
        @if ($required) required @endif
        @if ($autocomplete) autocomplete="{{ $autocomplete }}" @endif
        placeholder=" "
        :class="invalid && !@js((bool) $error) ? 'border-red-400 focus:border-red-500 focus:ring-red-500/30 dark:border-red-500/60' : ''"
        {{ $attributes->merge(['class' =>
            'peer block w-full rounded-lg border bg-white px-3.5 pb-2 pt-5 text-sm text-slate-900 shadow-sm transition placeholder-transparent focus:outline-none focus:ring-2 dark:bg-slate-800 dark:text-slate-100 ' .
            ($error
                ? 'border-red-400 focus:border-red-500 focus:ring-red-500/30 dark:border-red-500/60'
                : 'border-slate-300 focus:border-slate-500 focus:ring-slate-500/30 dark:border-slate-700 dark:focus:border-slate-500')
        ]) }}
    >
    <label
        for="{{ $id }}"
        class="pointer-events-none absolute left-3.5 top-2 origin-left text-xs text-slate-500 transition-all duration-150 peer-placeholder-shown:top-3.5 peer-placeholder-shown:text-sm peer-placeholder-shown:text-slate-400 peer-focus:top-2 peer-focus:text-xs peer-focus:text-slate-600 dark:text-slate-400 dark:peer-focus:text-slate-400"
    >
        {{ $label }}
    </label>

    <p x-show="invalid" x-cloak x-transition class="mt-1.5 text-xs text-red-600 dark:text-red-400">
        {{ $label }} is required.
    </p>

    @if ($hint)
        <p class="mt-1.5 text-xs text-slate-400 dark:text-slate-500">{{ $hint }}</p>
    @endif
</div>
