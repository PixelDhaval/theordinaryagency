@php
    // Inline @section values are already HTML-escaped by Blade, so they are echoed raw below.
    $siteName = config('agency.name');
    $pageTitle = trim($__env->yieldContent('title')) ?: e($siteName.' - Branding & Design Studio');
    $pageDescription = trim($__env->yieldContent('description')) ?: e('A branding and design studio for packaging, pitch decks, social, brochures, and UI/UX. See the work, then book a call to start your project.');
    $canonical = trim($__env->yieldContent('canonical')) ?: url()->current();
    $robots = trim($__env->yieldContent('robots')) ?: (app()->isProduction() ? 'index, follow, max-image-preview:large' : 'noindex, nofollow');
    $ogType = trim($__env->yieldContent('og_type')) ?: 'website';
    $ogImage = trim($__env->yieldContent('og_image')) ?: asset(config('agency.og_image'));

    $organization = array_filter([
        '@type' => 'Organization',
        '@id' => url('/').'#organization',
        'name' => $siteName,
        'url' => url('/'),
        'logo' => asset('img/logo/logo-light.png'),
        'email' => config('agency.email'),
        'sameAs' => array_values(config('agency.social')) ?: null,
    ]);

    $siteSchema = [
        '@context' => 'https://schema.org',
        '@graph' => [
            $organization,
            [
                '@type' => 'WebSite',
                '@id' => url('/').'#website',
                'url' => url('/'),
                'name' => $siteName,
                'publisher' => ['@id' => url('/').'#organization'],
            ],
        ],
    ];
@endphp

<title>{!! $pageTitle !!}</title>
<meta name="description" content="{!! $pageDescription !!}">
<meta name="robots" content="{{ $robots }}">
<link rel="canonical" href="{{ $canonical }}">

<meta property="og:type" content="{{ $ogType }}">
<meta property="og:site_name" content="{{ $siteName }}">
<meta property="og:locale" content="en_US">
<meta property="og:title" content="{!! $pageTitle !!}">
<meta property="og:description" content="{!! $pageDescription !!}">
<meta property="og:url" content="{{ $canonical }}">
<meta property="og:image" content="{{ $ogImage }}">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{!! $pageTitle !!}">
<meta name="twitter:description" content="{!! $pageDescription !!}">
<meta name="twitter:image" content="{{ $ogImage }}">

<x-schema :data="$siteSchema" />
