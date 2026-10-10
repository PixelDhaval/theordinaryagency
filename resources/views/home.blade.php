@extends('layouts.app')

@section('title', 'Branding & Design Agency | The Ordinary Agency')
@section('description', 'A branding and design studio for packaging, pitch decks, social, brochures, and UI/UX. See the work, then book a call to start your project.')

@push('head')
    <link rel="preload" as="image" href="{{ asset('img/home/hero-ring.webp') }}" fetchpriority="high">
@endpush

@php
    $services = [
        ['title' => 'Branding', 'image' => 'branding', 'desc' => 'Identity, strategy, and visual systems that hold up everywhere your brand shows up.'],
        ['title' => 'Packaging Design', 'image' => 'packaging', 'desc' => 'Shelf-ready packaging, from concept to print-ready files.'],
        ['title' => 'Pitch Deck Design', 'image' => 'pitch-deck', 'desc' => 'Investor-ready decks that are clear, not cluttered.'],
        ['title' => 'Social Media Design', 'image' => 'social-media', 'desc' => 'On-brand templates and graphics for every platform you post on.'],
        ['title' => 'Brochure Design', 'image' => 'brochure', 'desc' => 'Print and digital collateral that keeps selling after you leave the room.'],
        ['title' => 'UI/UX Design', 'image' => 'ui-ux', 'desc' => 'Research-led, carefully designed digital products.'],
    ];

    $steps = [
        ['title' => 'Discovery', 'desc' => 'We learn your goals, audience, and constraints.'],
        ['title' => 'Concept', 'desc' => 'We explore directions before committing to one.'],
        ['title' => 'Design & Refinement', 'desc' => 'We build it out and refine with your feedback.'],
        ['title' => 'Delivery', 'desc' => 'You get final files, guidelines, and support.'],
    ];

    $marquee = ['Branding', 'Packaging', 'Pitch Decks', 'Social Media', 'Brochures', 'UI/UX'];
@endphp

