@extends('layouts.app')

@section('title', 'Logo Portfolio | The Ordinary Agency')
@section('description', 'Selected logo and identity work from The Ordinary Agency - lettermarks, emblems and wordmarks, shown with their construction, colour and in use.')
@section('robots', 'noindex, nofollow')

@php
    // Shared privately with leads: ?for=Name personalises the cover without a route per lead.
    $preparedFor = request()->string('for')->trim()->limit(60)->value();

    $types = [
        'lettermark' => 'Lettermarks',
        'combination' => 'Combination marks',
        'pictorial' => 'Pictorial marks',
        'wordmark' => 'Wordmarks',
        'emblem' => 'Emblems',
    ];

    $logos = [
        ['slug' => 'vansh-maritime', 'name' => 'Vansh Maritime', 'sector' => 'Maritime', 'type' => 'lettermark', 'width' => 850, 'height' => 498],
        ['slug' => 'prana-studio', 'name' => 'Prana Studio', 'sector' => 'Yoga & wellness', 'type' => 'pictorial', 'width' => 856, 'height' => 456],
        ['slug' => 'globelink', 'name' => 'Globelink', 'sector' => 'Shipping & logistics', 'type' => 'combination', 'width' => 928, 'height' => 390],
        ['slug' => 'lush-bloom', 'name' => 'Lush Bloom', 'sector' => 'Cosmetics', 'type' => 'wordmark', 'width' => 1191, 'height' => 478],
        ['slug' => 'ahir-fuels', 'name' => 'Ahir Fuels', 'sector' => 'Fuel & energy', 'type' => 'combination', 'width' => 1054, 'height' => 275],
        ['slug' => 'leo-club-gandhidham', 'name' => 'Leo Club of Gandhidham', 'sector' => 'Non-profit', 'type' => 'emblem', 'width' => 809, 'height' => 876],
        ['slug' => 'lavish-living', 'name' => 'Lavish Living', 'sector' => 'Home furnishings', 'type' => 'combination', 'width' => 962, 'height' => 378],
        ['slug' => 'reclosia-thrift', 'name' => 'Reclosia Thrift', 'sector' => 'Thrift fashion', 'type' => 'emblem', 'width' => 907, 'height' => 743],
        ['slug' => 'accviz', 'name' => 'AccViz', 'sector' => null, 'type' => 'combination', 'width' => 858, 'height' => 195],
        ['slug' => 'sark-shipping', 'name' => 'SARK Shipping Services', 'sector' => 'Shipping & logistics', 'type' => 'pictorial', 'width' => 670, 'height' => 610],
        ['slug' => 'palak-bakers', 'name' => 'Palak Bakers', 'sector' => 'Bakery', 'type' => 'wordmark', 'width' => 709, 'height' => 463],
        ['slug' => 'ads-vizion', 'name' => 'ADS Vizion', 'sector' => null, 'type' => 'lettermark', 'width' => 1400, 'height' => 734],
        ['slug' => 'dr-pooja-belani', 'name' => 'Dr Pooja Belani', 'sector' => 'Healthcare', 'type' => 'pictorial', 'width' => 849, 'height' => 820],
        ['slug' => 'svadistha', 'name' => 'Svadistha', 'sector' => null, 'type' => 'combination', 'width' => 1400, 'height' => 452, 'tone' => 'dark'],
        ['slug' => 'kashyap-shipping', 'name' => 'Kashyap Shipping', 'sector' => 'Shipping', 'type' => 'pictorial', 'width' => 1001, 'height' => 347],
        ['slug' => 'vansh-exim', 'name' => 'Vansh Exim Shipping', 'sector' => 'Export & import', 'type' => 'lettermark', 'width' => 698, 'height' => 294],
        ['slug' => 'sr-tradeline', 'name' => 'SR Tradeline', 'sector' => 'Trade', 'type' => 'pictorial', 'width' => 899, 'height' => 552],
    ];

    // Measured from the trimmed artwork - see logo-portfolio/constructions/*.
    $studies = [
        [
            'slug' => 'lavish-living', 'name' => 'Lavish Living', 'meta' => 'Home furnishings · Combination mark',
            'width' => 962, 'height' => 378, 'mark' => ['lavish-living-mark', 297, 297],
            'notes' => ['Square mark = two-line stack', 'Fold cut at 45°', 'Tagline spans the full lockup'],
            'palette' => ['#432818' => 'Walnut', '#FFFFFF' => 'White'],
        ],
        [
            'slug' => 'prana-studio', 'name' => 'Prana Studio', 'meta' => 'Yoga & wellness · Pictorial mark',
            'width' => 856, 'height' => 456, 'mark' => ['prana-studio-mark', 393, 275],
            'notes' => ['Sun Ø = x-height of the wordmark', 'Two equal loops, one line', 'Arms orbit the sun'],
            'palette' => ['#2E2E29' => 'Charcoal', '#C97C5F' => 'Terracotta', '#F6F3EC' => 'Linen'],
        ],
        [
            'slug' => 'vansh-maritime', 'name' => 'Vansh Maritime', 'meta' => 'Maritime · Lettermark',
            'width' => 850, 'height' => 498, 'mark' => ['vansh-maritime-mark', 446, 354],
            'notes' => ['M drawn inside a square', 'Tick runs parallel to the M at 37°', 'Both vertices share one axis'],
            'palette' => ['#2B3694' => 'Harbour blue', '#F48222' => 'Signal orange'],
        ],
        [
            'slug' => 'globelink', 'name' => 'Globelink', 'meta' => 'Shipping & logistics · Combination mark',
            'width' => 928, 'height' => 390, 'mark' => ['globelink-mark', 340, 390],
            'notes' => ['G and arrow share one circle', 'Cut at 45° through the centre', 'Three lines, one left edge'],
            'palette' => ['#034FE5' => 'Deep blue', '#569EFA' => 'Sky', '#0E1142' => 'Navy'],
        ],
    ];

    $marqueeMarks = [
        ['vansh-maritime-mark', 446, 354], ['prana-studio-mark', 393, 275], ['globelink-mark', 340, 390],
        ['lavish-living-mark', 297, 297], ['ahir-fuels-mark', 873, 599], ['accviz-mark', 225, 195],
        ['sark-shipping', 670, 610], ['svadistha-leaf', 535, 547], ['vansh-exim', 698, 294],
        ['sr-tradeline', 899, 552], ['palak-bakers', 709, 463], ['ads-vizion', 1400, 734],
    ];

    $appIcons = [
        ['file' => 'globelink-mark', 'label' => 'Globelink', 'bg' => '#FFFFFF', 'invert' => false],
        ['file' => 'vansh-maritime-mark', 'label' => 'Vansh', 'bg' => '#FFFFFF', 'invert' => false],
        ['file' => 'ahir-fuels-mark', 'label' => 'Ahir Fuels', 'bg' => '#FFFFFF', 'invert' => false],
        ['file' => 'lavish-living-mark', 'label' => 'Lavish', 'bg' => '#432818', 'invert' => true],
        ['file' => 'prana-studio-mark', 'label' => 'Prana', 'bg' => '#F6F3EC', 'invert' => false],
        ['file' => 'svadistha-leaf', 'label' => 'Svadistha', 'bg' => '#16231B', 'invert' => false],
    ];

    $tabs = [
        ['file' => 'prana-studio-mark', 'title' => 'Prana Studio'],
        ['file' => 'globelink-mark', 'title' => 'Globelink Shipping'],
        ['file' => 'vansh-maritime-mark', 'title' => 'Vansh Maritime'],
    ];

    $steps = [
        ['title' => 'Discovery', 'desc' => 'Your story, your audience, your competitors.'],
        ['title' => 'Concept', 'desc' => 'A few strong directions, not dozens of weak ones.'],
        ['title' => 'Construct', 'desc' => 'The chosen idea, rebuilt on a grid.'],
        ['title' => 'Refine', 'desc' => 'Weight, curves and spacing, tested at every size.'],
        ['title' => 'Deliver', 'desc' => 'Final files, plus the rules to use them.'],
    ];

    $included = ['Brand strategy and positioning', 'Logo and visual identity design', 'Color palette and typography system', 'Brand guidelines document'];

    $logoAsset = fn (string $file): string => asset('img/logo-design/web/'.$file.'.webp');
