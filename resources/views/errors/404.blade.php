@extends('layouts.app')

@section('title', 'Page not found | The Ordinary Agency')
@section('description', 'The page you were looking for doesn’t exist or has moved.')
@section('robots', 'noindex, follow')

@section('content')
    <section class="flex min-h-svh flex-col justify-between bg-paper px-5 pb-12 pt-32 text-ink sm:px-10 sm:pt-40">
        <div>
            <x-eyebrow class="text-fg-muted" data-reveal>Error 404</x-eyebrow>
            <p aria-hidden="true" data-reveal class="mt-6 font-display text-[clamp(7rem,28vw,24rem)] font-light leading-[0.8] tracking-[-0.04em]">404</p>
        </div>

        <div class="mt-16 grid grid-cols-1 gap-10 border-t border-border pt-10 lg:grid-cols-12">
            <div class="lg:col-span-6">
                <h1 data-split-lines class="text-h1 font-medium leading-[1] tracking-[-0.035em]">
                    Nothing to see <span class="accent">here.</span>
                </h1>
                <p data-reveal class="mt-6 max-w-md text-fg-muted">
                    The page you were looking for doesn’t exist or has moved. Try one of these instead.
                </p>
            </div>
            <nav data-reveal class="flex flex-wrap items-end gap-3 lg:col-span-6 lg:justify-end" aria-label="Helpful links">
                <x-button :href="route('home')">Back to home</x-button>
                <x-button :href="route('services')" variant="outline-dark">Services</x-button>
                <x-button :href="route('work')" variant="outline-dark">Work</x-button>
            </nav>
        </div>
    </section>
@endsection
