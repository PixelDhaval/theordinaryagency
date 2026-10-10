@extends('layouts.app')

@section('title', $service->meta_title)
@section('description', $service->meta_description)
@section('og_image', asset($service->image))

@php
    $serviceSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'Service',
        'name' => $service->heading.' services',
        'serviceType' => $service->name,
        'description' => $service->meta_description,
        'url' => route('services.show', $service->slug),
        'image' => asset($service->image),
        'provider' => ['@id' => url('/').'#organization'],
        'hasOfferCatalog' => [
            '@type' => 'OfferCatalog',
            'name' => 'What’s included',
            'itemListElement' => collect($service->included)->map(fn (string $item) => [
                '@type' => 'Offer',
                'itemOffered' => ['@type' => 'Service', 'name' => $item],
            ])->all(),
        ],
    ];

    $faqSchema = $service->faq ? [
        '@context' => 'https://schema.org',
        '@type' => 'FAQPage',
        'mainEntity' => collect($service->faq)->map(fn (array $item) => [
            '@type' => 'Question',
            'name' => $item['question'],
            'acceptedAnswer' => ['@type' => 'Answer', 'text' => $item['answer']],
        ])->all(),
    ] : null;
@endphp

@push('schema')
    <x-schema :data="$serviceSchema" />
    @if ($faqSchema)
        <x-schema :data="$faqSchema" />
    @endif
@endpush

