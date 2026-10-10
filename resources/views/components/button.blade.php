@props([
    'href',
    'variant' => 'dark',
])

@php
    $variantClasses = match ($variant) {
        'light' => 'bg-paper text-ink',
        'outline-light' => 'border border-paper/40 text-paper hover:border-paper',
        'outline-dark' => 'border border-ink/30 text-ink hover:border-ink',
        default => 'bg-ink text-paper',
    };
@endphp

<a href="{{ $href }}" data-magnetic {{ $attributes->class(['group relative inline-flex items-center gap-3 overflow-hidden rounded-full py-4 pl-7 pr-5 text-sm font-medium transition-colors duration-500 ease-expo', $variantClasses]) }}>
    <span class="roll relative z-10">
        <span>{{ $slot }}</span>
        <span aria-hidden="true">{{ $slot }}</span>
    </span>
    <span class="relative z-10 flex size-7 items-center justify-center rounded-full border border-current/30">
        <x-phosphor-arrow-up-right-thin class="size-4 transition-transform duration-500 ease-expo group-hover:rotate-45" aria-hidden="true" />
    </span>
</a>
