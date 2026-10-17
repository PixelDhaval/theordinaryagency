@extends('layouts.app')

@php
    $isFiltered = $search !== '' || $activeDiscipline;
    $activeSlug = $activeDiscipline ? Str::slug($activeDiscipline) : null;

    $pageSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'CollectionPage',
        'name' => 'Our work',
        'url' => route('work'),
        'mainEntity' => [
            '@type' => 'ItemList',
            'itemListElement' => $projects->getCollection()->values()->map(fn ($project, $index) => [
                '@type' => 'ListItem',
                'position' => $projects->firstItem() + $index,
                'url' => route('work.show', $project->slug),
                'name' => $project->title,
            ])->all(),
        ],
    ];
@endphp

@section('title', 'Our Work | Branding, Packaging & UI/UX Case Studies'.($projects->currentPage() > 1 ? ' - Page '.$projects->currentPage() : ''))
@section('description', 'Branding, packaging, pitch deck, social, brochure, and UI/UX projects - see the work and the thinking behind it, then start your own.')
@section('canonical', request()->fullUrlWithoutQuery(['q']))
@if ($search !== '')
    @section('robots', 'noindex, follow')
@endif

@push('schema')
    <x-schema :data="$pageSchema" />
@endpush

@section('content')
    <x-page-header
        :breadcrumbs="[['label' => 'Work']]"
        eyebrow="Work"
        intro="A selection of projects across branding, packaging, pitch decks, social media, brochures, and UI/UX."
    >
        Our <span class="accent">work</span>
    </x-page-header>

    <section id="results" class="bg-paper px-5 pb-28 text-ink sm:px-10 sm:pb-40">
        @if ($hasAnyProjects)
            <x-listing-filters
                :action="route('work')"
                :search="$search"
                :options="$disciplines"
                param="discipline"
                :active="$activeSlug"
                placeholder="Search projects or clients"
                label="Filter projects by discipline"
                data-reveal
            />

            @if ($isFiltered)
                <p class="mt-8 text-sm text-fg-muted" aria-live="polite">
                    {{ trans_choice(':count project|:count projects', $projects->total(), ['count' => $projects->total()]) }}
                    @if ($search !== '') for “{{ $search }}” @endif
                    @if ($activeDiscipline) in {{ $activeDiscipline }} @endif
                    · <a href="{{ route('work') }}" class="link-line text-ink">Clear</a>
                </p>
            @endif

            @if ($projects->isNotEmpty())
                <div class="mt-16 grid grid-cols-1 gap-x-10 gap-y-20 md:grid-cols-2">
                    @foreach ($projects as $project)
                        <div @class(['md:mt-32' => $loop->even])>
                            <x-work-card :project="$project" :aspect="$loop->even ? 'aspect-[4/3]' : 'aspect-[4/5]'" />
                        </div>
                    @endforeach
                </div>

                <div class="mt-24">
                    {{ $projects->fragment('results')->links('pagination.agency') }}
                </div>
            @else
                <div class="py-24">
                    <p class="max-w-xl text-h3 leading-[1.45] text-fg-muted">No projects match that search.</p>
                    <div class="mt-8">
                        <x-button :href="route('work')" variant="outline-dark">See all work</x-button>
                    </div>
                </div>
            @endif
        @else
            <div class="border-t border-border pt-16">
                <p data-reveal class="max-w-xl text-h3 leading-[1.45] text-fg-muted">
                    Case studies are on their way. In the meantime, see what each service includes.
                </p>
                <div data-reveal class="mt-10">
                    <x-button :href="route('services')" variant="outline-dark">Explore our services</x-button>
                </div>
            </div>
        @endif
    </section>

    <x-cta-band label="Start your project">
        Have a project like this <span class="accent">in mind?</span>
    </x-cta-band>
@endsection
