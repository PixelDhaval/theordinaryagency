@props([
    'project',
    'aspect' => 'aspect-[4/5]',
])

<article {{ $attributes->class(['group']) }}>
    <a href="{{ route('work.show', $project->slug) }}" data-cursor="View" class="block">
        <div data-clip-reveal class="relative overflow-hidden bg-ink/5 {{ $aspect }}">
            <img src="{{ $project->cover_url }}"
                 alt="{{ $project->title }} — {{ $project->discipline }} for {{ $project->client }}"
                 loading="lazy"
                 class="absolute inset-0 h-full w-full object-cover transition-transform duration-[1400ms] ease-expo group-hover:scale-105">
            <span class="absolute left-4 top-4 rounded-full bg-paper/90 px-3 py-1.5 text-eyebrow uppercase tracking-[0.2em] text-ink backdrop-blur-sm">
                {{ $project->discipline }}
            </span>
            <span class="absolute bottom-4 right-4 flex size-11 translate-y-3 items-center justify-center rounded-full bg-paper text-ink opacity-0 transition-all duration-500 ease-expo group-hover:translate-y-0 group-hover:opacity-100" aria-hidden="true">
                <x-phosphor-arrow-up-right-thin class="size-5" />
            </span>
        </div>

        <div data-reveal class="mt-5 flex items-start justify-between gap-6">
            <div>
                <h3 class="text-h3 font-medium tracking-[-0.03em]">
                    <span class="link-line">{{ $project->title }}</span>
                </h3>
                <p class="mt-2 max-w-md text-sm leading-relaxed text-fg-muted">
                    <span class="text-ink">{{ $project->client }}</span> — {{ $project->summary }}
                </p>
            </div>
            <span class="pt-1.5 text-eyebrow tabular-nums text-fg-muted">{{ $project->year }}</span>
        </div>
    </a>
</article>
