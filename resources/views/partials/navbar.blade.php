@php
    $menuImages = [
        'work' => 'packaging',
        'services' => 'branding',
        'process' => 'process',
        'about' => 'philosophy',
        'journal' => 'brochure',
        'contact' => 'contact',
    ];
@endphp
<div id="site-menu" class="invisible fixed inset-0 z-[110] bg-ink text-paper" style="clip-path: inset(0% 0% 100% 0%)" role="dialog" aria-modal="true" aria-label="Site menu" aria-hidden="true">
    <div class="grid h-full grid-cols-1 gap-10 px-5 pb-8 pt-28 sm:px-10 lg:grid-cols-12 lg:pt-32">

        <nav class="flex flex-col justify-center lg:col-span-7" aria-label="Menu">
            <ul class="space-y-1">
                @foreach ($navigation as $index => $item)
                    <li class="line-mask">
                        <a href="{{ route($item['route']) }}"
                           data-menu-item
                           data-menu-image="{{ $menuImages[$item['route']] }}"
                           class="group flex items-baseline gap-5 py-1">
                            <span class="w-8 text-eyebrow tabular-nums text-fg-muted-inverse">0{{ $index + 1 }}</span>
                            <span class="font-display text-[clamp(2.75rem,7vw,6.5rem)] font-light leading-[0.95] tracking-[-0.02em] transition-transform duration-500 ease-expo group-hover:translate-x-4">{{ $item['label'] }}</span>
                            <x-phosphor-arrow-up-right-thin class="size-8 -translate-x-3 opacity-0 transition-all duration-500 ease-expo group-hover:translate-x-0 group-hover:opacity-100" aria-hidden="true" />
                        </a>
                    </li>
                @endforeach
            </ul>
        </nav>

        <div class="relative hidden overflow-hidden lg:col-span-4 lg:col-start-9 lg:block" data-menu-preview>
            @foreach (array_unique($menuImages) as $image)
                <img src="{{ asset('img/home/'.$image.'.webp') }}" alt="" data-menu-preview-image="{{ $image }}" loading="lazy" class="absolute inset-0 h-full w-full object-cover opacity-0">
            @endforeach
        </div>

        <div data-menu-foot class="flex flex-col justify-between gap-6 border-t border-border-inverse pt-6 text-sm text-fg-muted-inverse sm:flex-row sm:items-end lg:col-span-12">
            <p class="max-w-sm">Branding, packaging, pitch decks, social media, brochures, and UI/UX — all under one roof.</p>
            <a href="{{ route('contact') }}" class="link-line text-paper">Have a project in mind? Book a call <x-phosphor-arrow-up-right-thin class="size-4" aria-hidden="true" /></a>
        </div>
    </div>
</div>
