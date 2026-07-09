@props(['content'])

@php
$html = null;

if (class_exists(\League\CommonMark\CommonMarkConverter::class)) {
    static $converter;
    $converter ??= new \League\CommonMark\CommonMarkConverter([
        'html_input' => 'strip',
        'allow_unsafe_links' => false,
    ]);
    $html = (string) $converter->convert($content ?? '');
}
@endphp

<div {{ $attributes->merge(['class' => 'prose prose-sm max-w-none prose-slate dark:prose-invert prose-headings:font-semibold prose-a:text-indigo-600 dark:prose-a:text-indigo-400']) }}>
    @if ($html)
        {!! $html !!}
    @else
        <p class="whitespace-pre-line">{{ $content }}</p>
    @endif
</div>
