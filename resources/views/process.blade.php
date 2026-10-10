@extends('layouts.app')

@section('title', 'Our Process | How We Work | The Ordinary Agency')
@section('description', 'From discovery to delivery — see exactly how a project with us works, step by step, before you book a call.')

@php
    $steps = [
        ['title' => 'Discovery', 'text' => 'We start by understanding your goals, audience, and the scope of what you need. For branding, that means your positioning and story. For UI/UX, it means your users and their flows. For packaging, it means your product and where it’ll be seen.'],
        ['title' => 'Concept', 'text' => 'We explore a small number of strong directions rather than dozens of weak ones — so your feedback at this stage is focused and useful, not overwhelming.'],
        ['title' => 'Design & Refinement', 'text' => 'We build out the chosen direction in full and refine it based on your feedback. This is where most of the back-and-forth happens, and where the work gets sharp.'],
        ['title' => 'Delivery & Handoff', 'text' => 'You receive final files, guidelines, or documentation — whatever’s needed to put the work to use. Packaging projects get production-ready files; UI/UX projects get developer handoff; branding projects get a guidelines document.'],
    ];

    $needs = [
        'A clear sense of your goals and audience',
        'Any existing brand assets, if you have them',
        'Timely feedback during review rounds — this is the biggest factor in keeping a project on schedule',
    ];

    $pageSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'WebPage',
        'name' => 'How we work',
        'url' => route('process'),
        'description' => 'The four-step process behind every project: discovery, concept, design and refinement, delivery and handoff.',
        'isPartOf' => ['@id' => url('/').'#website'],
    ];
@endphp

@push('schema')
    <x-schema :data="$pageSchema" />
@endpush

@section('content')
    <x-page-header
        :breadcrumbs="[['label' => 'Process']]"
        eyebrow="Process"
        intro="The same four-step process runs under every project — branding, packaging, pitch decks, social, brochures, UI/UX. What changes is what happens inside each step."
    >
        How we <span class="accent">work</span>
    </x-page-header>

    <section class="bg-paper px-5 pb-28 text-ink sm:px-10 sm:pb-40">
        <ol>
            @foreach ($steps as $step)
                <li class="grid grid-cols-1 gap-6 border-t border-border py-12 md:grid-cols-12 md:py-16">
                    <span data-reveal class="font-display text-[clamp(3.5rem,8vw,7rem)] font-light leading-[0.85] md:col-span-3">0{{ $loop->iteration }}</span>
                    <h2 data-split-lines class="text-h2 font-medium tracking-[-0.03em] md:col-span-4">{{ $step['title'] }}</h2>
                    <p data-reveal class="leading-relaxed text-fg-muted md:col-span-5">{{ $step['text'] }}</p>
                </li>
            @endforeach
        </ol>
    </section>

    <section class="bg-ink px-5 py-28 text-paper sm:px-10 sm:py-40">
        <div class="grid grid-cols-1 gap-16 lg:grid-cols-12">
            <div class="lg:col-span-5">
                <x-eyebrow class="text-fg-muted-inverse" data-reveal>From you</x-eyebrow>
                <h2 data-split-lines class="mt-8 text-h1 font-medium leading-[1] tracking-[-0.035em]">What we need <span class="accent">from you</span></h2>
            </div>
            <ul class="lg:col-span-6 lg:col-start-7">
                @foreach ($needs as $need)
                    <li data-reveal class="flex items-start gap-4 border-b border-border-inverse py-6 first:border-t">
                        <x-phosphor-plus-thin class="mt-1 size-4 shrink-0 text-fg-muted-inverse" aria-hidden="true" />
                        <span>{{ $need }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
    </section>

    <section class="bg-paper px-5 py-28 text-ink sm:px-10 sm:py-40">
        <div class="flex flex-col gap-10 md:flex-row md:items-end md:justify-between">
            <div>
                <x-eyebrow class="text-fg-muted" data-reveal>Timelines</x-eyebrow>
                <h2 data-split-lines class="mt-8 text-h1 font-medium leading-[1] tracking-[-0.035em]">How long <span class="accent">does it take?</span></h2>
                <p data-reveal class="mt-6 max-w-lg text-fg-muted">
                    Timelines vary by service — see each <a href="{{ route('services') }}" class="link-line text-ink">service page</a> for specifics, or ask directly on a call.
                </p>
            </div>
            <div data-reveal>
                <x-button :href="route('work')" variant="outline-dark">See the process in action</x-button>
            </div>
        </div>
    </section>

    <x-cta-band>
        Ready when <span class="accent">you are.</span>
    </x-cta-band>
@endsection
