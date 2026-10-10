@props([
    'post',
])

<article {{ $attributes->class(['group']) }}>
    <a href="{{ route('journal.show', $post->slug) }}" data-cursor="Read" class="block">
        <div data-clip-reveal class="relative aspect-[4/3] overflow-hidden bg-ink/5">
            <img src="{{ $post->cover_url }}"
                 alt=""
                 loading="lazy"
                 class="absolute inset-0 h-full w-full object-cover transition-transform duration-[1400ms] ease-expo group-hover:scale-105">
        </div>

        <div data-reveal class="mt-6">
            <p class="flex items-center gap-3 text-eyebrow uppercase tracking-[0.2em] text-fg-muted">
                @if ($post->category)
                    <span>{{ $post->category->name }}</span>
                    <span class="h-px w-4 bg-current opacity-40"></span>
                @endif
                <time datetime="{{ $post->published_at->toDateString() }}">{{ $post->published_at->format('M j, Y') }}</time>
            </p>

            <h3 class="mt-4 text-h3 font-medium leading-[1.2] tracking-[-0.025em] transition-colors duration-500 group-hover:text-fg-muted">
                {{ $post->title }}
            </h3>

            <p class="mt-3 text-sm leading-relaxed text-fg-muted">{{ $post->excerpt }}</p>

            <span class="mt-6 inline-flex items-center gap-2 text-sm">
                <span class="roll"><span>Read article</span><span aria-hidden="true">Read article</span></span>
                <span class="text-fg-muted">· {{ $post->reading_time }} min</span>
                <x-phosphor-arrow-right-thin class="size-4 transition-transform duration-500 ease-expo group-hover:translate-x-1" aria-hidden="true" />
            </span>
        </div>
    </a>
</article>
