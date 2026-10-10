@extends('layouts.app')

@php
    $canonical = $post->seoCanonical(route('journal.show', $post->slug));

    $pageSchema = array_filter([
        '@context' => 'https://schema.org',
        '@type' => 'BlogPosting',
        'headline' => $post->title,
        'description' => $post->seoDescription($post->excerpt),
        'image' => $post->seoImageUrl(),
        'url' => $canonical,
        'mainEntityOfPage' => $canonical,
        'datePublished' => $post->published_at->toIso8601String(),
        'dateModified' => ($post->updated_at ?? $post->published_at)->toIso8601String(),
        'articleSection' => $post->category?->name,
        'timeRequired' => 'PT'.$post->reading_time.'M',
        'author' => ['@id' => url('/').'#organization'],
        'publisher' => ['@id' => url('/').'#organization'],
    ]);
@endphp

@section('title', $post->seoTitle($post->title.' | The Ordinary Agency Journal'))
@section('description', $post->seoDescription($post->excerpt) ?? '')
@section('canonical', $canonical)
@section('og_type', 'article')
@if ($post->seoImageUrl())
    @section('og_image', $post->seoImageUrl())
@endif
@if ($post->seoRobots())
    @section('robots', $post->seoRobots())
@endif

@push('head')
    <meta property="article:published_time" content="{{ $post->published_at->toIso8601String() }}">
    <meta property="article:modified_time" content="{{ $post->updated_at?->toIso8601String() }}">
    @if ($post->category)
        <meta property="article:section" content="{{ $post->category->name }}">
    @endif
@endpush

@push('schema')
    <x-schema :data="$pageSchema" />
@endpush

@section('content')
    <article>
        <header class="bg-paper px-5 pb-16 pt-32 text-ink sm:px-10 sm:pb-20 sm:pt-40">
            <x-breadcrumbs :items="[['label' => 'Journal', 'url' => route('journal')], ['label' => $post->title]]" data-reveal />

            <p data-reveal class="mt-16 flex flex-wrap items-center gap-3 text-eyebrow uppercase tracking-[0.2em] text-fg-muted">
                @if ($post->category)
                    <a href="{{ route('journal', ['category' => $post->category->slug]) }}" class="link-line">{{ $post->category->name }}</a>
                    <span class="h-px w-4 bg-current opacity-40"></span>
                @endif
                <time datetime="{{ $post->published_at->toDateString() }}">{{ $post->published_at->format('F j, Y') }}</time>
                <span class="h-px w-4 bg-current opacity-40"></span>
                <span>{{ $post->reading_time }} min read</span>
            </p>

            <h1 data-split-lines class="mt-8 max-w-5xl text-h1 font-medium leading-[1] tracking-[-0.035em]">{{ $post->title }}</h1>
            @if ($post->excerpt)
                <p data-reveal class="mt-8 max-w-2xl text-h3 leading-[1.45] text-fg-muted">{{ $post->excerpt }}</p>
            @endif
        </header>

        @if ($post->cover_url)
            <div class="bg-paper px-5 text-ink sm:px-10">
                <div data-clip-reveal class="relative aspect-[16/9] overflow-hidden bg-ink/5">
                    <img src="{{ $post->cover_url }}" alt="" class="h-full w-full object-cover">
                </div>
            </div>
        @endif

        <div class="bg-paper px-5 py-24 text-ink sm:px-10 sm:py-32">
            <div class="grid grid-cols-1 lg:grid-cols-12">
                <div data-reveal class="prose-agency lg:col-span-7 lg:col-start-4">
                    {!! Str::markdown((string) $post->body, ['html_input' => 'escape', 'allow_unsafe_links' => false]) !!}
                </div>
            </div>
        </div>
    </article>

    @if ($relatedPosts->isNotEmpty())
        <section class="bg-paper px-5 pb-28 text-ink sm:px-10 sm:pb-40" aria-labelledby="related-heading">
            <div class="flex flex-col gap-8 border-t border-border pt-16 md:flex-row md:items-end md:justify-between">
                <h2 id="related-heading" data-split-lines class="text-h2 font-medium tracking-[-0.03em]">Keep <span class="accent">reading</span></h2>
                <a href="{{ route('journal') }}" data-reveal class="link-line self-start text-sm md:self-auto">
                    All articles <x-phosphor-arrow-up-right-thin class="size-4" aria-hidden="true" />
                </a>
            </div>
            <div class="mt-12 grid grid-cols-1 gap-x-8 gap-y-16 md:grid-cols-2">
                @foreach ($relatedPosts as $related)
                    <x-journal-card :post="$related" />
                @endforeach
            </div>
        </section>
    @endif

    <x-cta-band>
        Have a project <span class="accent">in mind?</span>
    </x-cta-band>
@endsection
