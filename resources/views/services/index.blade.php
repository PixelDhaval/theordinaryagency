@extends('layouts.app')

@section('title', 'Design Services | Branding, Packaging, UI/UX & More')
@section('description', 'Branding, packaging, pitch decks, social media design, brochures, and UI/UX — explore every design service this studio offers and find the right fit.')

@php
    $pageSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'CollectionPage',
        'name' => 'Design services',
        'url' => route('services'),
        'mainEntity' => [
            '@type' => 'ItemList',
            'itemListElement' => $services->values()->map(fn ($service, $index) => [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'url' => route('services.show', $service->slug),
                'name' => $service->name,
            ])->all(),
        ],
    ];
@endphp

@push('schema')
    <x-schema :data="$pageSchema" />
@endpush

@section('content')
    <x-page-header
        :breadcrumbs="[['label' => 'Services']]"
        eyebrow="Services"
        intro="We offer design services across six disciplines — branding, packaging, pitch decks, social media, brochures, and UI/UX — so you can bring one project, or every brand touchpoint, to a single studio."
    >
        Design <span class="accent">services</span>
    </x-page-header>

    {{-- Which service do you need? --}}
    <section class="bg-paper px-5 pb-28 text-ink sm:px-10 sm:pb-40">
        <div class="grid grid-cols-1 gap-12 border-t border-border pt-16 lg:grid-cols-12">
            <div class="lg:col-span-4">
                <x-eyebrow number="01" class="text-fg-muted" data-reveal>Find your fit</x-eyebrow>
                <h2 data-split-lines class="mt-10 text-h2 font-medium leading-[1.05] tracking-[-0.03em]">
                    Which service <span class="accent">do you need?</span>
                </h2>
                <p data-reveal class="mt-6 max-w-sm text-fg-muted">
                    Not sure which one fits? <a href="{{ route('contact') }}" class="link-line text-ink">Book a call</a> and we’ll help you figure it out.
                </p>
            </div>
            <ul class="lg:col-span-7 lg:col-start-6">
                @foreach ($services as $service)
                    <li data-reveal class="border-b border-border first:border-t">
                        <a href="{{ route('services.show', $service->slug) }}" class="group grid grid-cols-1 gap-2 py-6 sm:grid-cols-2 sm:items-center">
                            <span class="text-fg-muted">{{ $service->scenario }}</span>
                            <span class="flex items-center justify-between gap-4 text-h3 font-medium tracking-[-0.03em]">
                                {{ $service->name }}
                                <x-phosphor-arrow-right-thin class="size-5 shrink-0 transition-transform duration-500 ease-expo group-hover:translate-x-1" aria-hidden="true" />
                            </span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </section>

    {{-- The six services --}}
    <section class="bg-paper px-5 pb-28 text-ink sm:px-10 sm:pb-40">
        <x-eyebrow number="02" class="border-t border-border pt-16 text-fg-muted" data-reveal>Six disciplines</x-eyebrow>

        <div class="mt-16 space-y-24 sm:space-y-32">
            @foreach ($services as $service)
                <article class="grid grid-cols-1 items-center gap-10 lg:grid-cols-12 lg:gap-16">
                    <a href="{{ route('services.show', $service->slug) }}" data-cursor="View" @class(['block lg:col-span-6', 'lg:order-last lg:col-start-7' => $loop->even])>
                        <div data-clip-reveal class="relative aspect-[4/3] overflow-hidden bg-ink/5">
                            <img src="{{ asset($service->image) }}" alt="" loading="lazy" class="h-full w-full object-cover">
                        </div>
                    </a>
                    <div @class(['lg:col-span-5', 'lg:col-start-8' => $loop->odd])>
                        <span data-reveal class="block font-display text-[clamp(3rem,6vw,5rem)] font-light leading-none text-fg-muted">0{{ $loop->iteration }}</span>
                        <h2 data-split-lines class="mt-6 text-h1 font-medium leading-[1] tracking-[-0.035em]">{{ $service->name }}</h2>
                        <p data-reveal class="mt-6 text-fg-muted">{{ $service->hub_summary }}</p>
                        <ul data-reveal class="mt-8 border-t border-border">
                            @foreach ($service->hub_points as $point)
                                <li class="flex items-center gap-3 border-b border-border py-3 text-sm">
                                    <x-phosphor-plus-thin class="size-4 shrink-0 text-fg-muted" aria-hidden="true" />
                                    {{ $point }}
                                </li>
                            @endforeach
                        </ul>
                        <a data-reveal href="{{ route('services.show', $service->slug) }}" class="link-line mt-8 inline-flex text-sm">
                            Explore {{ Str::of($service->name)->lower()->finish(' design') }} <x-phosphor-arrow-up-right-thin class="size-4" aria-hidden="true" />
                        </a>
                    </div>
                </article>
            @endforeach
        </div>
    </section>

    <x-cta-band text="That’s exactly what we’re built for.">
        Have a project that spans <span class="accent">more than one?</span>
    </x-cta-band>
@endsection