@section('content')

    {{-- ─── Hero ─────────────────────────────────────────────── --}}
    <section id="hero" data-hero class="relative h-svh min-h-[640px] overflow-hidden bg-paper text-ink">

        <div data-hero-media class="absolute inset-0 z-0 [clip-path:inset(30%_34%_24%_34%)] max-md:[clip-path:inset(34%_12%_30%_12%)]">
            <div data-hero-media-inner class="h-full w-full">
                <img data-hero-image src="{{ asset('img/home/hero-ring.webp') }}" alt="A ring of light drawn against a dark night sky" width="2000" height="1333" fetchpriority="high" class="h-full w-full object-cover">
                <div class="absolute inset-0 bg-ink/25"></div>
            </div>
        </div>

        <div class="pointer-events-none relative z-10 flex h-full flex-col justify-between px-5 pb-8 pt-28 text-white mix-blend-difference sm:px-10 sm:pt-32">
            <div class="flex items-start justify-between" data-hero-meta>
                <x-eyebrow>Branding &amp; design studio</x-eyebrow>
                <x-eyebrow class="hidden sm:flex">Six disciplines — one roof</x-eyebrow>
            </div>

            <h1 data-hero-title class="text-display font-medium leading-[0.92] tracking-[-0.045em]">
                <span class="line-mask block"><span data-hero-line class="block">Branding and design</span></span>
                <span class="line-mask block"><span data-hero-line class="block md:pl-[14vw]">agency for brands</span></span>
                <span class="line-mask block"><span data-hero-line class="block md:text-right">that want to <span class="accent">stand out</span></span></span>
            </h1>

            <div class="h-20 sm:h-16"></div>
        </div>

        <div class="absolute inset-x-0 bottom-0 z-20 flex flex-col gap-6 px-5 pb-8 sm:flex-row sm:items-end sm:justify-between sm:px-10" data-hero-foot>
            <p class="max-w-sm text-sm leading-relaxed text-fg-muted">
                A design studio that handles branding, packaging, pitch decks, social media, brochures, and UI/UX — all under one roof, all built with the same care.
            </p>
            <div class="flex items-center gap-6">
                <button type="button" data-scroll-cue class="group hidden items-center gap-2 text-sm sm:inline-flex" aria-label="Scroll to the next section">
                    <span class="roll"><span>Scroll</span><span aria-hidden="true">Scroll</span></span>
                    <x-phosphor-arrow-down-thin class="size-4 animate-bounce" aria-hidden="true" />
                </button>
                <x-button :href="route('contact')">Book a call</x-button>
            </div>
        </div>

        <p data-hero-caption aria-hidden="true" class="pointer-events-none absolute inset-x-0 bottom-[12vh] z-10 px-5 text-center font-sans text-h1 font-medium leading-[1] tracking-[-0.035em] text-paper opacity-0 sm:px-10">
            All under one roof, <span class="accent">all built with the same care.</span>
        </p>
    </section>

    {{-- ─── Marquee ──────────────────────────────────────────── --}}
    <section class="overflow-hidden border-y border-border-inverse bg-ink py-8 text-paper sm:py-10" aria-label="Disciplines">
        <div data-marquee class="marquee-track" aria-hidden="true">
            @for ($i = 0; $i < 2; $i++)
                <div class="flex shrink-0 items-center">
                    @foreach ($marquee as $word)
                        <span class="whitespace-nowrap px-6 font-display text-[clamp(2.5rem,6vw,5.5rem)] font-light leading-none sm:px-10">{{ $word }}</span>
                        <x-phosphor-asterisk-thin class="size-[clamp(1.5rem,3vw,2.75rem)] shrink-0 text-fg-muted-inverse" />
                    @endforeach
                </div>
            @endfor
        </div>
        <p class="sr-only">Branding, packaging, pitch decks, social media, brochures, and UI/UX.</p>
    </section>

    {{-- ─── Philosophy ───────────────────────────────────────── --}}
    <section id="philosophy" class="bg-paper px-5 py-28 text-ink sm:px-10 sm:py-40">
        <div class="grid grid-cols-1 gap-16 lg:grid-cols-12">
            <div class="lg:col-span-7">
                <x-eyebrow number="01" class="text-fg-muted" data-reveal>Philosophy</x-eyebrow>

                <h2 data-split-lines class="mt-10 text-h1 font-medium leading-[1] tracking-[-0.035em]">
                    Design that’s considered, <span class="accent">not crowded.</span>
                </h2>

                <p data-scrub-words class="mt-14 max-w-3xl text-h3 leading-[1.4] tracking-[-0.01em]">
                    We keep things minimal because clarity sells better than clutter. Every project starts with understanding what a brand actually needs to say — then we design the smallest, clearest version of that. No unnecessary decoration, no filler pages, no generic templates.
                </p>

                <p data-reveal class="mt-10 max-w-md text-fg-muted">
                    That’s true of the work we make for clients, and it’s true of how we work with them.
                </p>
            </div>

            <figure class="lg:col-span-4 lg:col-start-9">
                <div data-clip-reveal class="relative aspect-[4/5] overflow-hidden">
                    <img src="{{ asset('img/home/philosophy.webp') }}" alt="A single sprig in a small vase on an empty white table" width="1200" height="1500" loading="lazy" data-parallax class="absolute inset-x-0 -top-[12%] h-[124%] w-full object-cover">
                </div>
                <figcaption class="mt-4 flex justify-between text-eyebrow uppercase tracking-[0.25em] text-fg-muted">
                    <span>Less, but better</span>
                    <span>(Fig. 01)</span>
                </figcaption>
            </figure>
        </div>
    </section>

    {{-- ─── Services ─────────────────────────────────────────── --}}
    <section id="services" data-services class="relative bg-paper px-5 pb-28 text-ink sm:px-10 sm:pb-40">
        <div class="flex flex-col gap-10 border-t border-border pt-10 md:flex-row md:items-end md:justify-between">
            <div>
                <x-eyebrow number="02" class="text-fg-muted" data-reveal>What we do</x-eyebrow>
                <h2 data-split-lines class="mt-10 text-h1 font-medium leading-[1] tracking-[-0.035em]">
                    Six disciplines, <span class="accent">one studio.</span>
                </h2>
            </div>
            <a href="{{ route('services') }}" data-reveal class="link-line self-start text-sm md:self-auto">
                Explore our design services <x-phosphor-arrow-up-right-thin class="size-4" aria-hidden="true" />
            </a>
        </div>

        <ul class="mt-16" data-service-list>
            @foreach ($services as $index => $service)
                <li data-service-row class="relative">
                    <span data-row-line class="absolute inset-x-0 top-0 block h-px origin-left bg-border"></span>
                    <a href="{{ route('services') }}"
                       data-service-link
                       data-service-image="{{ $service['image'] }}"
                       data-cursor="View"
                       class="group grid grid-cols-12 items-center gap-4 py-7 sm:py-9">
                        <span class="col-span-2 text-eyebrow tabular-nums text-fg-muted sm:col-span-1">0{{ $index + 1 }}</span>
                        <span class="col-span-10 text-[clamp(1.75rem,4.2vw,3.75rem)] font-medium leading-none tracking-[-0.035em] transition-transform duration-700 ease-expo group-hover:translate-x-3 sm:col-span-6 lg:col-span-5">
                            {{ $service['title'] }}
                        </span>
                        <span class="col-span-10 col-start-3 text-sm leading-relaxed text-fg-muted sm:col-span-4 sm:col-start-auto lg:col-span-5">{{ $service['desc'] }}</span>
                        <span class="col-span-12 flex justify-end sm:col-span-1">
                            <span class="flex size-11 items-center justify-center rounded-full border border-border transition-colors duration-500 ease-expo group-hover:border-ink group-hover:bg-ink group-hover:text-paper">
                                <x-phosphor-arrow-up-right-thin class="size-5 transition-transform duration-500 ease-expo group-hover:rotate-45" aria-hidden="true" />
                            </span>
                        </span>
                    </a>
                    <img src="{{ asset('img/home/'.$service['image'].'.webp') }}" alt="" loading="lazy" class="mb-6 aspect-[16/10] w-full object-cover lg:hidden">
                </li>
            @endforeach
            <li class="relative" aria-hidden="true"><span data-row-line class="absolute inset-x-0 top-0 block h-px origin-left bg-border"></span></li>
        </ul>

        <div data-service-preview class="pointer-events-none absolute left-0 top-0 z-20 hidden aspect-[4/5] w-[22vw] max-w-[340px] overflow-hidden opacity-0 lg:block" aria-hidden="true">
            @foreach ($services as $service)
                <img src="{{ asset('img/home/'.$service['image'].'.webp') }}" alt="" loading="lazy" data-preview-image="{{ $service['image'] }}" class="absolute inset-0 h-full w-full object-cover opacity-0">
            @endforeach
        </div>
    </section>

    {{-- ─── Selected work ────────────────────────────────────── --}}
    @if ($projects->isNotEmpty())
        @php
            $workLayout = [
                ['wrap' => 'md:col-span-7', 'aspect' => 'aspect-[4/5]', 'drift' => false],
                ['wrap' => 'md:col-span-5 md:mt-56', 'aspect' => 'aspect-[4/3]', 'drift' => true],
                ['wrap' => 'md:col-span-5', 'aspect' => 'aspect-[4/5]', 'drift' => true],
                ['wrap' => 'md:col-span-7 md:mt-32', 'aspect' => 'aspect-[4/3]', 'drift' => false],
            ];
        @endphp
        <section id="work" class="bg-paper px-5 pb-28 text-ink sm:px-10 sm:pb-40">
            <div class="flex flex-col gap-10 border-t border-border pt-10 md:flex-row md:items-end md:justify-between">
                <div>
                    <x-eyebrow number="03" class="text-fg-muted" data-reveal>Selected work</x-eyebrow>
                    <h2 data-split-lines class="mt-10 text-h1 font-medium leading-[1] tracking-[-0.035em]">
                        Recent <span class="accent">work.</span>
                    </h2>
                </div>
                <a href="{{ route('work') }}" data-reveal class="link-line self-start text-sm md:self-auto">
                    See all our work <x-phosphor-arrow-up-right-thin class="size-4" aria-hidden="true" />
                </a>
            </div>

            <div class="mt-16 grid grid-cols-1 gap-x-10 gap-y-20 md:grid-cols-12 md:gap-y-28">
                @foreach ($projects as $project)
                    @php($layout = $workLayout[$loop->index % count($workLayout)])
                    <div class="{{ $layout['wrap'] }}" @if ($layout['drift']) data-drift @endif>
                        <x-work-card :project="$project" :aspect="$layout['aspect']" />
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    {{-- ─── Process (horizontal on desktop) ──────────────────── --}}
    <section id="process" data-process class="relative overflow-hidden bg-ink text-paper">
        <div data-process-track class="flex flex-col md:h-svh md:w-max md:flex-row md:items-stretch">

            <div class="flex shrink-0 flex-col justify-between gap-12 px-5 py-24 sm:px-10 md:w-[46vw] md:py-28">
                <div>
                    <x-eyebrow number="04" class="text-fg-muted-inverse" data-reveal>How we work</x-eyebrow>
                    <h2 data-split-lines class="mt-10 text-h1 font-medium leading-[1] tracking-[-0.035em]">
                        Four steps, <span class="accent">every project.</span>
                    </h2>
                    <p data-reveal class="mt-8 max-w-md text-fg-muted-inverse">
                        The same four-step process runs under every project — branding, packaging, pitch decks, social, brochures, UI/UX. What changes is what happens inside each step.
                    </p>
                </div>
                <div class="flex items-end justify-between gap-8">
                    <div data-clip-reveal class="relative aspect-[16/10] w-full max-w-md overflow-hidden">
                        <img src="{{ asset('img/home/process.webp') }}" alt="Symmetrical escalators rising through a modern station" width="2400" height="1500" loading="lazy" class="h-full w-full object-cover">
                    </div>
                </div>
            </div>

            @foreach ($steps as $index => $step)
                <article data-process-step class="flex shrink-0 flex-col justify-between border-t border-border-inverse px-5 py-14 sm:px-10 md:w-[34vw] md:min-w-[380px] md:border-l md:border-t-0 md:py-28">
                    <span class="font-display text-[clamp(5rem,12vw,11rem)] font-light leading-[0.8] text-paper/90">0{{ $index + 1 }}</span>
                    <div class="mt-16 md:mt-0">
                        <h3 class="text-h2 font-medium tracking-[-0.03em]">{{ $step['title'] }}</h3>
                        <p class="mt-4 max-w-xs text-fg-muted-inverse">{{ $step['desc'] }}</p>
                    </div>
                </article>
            @endforeach

            <div class="flex shrink-0 items-end px-5 pb-24 sm:px-10 md:w-[30vw] md:min-w-[320px] md:items-center md:pb-0">
                <a href="{{ route('process') }}" class="group inline-flex items-center gap-4 font-display text-h2 font-light" data-cursor="Read">
                    <span class="link-line">See our full process</span>
                    <x-phosphor-arrow-right-thin class="size-8 transition-transform duration-500 ease-expo group-hover:translate-x-2" aria-hidden="true" />
                </a>
            </div>
        </div>

        <div class="absolute inset-x-5 bottom-8 hidden h-px bg-border-inverse sm:inset-x-10 md:block" aria-hidden="true">
            <div data-process-progress class="h-full origin-left scale-x-0 bg-paper"></div>
        </div>
    </section>

    {{-- ─── Journal ──────────────────────────────────────────── --}}
    @if ($posts->isNotEmpty())
        <section id="journal" class="bg-paper px-5 py-28 text-ink sm:px-10 sm:py-40">
            <div class="flex flex-col gap-10 md:flex-row md:items-end md:justify-between">
                <div>
                    <x-eyebrow number="05" class="text-fg-muted" data-reveal>Journal</x-eyebrow>
                    <h2 data-split-lines class="mt-10 text-h1 font-medium leading-[1] tracking-[-0.035em]">
                        Notes from <span class="accent">the studio.</span>
                    </h2>
                    <p data-reveal class="mt-6 max-w-md text-fg-muted">
                        Notes on branding, packaging, and design process — written from real projects, not generic listicles.
                    </p>
                </div>
                <a href="{{ route('journal') }}" data-reveal class="link-line self-start text-sm md:self-auto">
                    Read the journal <x-phosphor-arrow-up-right-thin class="size-4" aria-hidden="true" />
                </a>
            </div>

            <div class="mt-16 grid grid-cols-1 gap-x-8 gap-y-16 md:grid-cols-2 lg:grid-cols-3">
                @foreach ($posts as $post)
                    <x-journal-card :post="$post" />
                @endforeach
            </div>
        </section>
    @endif

    {{-- "Clients" stays out until real, permissioned logos/testimonials exist (content/pages/home.md). --}}

    {{-- ─── Closing CTA ──────────────────────────────────────── --}}
    <section id="contact-cta" class="relative overflow-hidden bg-ink text-paper">
        <div class="absolute inset-0">
            <img src="{{ asset('img/home/contact.webp') }}" alt="" width="2000" height="1167" loading="lazy" data-parallax class="absolute inset-x-0 -top-[15%] h-[130%] w-full object-cover opacity-60">
            <div class="absolute inset-0 bg-gradient-to-b from-ink via-ink/40 to-ink"></div>
        </div>

        <div class="relative flex min-h-svh flex-col items-center justify-center px-5 py-32 text-center sm:px-10">
            <x-eyebrow class="text-fg-muted-inverse" data-reveal>Let’s talk</x-eyebrow>
            <h2 data-split-lines class="mt-10 text-display font-medium leading-[0.95] tracking-[-0.045em]">
                Have a project <span class="accent">in mind?</span>
            </h2>
            <p data-reveal class="mt-8 max-w-md text-fg-muted-inverse">
                Tell us what you’re working on. We’ll figure out the right service — or combination of services — together.
            </p>

            <a href="{{ route('contact') }}" data-magnetic data-reveal data-cursor="Let's talk" class="group mt-14 flex size-40 flex-col items-center justify-center gap-2 rounded-full bg-paper text-ink transition-transform duration-700 ease-expo hover:scale-105 sm:size-48">
                <x-phosphor-arrow-up-right-thin class="size-7 transition-transform duration-500 ease-expo group-hover:rotate-45" aria-hidden="true" />
                <span class="text-sm font-medium">Book a call</span>
            </a>
        </div>
    </section>

@endsection
