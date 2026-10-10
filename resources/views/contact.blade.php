@extends('layouts.app')

@section('title', 'Contact Us | Book a Call | The Ordinary Agency')
@section('description', 'Ready to start your branding, packaging, or design project? Book a quick call with our studio or reach out directly.')

@php
    $email = config('agency.email');
    $bookingUrl = config('agency.booking_url');
    $social = config('agency.social');
    $services = \App\Support\ServiceCatalog::all()->pluck('name');

    $fieldClasses = 'mt-3 w-full border-0 border-b bg-transparent px-0 py-3 text-lg outline-none transition-colors placeholder:text-fg-muted/60 focus:border-ink focus:ring-0';

    $pageSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'ContactPage',
        'name' => 'Contact The Ordinary Agency',
        'url' => route('contact'),
        'about' => ['@id' => url('/').'#organization'],
    ];
@endphp

@push('schema')
    <x-schema :data="$pageSchema" />
@endpush

@section('content')
    <x-page-header
        :breadcrumbs="[['label' => 'Contact']]"
        eyebrow="Contact"
        intro="Book a quick call to talk through your project — branding, packaging, pitch decks, social, brochures, or UI/UX."
    >
        Let’s <span class="accent">talk</span>
    </x-page-header>

    <section id="enquiry" class="bg-paper px-5 pb-28 text-ink sm:px-10 sm:pb-40">
        <div class="grid grid-cols-1 gap-16 border-t border-border pt-16 lg:grid-cols-12">

            <div class="lg:col-span-7">
                <x-eyebrow class="text-fg-muted" data-reveal>Tell us about your project</x-eyebrow>

                @if (session('contact_status'))
                    <div role="status" class="mt-8 flex items-start gap-4 border border-ink p-6">
                        <x-phosphor-check-circle-thin class="size-7 shrink-0" aria-hidden="true" />
                        <p class="text-h3 leading-snug tracking-[-0.01em]">{{ session('contact_status') }}</p>
                    </div>
                @endif

                <form method="POST" action="{{ route('contact.store') }}" class="mt-10 grid grid-cols-1 gap-x-8 gap-y-10 sm:grid-cols-2" novalidate>
                    @csrf

                    {{-- Honeypot: visually hidden, ignored by people, filled by bots. --}}
                    <div class="absolute -left-[9999px] h-px w-px overflow-hidden" aria-hidden="true">
                        <label for="website">Leave this field empty</label>
                        <input id="website" type="text" name="website" tabindex="-1" autocomplete="off">
                    </div>

                    <div>
                        <label for="name" class="text-eyebrow uppercase tracking-[0.2em] text-fg-muted">Name *</label>
                        <input id="name" name="name" type="text" required autocomplete="name" maxlength="120" value="{{ old('name') }}"
                               @error('name') aria-invalid="true" aria-describedby="name-error" @enderror
                               @class([$fieldClasses, 'border-red-700' => $errors->has('name'), 'border-border' => ! $errors->has('name')])>
                        @error('name') <p id="name-error" class="mt-2 text-sm text-red-700">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="email" class="text-eyebrow uppercase tracking-[0.2em] text-fg-muted">Email *</label>
                        <input id="email" name="email" type="email" required autocomplete="email" maxlength="255" value="{{ old('email') }}"
                               @error('email') aria-invalid="true" aria-describedby="email-error" @enderror
                               @class([$fieldClasses, 'border-red-700' => $errors->has('email'), 'border-border' => ! $errors->has('email')])>
                        @error('email') <p id="email-error" class="mt-2 text-sm text-red-700">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="company" class="text-eyebrow uppercase tracking-[0.2em] text-fg-muted">Company</label>
                        <input id="company" name="company" type="text" autocomplete="organization" maxlength="160" value="{{ old('company') }}"
                               @class([$fieldClasses, 'border-border'])>
                    </div>

                    <div>
                        <label for="service" class="text-eyebrow uppercase tracking-[0.2em] text-fg-muted">Interested in</label>
                        <select id="service" name="service" @class([$fieldClasses, 'border-border'])>
                            <option value="">Not sure yet</option>
                            @foreach ($services as $service)
                                <option value="{{ $service }}" @selected(old('service', request('service')) === $service)>{{ $service }}</option>
                            @endforeach
                        </select>
                        @error('service') <p class="mt-2 text-sm text-red-700">{{ $message }}</p> @enderror
                    </div>

                    <div class="sm:col-span-2">
                        <label for="message" class="text-eyebrow uppercase tracking-[0.2em] text-fg-muted">Message *</label>
                        <textarea id="message" name="message" rows="5" required minlength="10" maxlength="5000"
                                  placeholder="What are you working on, and what do you need?"
                                  @error('message') aria-invalid="true" aria-describedby="message-error" @enderror
                                  @class([$fieldClasses, 'resize-y', 'border-red-700' => $errors->has('message'), 'border-border' => ! $errors->has('message')])>{{ old('message') }}</textarea>
                        @error('message') <p id="message-error" class="mt-2 text-sm text-red-700">{{ $message }}</p> @enderror
                        @error('website') <p class="mt-2 text-sm text-red-700">{{ $message }}</p> @enderror
                    </div>

                    <div class="sm:col-span-2">
                        <button type="submit" data-magnetic class="group inline-flex items-center gap-3 rounded-full bg-ink py-4 pl-7 pr-5 text-sm font-medium text-paper">
                            <span class="roll"><span>Send enquiry</span><span aria-hidden="true">Send enquiry</span></span>
                            <span class="flex size-7 items-center justify-center rounded-full border border-paper/30">
                                <x-phosphor-arrow-up-right-thin class="size-4 transition-transform duration-500 ease-expo group-hover:rotate-45" aria-hidden="true" />
                            </span>
                        </button>
                    </div>
                </form>
            </div>

            <aside class="lg:col-span-4 lg:col-start-9">
                <x-eyebrow class="text-fg-muted" data-reveal>What to expect</x-eyebrow>
                <p data-reveal class="mt-8 text-h3 leading-[1.45] tracking-[-0.01em]">
                    A short, no-pressure call to understand what you’re working on and whether we’re the right fit.
                </p>

                @if ($bookingUrl || $email || $social)
                    <div class="mt-16 space-y-8">
                        <x-eyebrow class="text-fg-muted" data-reveal>Prefer to reach out directly?</x-eyebrow>
                        @if ($bookingUrl)
                            <a data-reveal href="{{ $bookingUrl }}" target="_blank" rel="noopener" class="group flex items-center justify-between gap-4 border-b border-ink pb-4">
                                <span class="text-h3 font-medium">Book a call</span>
                                <x-phosphor-calendar-blank-thin class="size-6 shrink-0" aria-hidden="true" />
                            </a>
                        @endif
                        @if ($email)
                            <a data-reveal href="mailto:{{ $email }}" class="group flex items-center justify-between gap-4 border-b border-ink pb-4">
                                <span class="break-all text-h3 font-medium">{{ $email }}</span>
                                <x-phosphor-envelope-simple-thin class="size-6 shrink-0" aria-hidden="true" />
                            </a>
                        @endif
                        @if ($social)
                            <ul data-reveal class="flex flex-wrap gap-x-6 gap-y-2 text-sm">
                                @foreach ($social as $network => $url)
                                    <li><a href="{{ $url }}" target="_blank" rel="noopener me" class="link-line">{{ $network }}</a></li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                @endif

                <p data-reveal class="mt-16 text-fg-muted">
                    Not sure what you need yet?
                    <a href="{{ route('services') }}" class="link-line text-ink">See our services</a>
                    or <a href="{{ route('work') }}" class="link-line text-ink">see our work</a> first.
                </p>
            </aside>
        </div>
    </section>
@endsection
