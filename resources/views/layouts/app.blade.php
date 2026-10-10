@php
    $navigation = [
        ['label' => 'Work', 'route' => 'work'],
        ['label' => 'Services', 'route' => 'services'],
        ['label' => 'Process', 'route' => 'process'],
        ['label' => 'About', 'route' => 'about'],
        ['label' => 'Journal', 'route' => 'journal'],
        ['label' => 'Contact', 'route' => 'contact'],
    ];
@endphp
<!doctype html>
<html lang="en" class="no-js">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="theme-color" content="#0a0a0a">

    @include('partials.seo')
    @stack('schema')

    <link rel="icon" href="{{ asset('img/logo/logo-favicon-dark.png') }}" sizes="any">
    <link rel="apple-touch-icon" href="{{ asset('img/logo/logo-favicon-dark.png') }}">

    <script>
        document.documentElement.classList.replace('no-js', 'js');
        document.documentElement.classList.add('is-loading');
    </script>

    <link rel="preload" href="{{ Vite::asset('resources/fonts/tt-firs-neue-var.woff2') }}" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="{{ Vite::asset('resources/fonts/adam-light.woff2') }}" as="font" type="font/woff2" crossorigin>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="bg-paper font-sans text-ink">

    <a href="#main" class="sr-only z-[200] bg-ink px-4 py-2 text-paper focus:not-sr-only focus:fixed focus:left-4 focus:top-4">Skip to content</a>

    @if (request()->routeIs('home'))
        @include('partials.preloader')
    @endif
    @include('partials.cursor')
    @include('partials.header', ['navigation' => $navigation])
    @include('partials.navbar', ['navigation' => $navigation])

    <div id="smooth-wrapper">
        <div id="smooth-content">
            <main id="main">
                @yield('content')
            </main>

            @include('partials.footer', ['navigation' => $navigation])
        </div>
    </div>

    @include('partials.go-to-top')

    <div class="grain" aria-hidden="true"></div>
</body>
</html>
