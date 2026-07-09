@props(['messages'])

@if ($messages)
    <ul {{ $attributes->merge(['class' => 'flex flex-col gap-1 text-sm text-red-600 dark:text-red-400']) }}>
        @foreach ((array) $messages as $message)
            <li class="flex items-center gap-1.5">
                <svg class="h-3.5 w-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                </svg>
                {{ $message }}
            </li>
        @endforeach
    </ul>
@endif
