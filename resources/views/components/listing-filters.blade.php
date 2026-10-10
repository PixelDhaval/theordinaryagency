@props([
    'action',
    'search' => '',
    'options' => [],
    'param',
    'active' => null,
    'placeholder' => 'Search',
    'label' => 'Filter',
])

{{-- GET-only search + filter chips: works without JS, shareable, and preserves the other parameter. --}}
<div {{ $attributes->class(['flex flex-col gap-6 border-t border-border pt-10 lg:flex-row lg:items-center lg:justify-between']) }}>
    @if (count($options))
        <nav aria-label="{{ $label }}" class="flex flex-wrap gap-2">
            <a href="{{ $action.($search !== '' ? '?'.http_build_query(['q' => $search]) : '') }}"
               @if (! $active) aria-current="page" @endif
               @class([
                   'rounded-full border px-5 py-2.5 text-sm transition-colors',
                   'border-ink bg-ink text-paper' => ! $active,
                   'border-border hover:border-ink' => $active,
               ])>All</a>
            @foreach ($options as $slug => $name)
                <a href="{{ $action.'?'.http_build_query(array_filter([$param => $slug, 'q' => $search])) }}"
                   @if ($active === $slug) aria-current="page" @endif
                   @class([
                       'rounded-full border px-5 py-2.5 text-sm transition-colors',
                       'border-ink bg-ink text-paper' => $active === $slug,
                       'border-border hover:border-ink' => $active !== $slug,
                   ])>{{ $name }}</a>
            @endforeach
        </nav>
    @endif

    <form method="GET" action="{{ $action }}" role="search" class="relative w-full lg:max-w-sm">
        @if ($active)
            <input type="hidden" name="{{ $param }}" value="{{ $active }}">
        @endif
        <label for="listing-search" class="sr-only">{{ $placeholder }}</label>
        <x-phosphor-magnifying-glass-thin class="pointer-events-none absolute left-4 top-1/2 size-5 -translate-y-1/2 text-fg-muted" aria-hidden="true" />
        <input id="listing-search" type="search" name="q" value="{{ $search }}" maxlength="100" placeholder="{{ $placeholder }}"
               class="w-full rounded-full border border-border bg-transparent py-3 pl-12 pr-28 text-sm outline-none transition-colors placeholder:text-fg-muted focus:border-ink">
        <button type="submit" class="absolute right-1.5 top-1/2 -translate-y-1/2 rounded-full bg-ink px-5 py-2 text-sm text-paper">Search</button>
    </form>
</div>
