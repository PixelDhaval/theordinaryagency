@props([
    'items' => [],
])

@php
    $trail = array_values(array_merge([['label' => 'Home', 'url' => route('home')]], $items));

    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => collect($trail)->map(fn (array $crumb, int $index) => [
            '@type' => 'ListItem',
            'position' => $index + 1,
            'name' => $crumb['label'],
            'item' => $crumb['url'] ?? url()->current(),
        ])->all(),
    ];
@endphp

<nav aria-label="Breadcrumb" {{ $attributes }}>
    <ol class="flex flex-wrap items-center gap-x-2 gap-y-1 text-eyebrow uppercase tracking-[0.2em]">
        @foreach ($trail as $crumb)
            <li class="flex items-center gap-2">
                @if (! $loop->last)
                    <a href="{{ $crumb['url'] }}" class="link-line text-fg-muted transition-colors hover:text-current">{{ $crumb['label'] }}</a>
                    <x-phosphor-caret-right-thin class="size-3 opacity-40" aria-hidden="true" />
                @else
                    <span aria-current="page">{{ $crumb['label'] }}</span>
                @endif
            </li>
        @endforeach
    </ol>
</nav>

@push('schema')
    <x-schema :data="$schema" />
@endpush