@endphp

@section('content')
    <div data-logo-portfolio>

        {{-- Solid-colour filters for the one-colour views; SVG filters, since CSS filter functions are unreliable on SVG <image> in Safari. --}}
        <svg width="0" height="0" class="absolute" aria-hidden="true" focusable="false">
            <filter id="lp-solid-ink" color-interpolation-filters="sRGB">
                <feColorMatrix type="matrix" values="0 0 0 0 0.04  0 0 0 0 0.04  0 0 0 0 0.04  0 0 0 1 0" />
            </filter>
            <filter id="lp-solid-paper" color-interpolation-filters="sRGB">
                <feColorMatrix type="matrix" values="0 0 0 0 0.96  0 0 0 0 0.96  0 0 0 0 0.95  0 0 0 1 0" />
            </filter>
            <pattern id="lp-grid" width="20" height="20" patternUnits="userSpaceOnUse">
                <path d="M 20 0 L 0 0 0 20" fill="none" stroke="currentColor" stroke-width="0.6" opacity="0.12" />
            </pattern>
            <pattern id="lp-grid-major" width="100" height="100" patternUnits="userSpaceOnUse">
                <rect width="100" height="100" fill="url(#lp-grid)" />
                <path d="M 100 0 L 0 0 0 100" fill="none" stroke="currentColor" stroke-width="1" opacity="0.14" />
            </pattern>
        </svg>

        {{-- ─── Cover ────────────────────────────────────────────── --}}
        <section class="relative flex min-h-svh flex-col justify-between overflow-hidden bg-ink pt-28 text-paper sm:pt-32">
            <div class="flex items-start justify-between gap-6 px-5 sm:px-10">
                <x-eyebrow class="text-fg-muted-inverse" data-reveal>Logo portfolio</x-eyebrow>
                @if ($preparedFor !== '')
                    <x-eyebrow class="text-right text-fg-muted-inverse" data-reveal>Prepared for {{ $preparedFor }}</x-eyebrow>
                @else
                    <x-eyebrow class="hidden text-fg-muted-inverse sm:flex" data-reveal>Selected identities</x-eyebrow>
                @endif
            </div>

            <div class="px-5 py-16 sm:px-10">
                <h1 data-split-lines class="max-w-6xl text-display font-medium leading-[0.92] tracking-[-0.045em]">
                    Logos that <span class="accent">earn their place.</span>
                </h1>

                <div class="mt-12 flex flex-col gap-10 md:flex-row md:items-end md:justify-between">
                    <p data-reveal class="max-w-md leading-relaxed text-fg-muted-inverse">
                        {{ count($logos) }} identities for shipping lines, clinics, studios, bakeries and more. Each one drawn on a system, then tested in colour, in one colour, and at every size.
                    </p>
                    <dl data-reveal class="grid grid-cols-3 gap-8 sm:gap-14">
                        <div>
                            <dt class="text-eyebrow uppercase tracking-[0.25em] text-fg-muted-inverse">Identities</dt>
                            <dd class="mt-3 font-display text-h1 font-light leading-none">{{ count($logos) }}</dd>
                        </div>
                        <div>
                            <dt class="text-eyebrow uppercase tracking-[0.25em] text-fg-muted-inverse">Mark types</dt>
                            <dd class="mt-3 font-display text-h1 font-light leading-none">{{ count($types) }}</dd>
                        </div>
                        <div>
                            <dt class="text-eyebrow uppercase tracking-[0.25em] text-fg-muted-inverse">Studied</dt>
                            <dd class="mt-3 font-display text-h1 font-light leading-none">0{{ count($studies) }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <div class="overflow-hidden border-t border-border-inverse py-10 sm:py-14">
                <div data-marquee class="marquee-track" aria-hidden="true">
                    @for ($i = 0; $i < 2; $i++)
                        <div class="flex shrink-0 items-center">
                            @foreach ($marqueeMarks as [$file, $width, $height])
                                <img src="{{ $logoAsset($file) }}" alt="" width="{{ $width }}" height="{{ $height }}" class="h-[clamp(2.75rem,6vw,4.5rem)] w-auto shrink-0 px-8 opacity-90 brightness-0 invert sm:px-14">
                                <x-phosphor-plus-thin class="size-4 shrink-0 text-fg-muted-inverse" />
                            @endforeach
                        </div>
                    @endfor
                </div>
            </div>
        </section>

        {{-- ─── Index ────────────────────────────────────────────── --}}
        <section id="index" class="bg-paper px-5 py-28 text-ink sm:px-10 sm:py-40">
            <div class="flex flex-col gap-10 md:flex-row md:items-end md:justify-between">
                <div>
                    <x-eyebrow number="01" class="text-fg-muted" data-reveal>Index</x-eyebrow>
                    <h2 data-split-lines class="mt-10 text-h1 font-medium leading-[1] tracking-[-0.035em]">
                        The full <span class="accent">collection.</span>
                    </h2>
                </div>
                <p data-reveal class="max-w-xs text-sm text-fg-muted">Tap any mark to see it larger, on light or dark.</p>
            </div>

            <div data-reveal class="mt-16 flex flex-col gap-6 border-t border-border pt-10 lg:flex-row lg:items-center lg:justify-between">
                <div role="group" aria-label="Filter by mark type" class="flex flex-wrap gap-2">
                    <button type="button" data-logo-filter="all" aria-pressed="true" class="rounded-full border border-border px-5 py-2.5 text-sm transition-colors hover:border-ink aria-pressed:border-ink aria-pressed:bg-ink aria-pressed:text-paper">
                        All <span class="tabular-nums opacity-50">{{ count($logos) }}</span>
                    </button>
                    @foreach ($types as $type => $label)
                        <button type="button" data-logo-filter="{{ $type }}" aria-pressed="false" class="rounded-full border border-border px-5 py-2.5 text-sm transition-colors hover:border-ink aria-pressed:border-ink aria-pressed:bg-ink aria-pressed:text-paper">
                            {{ $label }} <span class="tabular-nums opacity-50">{{ collect($logos)->where('type', $type)->count() }}</span>
                        </button>
                    @endforeach
                </div>

                <div role="group" aria-label="Colour view" class="inline-flex self-start rounded-full border border-border p-1 lg:self-auto">
                    <button type="button" data-logo-view="colour" aria-pressed="true" class="rounded-full px-5 py-2 text-sm transition-colors aria-pressed:bg-ink aria-pressed:text-paper">Colour</button>
                    <button type="button" data-logo-view="mono" aria-pressed="false" class="rounded-full px-5 py-2 text-sm transition-colors aria-pressed:bg-ink aria-pressed:text-paper">Mono</button>
                </div>
            </div>

            <ul data-logo-grid class="mt-12 grid grid-cols-1 gap-x-6 gap-y-12 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($logos as $logo)
                    @php
                        $isDark = ($logo['tone'] ?? null) === 'dark';
                    @endphp
                    <li data-logo-item data-type="{{ $logo['type'] }}">
                        <button type="button"
                                data-logo-open
                                data-src="{{ $logoAsset($logo['slug']) }}"
                                data-name="{{ $logo['name'] }}"
                                data-meta="{{ collect([$logo['sector'], Str::singular($types[$logo['type']])])->filter()->implode(' · ') }}"
                                data-tone="{{ $isDark ? 'dark' : 'light' }}"
                                data-cursor="View"
                                class="group block w-full text-left">
                            <span @class([
                                'relative flex aspect-[4/3] items-center justify-center overflow-hidden px-[16%] py-[14%]',
                                'bg-ink text-paper' => $isDark,
                                'bg-white text-ink' => ! $isDark,
                            ])>
                                <span class="crop-marks" aria-hidden="true"><i></i></span>
                                <span class="absolute left-5 top-5 text-eyebrow tabular-nums tracking-[0.25em] opacity-50">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                                <img src="{{ $logoAsset($logo['slug']) }}" alt="{{ $logo['name'] }} logo" width="{{ $logo['width'] }}" height="{{ $logo['height'] }}" loading="lazy" decoding="async" class="max-h-full w-auto max-w-full object-contain group-hover:scale-[1.04]">
                            </span>
                            <span class="mt-4 flex items-baseline justify-between gap-4">
                                <span class="font-medium tracking-[-0.01em]">{{ $logo['name'] }}</span>
                                <span class="shrink-0 text-xs text-fg-muted">{{ $logo['sector'] ?? Str::singular($types[$logo['type']]) }}</span>
                            </span>
                        </button>
                    </li>
                @endforeach
            </ul>
        </section>

        {{-- ─── Anatomy ──────────────────────────────────────────── --}}
        <section id="anatomy" class="bg-ink px-5 py-28 text-paper sm:px-10 sm:py-40">
            <div class="flex flex-col gap-10 md:flex-row md:items-end md:justify-between">
                <div>
                    <x-eyebrow number="02" class="text-fg-muted-inverse" data-reveal>Anatomy</x-eyebrow>
                    <h2 data-split-lines class="mt-10 text-h1 font-medium leading-[1] tracking-[-0.035em]">
                        How a mark <span class="accent">is built.</span>
                    </h2>
                </div>
                <p data-reveal class="max-w-xs text-sm text-fg-muted-inverse">Four marks, taken apart. Switch views to see the grid, the colour, and the mark in one colour.</p>
            </div>

            <div class="mt-20">
                @foreach ($studies as $study)
                    @php
                        $pad = 80;
                        $viewBox = (-$pad).' '.(-$pad).' '.($study['width'] + $pad * 2).' '.($study['height'] + $pad * 2);
                        [$markFile, $markWidth, $markHeight] = $study['mark'];
                    @endphp
                    <article data-anatomy class="grid grid-cols-1 gap-10 border-t border-border-inverse py-14 lg:grid-cols-12 lg:gap-16 lg:py-20">
                        <div class="lg:col-span-7">
                            <div class="mb-5 flex flex-wrap items-center justify-between gap-4">
                                <span class="text-eyebrow uppercase tabular-nums tracking-[0.25em] text-fg-muted-inverse">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }} / {{ str_pad(count($studies), 2, '0', STR_PAD_LEFT) }}</span>
                                <div role="group" aria-label="View {{ $study['name'] }} as" class="inline-flex rounded-full border border-border-inverse p-1">
                                    @foreach (['construct' => 'Grid', 'colour' => 'Colour', 'mono' => 'Mono', 'reverse' => 'Reverse'] as $mode => $label)
                                        <button type="button" data-anatomy-mode="{{ $mode }}" aria-pressed="{{ $loop->first ? 'true' : 'false' }}" class="rounded-full px-3.5 py-1.5 text-xs transition-colors aria-pressed:bg-paper aria-pressed:text-ink sm:px-4 sm:text-sm">{{ $label }}</button>
                                    @endforeach
                                </div>
                            </div>

                            <div data-anatomy-stage data-mode="construct" class="anatomy-stage border border-border-inverse">
                                <svg viewBox="{{ $viewBox }}" class="block h-auto w-full" role="img" aria-label="{{ $study['name'] }} logo with its construction grid">
                                    <rect class="stage-grid" x="{{ -$pad }}" y="{{ -$pad }}" width="{{ $study['width'] + $pad * 2 }}" height="{{ $study['height'] + $pad * 2 }}" fill="url(#lp-grid-major)" />
                                    <image class="anatomy-image" href="{{ $logoAsset($study['slug']) }}" x="0" y="0" width="{{ $study['width'] }}" height="{{ $study['height'] }}" />
                                    <g class="construction max-sm:[&_text]:hidden" aria-hidden="true">
                                        @include('logo-portfolio.constructions.'.$study['slug'])
                                    </g>
                                </svg>
                            </div>
                        </div>

                        <div class="flex flex-col justify-between gap-12 lg:col-span-5">
                            <div>
                                <h3 class="text-h2 font-medium tracking-[-0.03em]">{{ $study['name'] }}</h3>
                                <p class="mt-2 text-sm text-fg-muted-inverse">{{ $study['meta'] }}</p>

                                <ol class="mt-10">
                                    @foreach ($study['notes'] as $note)
                                        <li class="flex items-baseline gap-5 border-t border-border-inverse py-4 last:border-b">
                                            <span class="font-display text-sm font-light tabular-nums text-fg-muted-inverse">0{{ $loop->iteration }}</span>
                                            <span>{{ $note }}</span>
                                        </li>
                                    @endforeach
                                </ol>
                            </div>

                            <div>
                                <p class="text-eyebrow uppercase tracking-[0.25em] text-fg-muted-inverse">Palette</p>
                                <ul class="mt-4 flex flex-wrap gap-5">
                                    @foreach ($study['palette'] as $hex => $colourName)
                                        <li>
                                            <button type="button" data-copy="{{ $hex }}" class="group flex items-center gap-3 text-left" data-cursor="Copy">
                                                <span class="size-11 shrink-0 rounded-full border border-paper/20" style="background-color: {{ $hex }}"></span>
                                                <span class="flex flex-col">
                                                    <span class="text-sm">{{ $colourName }}</span>
                                                    <span data-copy-label class="text-xs tabular-nums text-fg-muted-inverse">{{ $hex }}</span>
                                                </span>
                                            </button>
                                        </li>
                                    @endforeach
                                </ul>

                                <p class="mt-10 text-eyebrow uppercase tracking-[0.25em] text-fg-muted-inverse">Scale test</p>
                                <div class="mt-4 flex items-end gap-8 bg-paper px-6 py-5 text-ink">
                                    @foreach ([64, 32, 16] as $size)
                                        <div class="flex flex-col items-start gap-3">
                                            <img src="{{ $logoAsset($markFile) }}" alt="" width="{{ $markWidth }}" height="{{ $markHeight }}" loading="lazy" class="w-auto" style="height: {{ $size }}px">
                                            <span class="text-xs tabular-nums text-fg-muted">{{ $size }}px</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        </section>

        {{-- ─── In use ───────────────────────────────────────────── --}}
        <section id="in-use" class="bg-paper px-5 py-28 text-ink sm:px-10 sm:py-40">
            <div class="flex flex-col gap-10 md:flex-row md:items-end md:justify-between">
                <div>
                    <x-eyebrow number="03" class="text-fg-muted" data-reveal>In use</x-eyebrow>
                    <h2 data-split-lines class="mt-10 text-h1 font-medium leading-[1] tracking-[-0.035em]">
                        Built for <span class="accent">every surface.</span>
                    </h2>
                </div>
                <p data-reveal class="max-w-xs text-sm text-fg-muted">From a printed card to a 16px browser tab.</p>
            </div>

            <div class="mt-16 grid grid-cols-1 gap-4 lg:grid-cols-12">

                {{-- Business card --}}
                <div data-reveal class="flex flex-col bg-[#e9e9e6] p-6 sm:p-10 lg:col-span-7">
                    <div class="flex items-center justify-between text-eyebrow uppercase tracking-[0.25em] text-fg-muted">
                        <span>Stationery</span>
                        <span class="hidden sm:inline">Tap to turn</span>
                    </div>
                    <div class="flex flex-1 items-center justify-center py-14 sm:py-20">
                        <button type="button" data-flip-card aria-pressed="false" aria-label="Turn the Lavish Living business card over" data-cursor="Turn" class="flip-card block w-full max-w-md">
                            <span class="flip-card-inner block aspect-[1.75] w-full">
                                <span class="flip-card-face flex h-full w-full flex-col justify-between bg-white p-[7%] shadow-[0_30px_60px_-30px_rgba(0,0,0,0.35)]">
                                    <img src="{{ $logoAsset('lavish-living') }}" alt="Lavish Living logo" width="962" height="378" loading="lazy" class="h-auto w-[62%]">
                                    <span class="flex items-end justify-between text-left text-[clamp(0.5rem,1.4vw,0.7rem)] leading-relaxed text-[#432818]">
                                        <span>Founder<br>Exclusive home furnishings</span>
                                        <span class="text-right">hello@lavishliving<br>+91 00000 00000</span>
                                    </span>
                                </span>
                                <span class="flip-card-face flip-card-back flex items-center justify-center bg-[#432818] shadow-[0_30px_60px_-30px_rgba(0,0,0,0.35)]">
                                    <img src="{{ $logoAsset('lavish-living-mark') }}" alt="" width="297" height="297" loading="lazy" class="h-[38%] w-auto brightness-0 invert">
                                </span>
                            </span>
                        </button>
                    </div>
                </div>

                {{-- App icons --}}
                <div data-reveal class="flex flex-col bg-ink p-6 text-paper sm:p-10 lg:col-span-5 lg:row-span-2">
                    <div class="text-eyebrow uppercase tracking-[0.25em] text-fg-muted-inverse">App icon</div>
                    <div class="flex flex-1 items-center justify-center py-12">
                        <div class="w-full max-w-[19rem] rounded-[2.75rem] border border-border-inverse bg-[#141414] p-3">
                            <div class="rounded-[2.2rem] bg-gradient-to-b from-[#2a2a2a] to-[#111] px-6 pb-10 pt-5">
                                <div class="flex items-center justify-between text-[0.7rem] font-medium tabular-nums">
                                    <span>9:41</span>
                                    <span class="h-5 w-20 rounded-full bg-black"></span>
                                    <span class="flex items-center gap-1"><x-phosphor-cell-signal-full-fill class="size-3.5" /><x-phosphor-battery-full-fill class="size-4" /></span>
                                </div>
                                <ul class="mt-12 grid grid-cols-3 gap-x-5 gap-y-7">
                                    @foreach ($appIcons as $icon)
                                        <li data-app-icon class="flex flex-col items-center gap-2">
                                            <span class="flex aspect-square w-full items-center justify-center rounded-[24%] p-[18%] shadow-[0_8px_20px_-8px_rgba(0,0,0,0.6)]" style="background-color: {{ $icon['bg'] }}">
                                                <img src="{{ $logoAsset($icon['file']) }}" alt="" loading="lazy" @class(['max-h-full max-w-full object-contain', 'brightness-0 invert' => $icon['invert']])>
                                            </span>
                                            <span class="text-[0.65rem] text-paper/80">{{ $icon['label'] }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                                <div class="mx-auto mt-14 h-1 w-24 rounded-full bg-paper/60"></div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Favicons --}}
                <div data-reveal class="flex flex-col bg-white p-6 sm:p-10 lg:col-span-7">
                    <div class="text-eyebrow uppercase tracking-[0.25em] text-fg-muted">Favicon · 16px</div>
                    <div class="mt-10 overflow-hidden rounded-xl border border-border bg-[#ececea]">
                        <div class="flex items-end gap-1 overflow-x-auto px-3 pt-3">
                            <span class="mb-3 mr-3 hidden gap-1.5 sm:flex">
                                <span class="size-2.5 rounded-full bg-[#cfcfcc]"></span>
                                <span class="size-2.5 rounded-full bg-[#cfcfcc]"></span>
                                <span class="size-2.5 rounded-full bg-[#cfcfcc]"></span>
                            </span>
                            @foreach ($tabs as $tab)
                                <span @class([
                                    'flex min-w-0 shrink-0 items-center gap-2.5 rounded-t-lg px-4 py-2.5 text-xs sm:min-w-44',
                                    'bg-white' => $loop->first,
                                    'text-fg-muted' => ! $loop->first,
                                ])>
                                    <img src="{{ $logoAsset($tab['file']) }}" alt="" loading="lazy" class="size-4 shrink-0 object-contain">
                                    <span class="truncate">{{ $tab['title'] }}</span>
                                    <x-phosphor-x-thin class="ml-auto size-3 shrink-0 opacity-50" />
                                </span>
                            @endforeach
                        </div>
                        <div class="flex items-center gap-3 bg-white px-4 py-3">
                            <x-phosphor-arrow-left-thin class="size-4 text-fg-muted" />
                            <x-phosphor-arrow-right-thin class="size-4 text-fg-muted" />
                            <span class="flex flex-1 items-center gap-2 rounded-full bg-[#f1f1ef] px-4 py-1.5 text-xs text-fg-muted">
                                <x-phosphor-lock-simple-thin class="size-3.5" /> pranastudio.in
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Mark to lockup --}}
                <div data-reveal class="flex flex-col bg-white p-6 sm:p-10 lg:col-span-6">
                    <div class="text-eyebrow uppercase tracking-[0.25em] text-fg-muted">Mark → lockup</div>
                    <div class="mt-10 grid flex-1 grid-cols-[1fr_auto_2fr] items-center gap-4 sm:gap-8">
                        <img src="{{ $logoAsset('ahir-fuels-mark') }}" alt="Ahir Fuels monogram" width="873" height="599" loading="lazy" class="h-auto w-full">
                        <x-phosphor-arrow-right-thin class="size-6 text-fg-muted" aria-hidden="true" />
                        <img src="{{ $logoAsset('ahir-fuels') }}" alt="Ahir Fuels full logo" width="1054" height="275" loading="lazy" class="h-auto w-full">
                    </div>
                </div>

                {{-- Stacked / horizontal --}}
                <div data-reveal class="flex flex-col bg-[#16231b] p-6 text-paper sm:p-10 lg:col-span-6">
                    <div class="text-eyebrow uppercase tracking-[0.25em] text-paper/60">Stacked / horizontal</div>
                    <div class="mt-10 grid flex-1 grid-cols-[2fr_3fr] items-center gap-6 sm:gap-10">
                        <img src="{{ $logoAsset('svadistha-mark') }}" alt="Svadistha stacked logo" width="898" height="785" loading="lazy" class="h-auto w-full">
                        <img src="{{ $logoAsset('svadistha') }}" alt="Svadistha horizontal logo" width="1400" height="452" loading="lazy" class="h-auto w-full">
                    </div>
                </div>
            </div>
        </section>

        {{-- ─── Process (horizontal on desktop) ──────────────────── --}}
        <section data-process class="relative overflow-hidden bg-ink text-paper">
            <div data-process-track class="flex flex-col md:h-svh md:w-max md:flex-row md:items-stretch">
                <div class="flex shrink-0 flex-col justify-between gap-12 px-5 py-24 sm:px-10 md:w-[46vw] md:py-28">
                    <div>
                        <x-eyebrow number="04" class="text-fg-muted-inverse" data-reveal>Process</x-eyebrow>
                        <h2 data-split-lines class="mt-10 text-h1 font-medium leading-[1] tracking-[-0.035em]">
                            From brief <span class="accent">to mark.</span>
                        </h2>
                    </div>
                    <svg viewBox="0 0 240 240" class="w-40 text-paper/70 sm:w-52" fill="none" stroke="currentColor" stroke-width="0.75" aria-hidden="true">
                        <rect x="20" y="20" width="200" height="200" stroke-dasharray="3 5" />
                        <line x1="120" y1="0" x2="120" y2="240" stroke-dasharray="3 5" />
                        <line x1="0" y1="120" x2="240" y2="120" stroke-dasharray="3 5" />
                        <circle cx="120" cy="120" r="70" />
                        <circle cx="120" cy="120" r="100" stroke-dasharray="3 5" />
                        <line x1="20" y1="220" x2="220" y2="20" />
                        <circle cx="120" cy="120" r="3" fill="currentColor" />
                    </svg>
                </div>

                @foreach ($steps as $step)
                    <article data-process-step class="flex shrink-0 flex-col justify-between border-t border-border-inverse px-5 py-14 sm:px-10 md:w-[30vw] md:min-w-[340px] md:border-l md:border-t-0 md:py-28">
                        <span class="font-display text-[clamp(5rem,12vw,11rem)] font-light leading-[0.8] text-paper/90">0{{ $loop->iteration }}</span>
                        <div class="mt-16 md:mt-0">
                            <svg viewBox="0 0 80 48" class="mb-8 h-12 w-20 text-paper/60" fill="none" stroke="currentColor" stroke-width="1" aria-hidden="true">
                                @switch($loop->iteration)
                                    @case(1)
                                        <circle cx="30" cy="22" r="14" /><line x1="40" y1="32" x2="54" y2="46" />
                                        @break
                                    @case(2)
                                        <path d="M4 30 C 14 4, 24 44, 34 20 S 52 6, 58 28 S 72 40, 76 14" />
                                        @break
                                    @case(3)
                                        <rect x="16" y="2" width="44" height="44" stroke-dasharray="2 3" /><circle cx="38" cy="24" r="16" /><line x1="16" y1="46" x2="60" y2="2" />
                                        @break
                                    @case(4)
                                        <path d="M6 40 C 26 4, 54 4, 74 40" /><line x1="6" y1="40" x2="22" y2="10" /><line x1="74" y1="40" x2="58" y2="10" /><circle cx="22" cy="10" r="2.5" fill="currentColor" /><circle cx="58" cy="10" r="2.5" fill="currentColor" />
                                        @break
                                    @default
                                        <rect x="22" y="10" width="30" height="36" /><rect x="28" y="4" width="30" height="36" fill="#0a0a0a" />
                                @endswitch
                            </svg>
                            <h3 class="text-h2 font-medium tracking-[-0.03em]">{{ $step['title'] }}</h3>
                            <p class="mt-4 max-w-xs text-fg-muted-inverse">{{ $step['desc'] }}</p>
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="absolute inset-x-5 bottom-8 hidden h-px bg-border-inverse sm:inset-x-10 md:block" aria-hidden="true">
                <div data-process-progress class="h-full origin-left scale-x-0 bg-paper"></div>
            </div>
        </section>

        {{-- ─── Included ─────────────────────────────────────────── --}}
        <section class="bg-paper px-5 py-28 text-ink sm:px-10 sm:py-40">
            <div class="grid grid-cols-1 gap-16 lg:grid-cols-12">
                <div class="lg:col-span-5">
                    <x-eyebrow number="05" class="text-fg-muted" data-reveal>Included</x-eyebrow>
                    <h2 data-split-lines class="mt-10 text-h1 font-medium leading-[1] tracking-[-0.035em]">More than <span class="accent">a logo.</span></h2>
                    <a href="{{ route('services.show', 'branding') }}" data-reveal class="link-line mt-8 text-sm">
                        About our branding service <x-phosphor-arrow-up-right-thin class="size-4" aria-hidden="true" />
                    </a>
                </div>
                <ul class="lg:col-span-6 lg:col-start-7">
                    @foreach ($included as $item)
                        <li data-reveal class="flex items-start gap-4 border-b border-border py-6 first:border-t">
                            <x-phosphor-plus-thin class="mt-1 size-4 shrink-0 text-fg-muted" aria-hidden="true" />
                            <span>{{ $item }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </section>

        <x-cta-band eyebrow="Next" :text="$preparedFor !== '' ? 'Let’s build the mark for '.$preparedFor.'.' : 'Let’s talk about the brand behind it.'">
            Your mark <span class="accent">is next.</span>
        </x-cta-band>

        {{-- ─── Lightbox ─────────────────────────────────────────── --}}
        <dialog data-lightbox aria-label="Logo viewer" class="m-0 h-dvh max-h-none w-screen max-w-none bg-transparent p-0 text-ink backdrop:bg-ink/70">
            <div data-lightbox-panel data-bg="light" class="group/lightbox flex h-full flex-col bg-white transition-colors duration-700 ease-expo data-[bg=dark]:bg-ink data-[bg=dark]:text-paper data-[bg=grid]:bg-[#fbfbf9]">
                <div class="flex items-center justify-between gap-6 px-5 py-5 sm:px-10 sm:py-7">
                    <span data-lightbox-count class="text-eyebrow uppercase tabular-nums tracking-[0.25em] opacity-60"></span>
                    <button type="button" data-lightbox-close class="group flex items-center gap-3 text-sm" data-cursor="Close">
                        <span class="roll"><span>Close</span><span aria-hidden="true">Close</span></span>
                        <x-phosphor-x-thin class="size-5" aria-hidden="true" />
                    </button>
                </div>

                <div class="relative flex min-h-0 flex-1 items-center justify-center px-5 sm:px-24">
                    <svg class="pointer-events-none absolute inset-0 hidden h-full w-full group-data-[bg=grid]/lightbox:block" aria-hidden="true">
                        <rect width="100%" height="100%" fill="url(#lp-grid-major)" />
                    </svg>
                    <button type="button" data-lightbox-prev aria-label="Previous logo" class="absolute left-3 top-1/2 z-10 hidden -translate-y-1/2 rounded-full border border-current/20 p-3 transition-colors hover:border-current sm:left-8 sm:block">
                        <x-phosphor-arrow-left-thin class="size-5" />
                    </button>
                    <img data-lightbox-image src="" alt="" class="relative max-h-[62vh] w-auto max-w-[min(100%,56rem)] object-contain">
                    <button type="button" data-lightbox-next aria-label="Next logo" class="absolute right-3 top-1/2 z-10 hidden -translate-y-1/2 rounded-full border border-current/20 p-3 transition-colors hover:border-current sm:right-8 sm:block">
                        <x-phosphor-arrow-right-thin class="size-5" />
                    </button>
                </div>

                <div class="flex flex-col gap-6 px-5 pb-6 pt-5 sm:flex-row sm:items-end sm:justify-between sm:px-10 sm:pb-10">
                    <div>
                        <p data-lightbox-name class="text-h2 font-medium tracking-[-0.03em]"></p>
                        <p data-lightbox-meta class="mt-1 text-sm opacity-60"></p>
                    </div>
                    <div class="flex items-center justify-between gap-4">
                        <div role="group" aria-label="Background" class="inline-flex rounded-full border border-current/20 p-1">
                            @foreach (['light' => 'Light', 'dark' => 'Dark', 'grid' => 'Grid'] as $bg => $label)
                                <button type="button" data-lightbox-bg="{{ $bg }}" aria-pressed="false" class="rounded-full px-4 py-1.5 text-sm transition-colors aria-pressed:bg-current/10">{{ $label }}</button>
                            @endforeach
                        </div>
                        <div class="flex gap-2 sm:hidden">
                            <button type="button" data-lightbox-prev aria-label="Previous logo" class="rounded-full border border-current/20 p-2.5"><x-phosphor-arrow-left-thin class="size-5" /></button>
                            <button type="button" data-lightbox-next aria-label="Next logo" class="rounded-full border border-current/20 p-2.5"><x-phosphor-arrow-right-thin class="size-5" /></button>
                        </div>
                    </div>
                </div>
            </div>
        </dialog>
    </div>
@endsection
