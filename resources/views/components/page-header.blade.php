@props([
    'breadcrumbs' => [],
    'eyebrow' => null,
    'intro' => null,
])

<section {{ $attributes->class(['bg-paper px-5 pb-16 pt-32 text-ink sm:px-10 sm:pb-24 sm:pt-40']) }}>
    <x-breadcrumbs :items="$breadcrumbs" data-reveal />

    @if ($eyebrow)
        <x-eyebrow class="mt-16 text-fg-muted" data-reveal>{{ $eyebrow }}</x-eyebrow>
    @endif

    <h1 data-split-lines class="mt-8 max-w-6xl text-display font-medium leading-[0.95] tracking-[-0.045em]">
        {{ $slot }}
    </h1>

    @if ($intro)
        <p data-reveal class="mt-10 max-w-2xl text-h3 leading-[1.45] tracking-[-0.01em] text-fg-muted">{{ $intro }}</p>
    @endif

    {{ $after ?? '' }}
</section>
