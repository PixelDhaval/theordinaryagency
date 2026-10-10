@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination" class="flex flex-col items-center justify-between gap-6 border-t border-border pt-10 sm:flex-row">
        <p class="text-sm text-fg-muted">
            Showing {{ $paginator->firstItem() }}–{{ $paginator->lastItem() }} of {{ $paginator->total() }}
        </p>

        <ul class="flex items-center gap-2">
            <li>
                @if ($paginator->onFirstPage())
                    <span class="flex size-11 items-center justify-center rounded-full border border-border text-fg-muted opacity-40" aria-disabled="true" aria-label="Previous page">
                        <x-phosphor-arrow-left-thin class="size-5" aria-hidden="true" />
                    </span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Previous page" class="flex size-11 items-center justify-center rounded-full border border-border transition-colors hover:border-ink hover:bg-ink hover:text-paper">
                        <x-phosphor-arrow-left-thin class="size-5" aria-hidden="true" />
                    </a>
                @endif
            </li>

            @foreach ($elements as $element)
                @if (is_string($element))
                    <li><span class="px-2 text-fg-muted" aria-hidden="true">{{ $element }}</span></li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        <li>
                            @if ($page == $paginator->currentPage())
                                <span aria-current="page" class="flex size-11 items-center justify-center rounded-full bg-ink text-sm tabular-nums text-paper">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" aria-label="Go to page {{ $page }}" class="flex size-11 items-center justify-center rounded-full text-sm tabular-nums transition-colors hover:bg-ink/5">{{ $page }}</a>
                            @endif
                        </li>
                    @endforeach
                @endif
            @endforeach

            <li>
                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Next page" class="flex size-11 items-center justify-center rounded-full border border-border transition-colors hover:border-ink hover:bg-ink hover:text-paper">
                        <x-phosphor-arrow-right-thin class="size-5" aria-hidden="true" />
                    </a>
                @else
                    <span class="flex size-11 items-center justify-center rounded-full border border-border text-fg-muted opacity-40" aria-disabled="true" aria-label="Next page">
                        <x-phosphor-arrow-right-thin class="size-5" aria-hidden="true" />
                    </span>
                @endif
            </li>
        </ul>
    </nav>
@endif
