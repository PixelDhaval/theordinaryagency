@extends('layouts.app')

@php
    $isFiltered = $search !== '' || $activeCategory;

    $pageSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'Blog',
        'name' => 'The Ordinary Agency Journal',
        'url' => route('journal'),
        'publisher' => ['@id' => url('/').'#organization'],
        'blogPost' => $posts->getCollection()->map(fn ($post) => [
            '@type' => 'BlogPosting',
            'headline' => $post->title,
            'url' => route('journal.show', $post->slug),
            'datePublished' => $post->published_at->toDateString(),
        ])->all(),
    ];
@endphp

@section('title', ($activeCategory ? $activeCategory->name.' — ' : '').'Journal | Design Insights from The Ordinary Agency'.($posts->currentPage() > 1 ? ' — Page '.$posts->currentPage() : ''))
@section('description', 'Notes on branding, packaging, and design process from our studio — real thinking from real projects, not generic tips.')
@section('canonical', request()->fullUrlWithoutQuery(['q']))
@if ($search !== '')
    @section('robots', 'noindex, follow')
@endif

@push('schema')
    <x-schema :data="$pageSchema" />
@endpush

@section('content')
    <x-page-header
        :breadcrumbs="$activeCategory ? [['label' => 'Journal', 'url' => route('journal')], ['label' => $activeCategory->name]] : [['label' => 'Journal']]"
        eyebrow="Journal"
        intro="Notes on branding, packaging, and design process — written from real projects, not generic listicles."
    >
        Notes from <span class="accent">the studio</span>
    </x-page-header>

    <section id="results" class="bg-paper px-5 pb-28 text-ink sm:px-10 sm:pb-40">
        @if ($hasAnyPosts)
            <x-listing-filters
                :action="route('journal')"
                :search="$search"
                :options="$categories->pluck('name', 'slug')->all()"
                param="category"
                :active="$activeCategory?->slug"
                placeholder="Search articles"
                label="Filter articles by category"
                data-reveal
            />

            @if ($isFiltered)
                <p class="mt-8 text-sm text-fg-muted" aria-live="polite">
                    {{ trans_choice(':count article|:count articles', $posts->total(), ['count' => $posts->total()]) }}
                    @if ($search !== '') for “{{ $search }}” @endif
                    @if ($activeCategory) in {{ $activeCategory->name }} @endif
                    · <a href="{{ route('journal') }}" class="link-line text-ink">Clear</a>
                </p>
            @endif

            @if ($posts->isNotEmpty())
                <div class="mt-16 grid grid-cols-1 gap-x-8 gap-y-16 md:grid-cols-2 lg:grid-cols-3">
                    @foreach ($posts as $post)
                        <x-journal-card :post="$post" />
                    @endforeach
                </div>

                <div class="mt-24">
                    {{ $posts->fragment('results')->links('pagination.agency') }}
                </div>
            @else
                <div class="py-24">
                    <p class="max-w-xl text-h3 leading-[1.45] text-fg-muted">No articles match that search.</p>
                    <div class="mt-8">
                        <x-button :href="route('journal')" variant="outline-dark">See all articles</x-button>
                    </div>
                </div>
            @endif
        @else
            <div class="border-t border-border pt-16">
                <p data-reveal class="max-w-xl text-h3 leading-[1.45] text-fg-muted">
                    The first articles are being written. Until then, our process page covers how we think.
                </p>
                <div data-reveal class="mt-10">
                    <x-button :href="route('process')" variant="outline-dark">Read about our process</x-button>
                </div>
            </div>
        @endif
    </section>

    <x-cta-band label="Get in touch">
        Have a question <span class="accent">for the studio?</span>
    </x-cta-band>
@endsection
