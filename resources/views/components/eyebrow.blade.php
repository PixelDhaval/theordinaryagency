@props([
    'number' => null,
])

<p {{ $attributes->class(['flex items-center gap-3 text-eyebrow uppercase tracking-[0.25em]']) }}>
    @if ($number)
        <span class="tabular-nums">({{ $number }})</span>
        <span class="h-px w-8 bg-current opacity-40"></span>
    @endif
    <span>{{ $slot }}</span>
</p>
