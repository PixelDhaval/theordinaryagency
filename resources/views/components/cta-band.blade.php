@props([
    'eyebrow' => 'Let’s talk',
    'text' => null,
    'label' => 'Book a call',
    'href' => null,
])

<section class="bg-ink px-5 py-28 text-paper sm:px-10 sm:py-40">
    <div class="flex flex-col gap-12 md:flex-row md:items-end md:justify-between">
        <div class="max-w-4xl">
            <x-eyebrow class="text-fg-muted-inverse" data-reveal>{{ $eyebrow }}</x-eyebrow>
            <h2 data-split-lines class="mt-10 text-h1 font-medium leading-[1] tracking-[-0.035em]">{{ $slot }}</h2>
            @if ($text)
                <p data-reveal class="mt-6 max-w-md text-fg-muted-inverse">{{ $text }}</p>
            @endif
        </div>
        <div data-reveal>
            <x-button :href="$href ?? route('contact')" variant="light">{{ $label }}</x-button>
        </div>
    </div>
</section>