@section('content')
    <x-page-header
        :breadcrumbs="[['label' => 'Services', 'url' => route('services')], ['label' => $service->name]]"
        eyebrow="Service"
        :intro="$service->intro"
    >
        {{ $service->heading }} <span class="accent">services</span>
    </x-page-header>

    <section class="bg-paper px-5 text-ink sm:px-10">
        <div data-clip-reveal class="relative aspect-[16/9] overflow-hidden bg-ink/5 sm:aspect-[21/9]">
            <img src="{{ asset($service->image) }}" alt="" width="1200" height="1500" class="h-full w-full object-cover">
        </div>
    </section>

    @if ($service->explainer)
        <section class="bg-paper px-5 pt-28 text-ink sm:px-10 sm:pt-40">
            <div class="grid grid-cols-1 gap-12 lg:grid-cols-12">
                <h2 data-split-lines class="text-h2 font-medium leading-[1.05] tracking-[-0.03em] lg:col-span-4">{{ $service->explainer['title'] }}</h2>
                <dl class="lg:col-span-7 lg:col-start-6">
                    @foreach ($service->explainer['items'] as $item)
                        <div data-reveal class="grid grid-cols-1 gap-2 border-t border-border py-6 sm:grid-cols-3">
                            <dt class="font-display text-h3 font-light">{{ $item['label'] }}</dt>
                            <dd class="text-fg-muted sm:col-span-2">{{ $item['text'] }}</dd>
                        </div>
                    @endforeach
                </dl>
            </div>
        </section>
    @endif

    {{-- What's included / Who this is for --}}
    <section class="bg-paper px-5 py-28 text-ink sm:px-10 sm:py-40">
        <div class="grid grid-cols-1 gap-20 lg:grid-cols-2 lg:gap-16">
            <div>
                <x-eyebrow number="01" class="text-fg-muted" data-reveal>Scope</x-eyebrow>
                <h2 data-split-lines class="mt-8 text-h2 font-medium tracking-[-0.03em]">What’s included</h2>
                <ul class="mt-10 border-t border-border">
                    @foreach ($service->included as $item)
                        <li data-reveal class="flex items-start gap-4 border-b border-border py-5">
                            <x-phosphor-plus-thin class="mt-1 size-4 shrink-0 text-fg-muted" aria-hidden="true" />
                            <span>{{ $item }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div>
                <x-eyebrow number="02" class="text-fg-muted" data-reveal>Audience</x-eyebrow>
                <h2 data-split-lines class="mt-8 text-h2 font-medium tracking-[-0.03em]">Who this is for</h2>
                <ul class="mt-10 border-t border-border">
                    @foreach ($service->audience as $item)
                        <li data-reveal class="border-b border-border py-5">
                            @if ($item['label'])
                                <span class="font-medium">{{ $item['label'] }}</span>
                                <span class="text-fg-muted">{{ $item['text'] }}</span>
                            @else
                                {{ $item['text'] }}
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </section>

    {{-- Process --}}
    <section class="bg-ink px-5 py-28 text-paper sm:px-10 sm:py-40">
        <div class="flex flex-col gap-8 md:flex-row md:items-end md:justify-between">
            <div>
                <x-eyebrow number="03" class="text-fg-muted-inverse" data-reveal>Process</x-eyebrow>
                <h2 data-split-lines class="mt-8 text-h1 font-medium leading-[1] tracking-[-0.035em]">Our <span class="accent">process</span></h2>
            </div>
            <a href="{{ route('process') }}" data-reveal class="link-line self-start text-sm md:self-auto">
                How every project runs <x-phosphor-arrow-up-right-thin class="size-4" aria-hidden="true" />
            </a>
        </div>
        <ol class="mt-16 grid grid-cols-1 gap-x-8 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ($service->process as $step)
                <li data-reveal class="border-t border-border-inverse py-8">
                    <span class="font-display text-[clamp(3rem,5vw,4.5rem)] font-light leading-none text-paper/80">0{{ $loop->iteration }}</span>
                    <h3 class="mt-8 text-h3 font-medium tracking-[-0.03em]">{{ $step['title'] }}</h3>
                    @if ($step['text'])
                        <p class="mt-3 text-sm leading-relaxed text-fg-muted-inverse">{{ $step['text'] }}</p>
                    @endif
                </li>
            @endforeach
        </ol>
    </section>

    {{-- Case studies (only when real or sample work in this discipline exists) --}}
    @if ($projects->isNotEmpty())
        <section class="bg-paper px-5 py-28 text-ink sm:px-10 sm:py-40">
            <div class="flex flex-col gap-8 md:flex-row md:items-end md:justify-between">
                <div>
                    <x-eyebrow number="04" class="text-fg-muted" data-reveal>Case studies</x-eyebrow>
                    <h2 data-split-lines class="mt-8 text-h1 font-medium leading-[1] tracking-[-0.035em]">Related <span class="accent">work</span></h2>
                </div>
                <a href="{{ route('work') }}" data-reveal class="link-line self-start text-sm md:self-auto">
                    See all work <x-phosphor-arrow-up-right-thin class="size-4" aria-hidden="true" />
                </a>
            </div>
            <div class="mt-16 grid grid-cols-1 gap-x-10 gap-y-16 md:grid-cols-2">
                @foreach ($projects as $project)
                    <x-work-card :project="$project" aspect="aspect-[4/3]" />
                @endforeach
            </div>
        </section>
    @endif

    {{-- FAQ --}}
    @if ($service->faq)
        <section @class(['bg-paper px-5 pb-28 text-ink sm:px-10 sm:pb-40', 'pt-28 sm:pt-40' => $projects->isEmpty()])>
            <div class="grid grid-cols-1 gap-12 border-t border-border pt-16 lg:grid-cols-12">
                <div class="lg:col-span-4">
                    <x-eyebrow class="text-fg-muted" data-reveal>FAQ</x-eyebrow>
                    <h2 data-split-lines class="mt-8 text-h2 font-medium tracking-[-0.03em]">Questions, <span class="accent">answered</span></h2>
                </div>
                <div class="lg:col-span-7 lg:col-start-6">
                    @foreach ($service->faq as $item)
                        <details data-reveal class="faq group border-b border-border first:border-t">
                            <summary class="flex cursor-pointer items-center justify-between gap-6 py-6 text-h3 font-medium tracking-[-0.02em]">
                                {{ $item['question'] }}
                                <x-phosphor-plus-thin class="faq-icon size-6 shrink-0" aria-hidden="true" />
                            </summary>
                            <p class="max-w-2xl pb-8 text-fg-muted">{{ $item['answer'] }}</p>
                        </details>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Related service + other services --}}
    <section class="bg-paper px-5 pb-28 text-ink sm:px-10 sm:pb-40">
        <div class="grid grid-cols-1 gap-16 border-t border-border pt-16 lg:grid-cols-12">
            @if ($related)
                <div class="lg:col-span-5">
                    <x-eyebrow class="text-fg-muted" data-reveal>Pairs well with</x-eyebrow>
                    <h2 data-split-lines class="mt-8 text-h2 font-medium tracking-[-0.03em]">{{ $service->related['heading'] }}</h2>
                    <p data-reveal class="mt-4 max-w-md text-fg-muted">{{ $service->related['text'] }}</p>
                    <a data-reveal href="{{ route('services.show', $related->slug) }}" class="link-line mt-6 inline-flex text-sm">
                        {{ Str::of($related->name)->lower()->finish(' design')->ucfirst() }} <x-phosphor-arrow-up-right-thin class="size-4" aria-hidden="true" />
                    </a>
                </div>
            @endif
            <nav class="lg:col-span-6 lg:col-start-7" aria-label="Other services">
                <x-eyebrow class="text-fg-muted" data-reveal>Other services</x-eyebrow>
                <ul class="mt-8 border-t border-border">
                    @foreach ($otherServices as $other)
                        <li class="border-b border-border">
                            <a href="{{ route('services.show', $other->slug) }}" class="group flex items-center justify-between py-4">
                                <span class="transition-transform duration-500 ease-expo group-hover:translate-x-2">{{ $other->name }}</span>
                                <x-phosphor-arrow-right-thin class="size-4" aria-hidden="true" />
                            </a>
                        </li>
                    @endforeach
                </ul>
            </nav>
        </div>
    </section>

    <x-cta-band :label="$service->cta" :href="route('contact', ['service' => $service->name])">
        Ready to <span class="accent">start?</span>
    </x-cta-band>
@endsection
