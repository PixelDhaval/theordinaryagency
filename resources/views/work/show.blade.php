@extends('layouts.app')

@php
    $canonical = $project->seoCanonical(route('work.show', $project->slug));

    $pageSchema = array_filter([
        '@context' => 'https://schema.org',
        '@type' => 'CreativeWork',
        'name' => $project->title,
        'headline' => $project->title,
        'description' => $project->seoDescription($project->summary),
        'url' => $canonical,
        'image' => $project->seoImageUrl(),
        'dateCreated' => $project->year ? (string) $project->year : null,
        'dateModified' => $project->updated_at?->toIso8601String(),
        'genre' => $project->discipline,
        'creator' => ['@id' => url('/').'#organization'],
        'sourceOrganization' => ['@type' => 'Organization', 'name' => $project->client],
    ]);
@endphp

@section('title', $project->seoTitle($project->title.' - '.$project->discipline.' for '.$project->client.' | The Ordinary Agency'))
@section('description', $project->seoDescription($project->summary))
@section('canonical', $canonical)
@section('og_type', 'article')
@if ($project->seoImageUrl())
    @section('og_image', $project->seoImageUrl())
@endif
@if ($project->seoRobots())
    @section('robots', $project->seoRobots())
@endif

@push('schema')
    <x-schema :data="$pageSchema" />
@endpush

@section('content')
    <x-page-header
        :breadcrumbs="[['label' => 'Work', 'url' => route('work')], ['label' => $project->title]]"
        :eyebrow="$project->discipline"
        :intro="$project->summary"
    >
        {{ $project->title }}
    </x-page-header>

    <section class="bg-paper px-5 text-ink sm:px-10">
        <dl data-reveal class="grid grid-cols-2 gap-8 border-t border-border py-8 md:grid-cols-4">
            <div>
                <dt class="text-eyebrow uppercase tracking-[0.2em] text-fg-muted">Client</dt>
                <dd class="mt-2">{{ $project->client }}</dd>
            </div>
            <div>
                <dt class="text-eyebrow uppercase tracking-[0.2em] text-fg-muted">Services</dt>
                <dd class="mt-2">{{ implode(', ', $project->services ?: [$project->discipline]) }}</dd>
            </div>
            @if ($project->year)
                <div>
                    <dt class="text-eyebrow uppercase tracking-[0.2em] text-fg-muted">Year</dt>
                    <dd class="mt-2">{{ $project->year }}</dd>
                </div>
            @endif
        </dl>

        @if ($project->cover_url)
            <div data-clip-reveal class="relative aspect-[16/10] overflow-hidden bg-ink/5">
                <img src="{{ $project->cover_url }}" alt="{{ $project->title }} - {{ $project->discipline }} for {{ $project->client }}" class="h-full w-full object-cover">
            </div>
        @endif
    </section>

    @if ($project->content)
        <section class="bg-paper py-28 text-ink sm:py-40">
            <x-project-content :blocks="$project->content" />
        </section>
    @endif

    @if ($project->gallery_urls)
        <section class="bg-paper px-5 pb-28 text-ink sm:px-10 sm:pb-40">
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                @foreach ($project->gallery_urls as $image)
                    <div data-clip-reveal @class(['relative overflow-hidden bg-ink/5', 'aspect-[4/5]' => $loop->odd, 'aspect-[4/5] md:mt-24' => $loop->even])>
                        <img src="{{ $image }}" alt="" loading="lazy" class="h-full w-full object-cover">
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    @if ($nextProject)
        <section class="bg-paper px-5 pb-28 text-ink sm:px-10 sm:pb-40">
            <a href="{{ route('work.show', $nextProject->slug) }}" data-cursor="Next" class="group block border-t border-border pt-10">
                <x-eyebrow class="text-fg-muted">Next project</x-eyebrow>
                <span class="mt-6 flex items-end justify-between gap-6">
                    <span class="text-display font-medium leading-[0.95] tracking-[-0.045em] transition-transform duration-700 ease-expo group-hover:translate-x-4">{{ $nextProject->title }}</span>
                    <x-phosphor-arrow-right-thin class="size-[clamp(2rem,5vw,4.5rem)] shrink-0 transition-transform duration-700 ease-expo group-hover:translate-x-2" aria-hidden="true" />
                </span>
                <span class="mt-4 block text-fg-muted">{{ $nextProject->client }} - {{ $nextProject->discipline }}</span>
            </a>
        </section>
    @endif

    <x-cta-band label="Start your project">
        Have a project like this <span class="accent">in mind?</span>
    </x-cta-band>
@endsection
