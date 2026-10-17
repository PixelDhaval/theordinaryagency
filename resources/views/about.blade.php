@extends('layouts.app')

@section('title', 'About Us | The Ordinary Agency Design Studio')
@section('description', 'Meet the studio behind the work - our story, our approach to minimal, elegant design, and the people doing it. See our process and get in touch.')

@php
    $pageSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'AboutPage',
        'name' => 'About The Ordinary Agency',
        'url' => route('about'),
        'about' => ['@id' => url('/').'#organization'],
    ];
@endphp

@push('schema')
    <x-schema :data="$pageSchema" />
@endpush

@section('content')
    <x-page-header :breadcrumbs="[['label' => 'About']]" eyebrow="About">
        About <span class="accent">The Ordinary Agency</span>
    </x-page-header>

    {{-- Founding story and team sections stay out until the real story, names and photos are supplied (content/pages/about.md). --}}

    <section class="bg-paper px-5 pb-28 text-ink sm:px-10 sm:pb-40">
        <div class="grid grid-cols-1 gap-16 border-t border-border pt-16 lg:grid-cols-12">
            <div class="lg:col-span-6">
                <x-eyebrow number="01" class="text-fg-muted" data-reveal>Our approach</x-eyebrow>
                <h2 data-split-lines class="mt-10 text-h1 font-medium leading-[1] tracking-[-0.035em]">
                    How we think <span class="accent">about design</span>
                </h2>
                <p data-reveal class="mt-10 max-w-xl text-h3 leading-[1.45] tracking-[-0.01em]">
                    Minimal doesn’t mean empty - it means nothing is on the page (or the package, the deck, the screen) that isn’t earning its place.
                </p>
                <p data-reveal class="mt-6 max-w-xl text-fg-muted">
                    We’d rather spend time removing what doesn’t matter than adding what might. That’s the standard we hold every branding, packaging, pitch deck, social, brochure, and UI/UX project to.
                </p>
            </div>
            <figure class="lg:col-span-5 lg:col-start-8">
                <div data-clip-reveal class="relative aspect-[4/5] overflow-hidden">
                    <img src="{{ asset('img/home/philosophy.webp') }}" alt="A single sprig in a small vase on an empty white table" width="1200" height="1500" loading="lazy" class="h-full w-full object-cover">
                </div>
            </figure>
        </div>
    </section>

    <section class="bg-paper px-5 pb-28 text-ink sm:px-10 sm:pb-40">
        <div class="border-t border-border pt-16">
            <x-eyebrow number="02" class="text-fg-muted" data-reveal>What we do</x-eyebrow>
            <ul class="mt-12 grid grid-cols-1 gap-x-10 sm:grid-cols-2 lg:grid-cols-3">
                @foreach (\App\Support\ServiceCatalog::all() as $service)
                    <li data-reveal class="border-t border-border">
                        <a href="{{ route('services.show', $service->slug) }}" class="group flex items-center justify-between gap-4 py-6">
                            <span class="text-h3 font-medium tracking-[-0.03em]">{{ $service->name }}</span>
                            <x-phosphor-arrow-up-right-thin class="size-5 shrink-0 transition-transform duration-500 ease-expo group-hover:rotate-45" aria-hidden="true" />
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </section>

    <section class="bg-paper px-5 pb-28 text-ink sm:px-10 sm:pb-40">
        <div class="flex flex-col gap-10 border-t border-border pt-16 md:flex-row md:items-end md:justify-between">
            <div>
                <x-eyebrow number="03" class="text-fg-muted" data-reveal>See it in action</x-eyebrow>
                <p data-reveal class="mt-8 max-w-xl text-h2 font-medium leading-[1.1] tracking-[-0.03em]">
                    Curious what this looks like in practice?
                </p>
            </div>
            <div data-reveal class="flex flex-wrap gap-4">
                <x-button :href="route('work')" variant="outline-dark">See our work</x-button>
                <x-button :href="route('process')" variant="outline-dark">Read about our process</x-button>
            </div>
        </div>
    </section>

    <x-cta-band>Let’s <span class="accent">talk.</span></x-cta-band>
@endsection
