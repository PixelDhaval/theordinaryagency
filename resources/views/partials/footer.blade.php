@php
    $footerServices = ['Branding', 'Packaging Design', 'Pitch Deck Design', 'Social Media Design', 'Brochure Design', 'UI/UX Design'];
@endphp
<footer class="relative overflow-hidden bg-ink text-paper" data-footer>
    <div class="px-5 pt-24 sm:px-10 sm:pt-32">
        <div class="grid grid-cols-2 gap-x-6 gap-y-12 md:grid-cols-12">
            <div class="col-span-2 md:col-span-5">
                <p class="text-eyebrow uppercase tracking-[0.25em] text-fg-muted-inverse">Start a project</p>
                <a href="{{ route('contact') }}" class="group mt-5 inline-flex items-end gap-4 font-display text-h1 font-light leading-none" data-cursor="Let's talk">
                    <span class="link-line">Book a call</span>
                    <x-phosphor-arrow-up-right-thin class="size-[0.8em] transition-transform duration-700 ease-expo group-hover:rotate-45" aria-hidden="true" />
                </a>
            </div>

            <nav class="md:col-span-2 md:col-start-8" aria-label="Footer">
                <p class="text-eyebrow uppercase tracking-[0.25em] text-fg-muted-inverse">Sitemap</p>
                <ul class="mt-5 space-y-2.5 text-sm">
                    <li><a href="{{ route('home') }}" class="link-line">Home</a></li>
                    @foreach ($navigation as $item)
                        <li><a href="{{ route($item['route']) }}" class="link-line">{{ $item['label'] }}</a></li>
                    @endforeach
                </ul>
            </nav>

            <div class="md:col-span-3">
                <p class="text-eyebrow uppercase tracking-[0.25em] text-fg-muted-inverse">Services</p>
                <ul class="mt-5 space-y-2.5 text-sm">
                    @foreach ($footerServices as $service)
                        <li><a href="{{ route('services') }}" class="link-line">{{ $service }}</a></li>
                    @endforeach
                </ul>
            </div>
        </div>

        <div class="mt-24 flex flex-col gap-4 border-t border-border-inverse py-6 text-xs text-fg-muted-inverse sm:flex-row sm:items-center sm:justify-between">
            <p>&copy; {{ now()->year }} The Ordinary Agency. All rights reserved.</p>
            <button type="button" data-scroll-top class="group inline-flex items-center gap-2 self-start sm:self-auto">
                <span class="roll"><span>Back to top</span><span aria-hidden="true">Back to top</span></span>
                <x-phosphor-arrow-up-thin class="size-4" aria-hidden="true" />
            </button>
        </div>
    </div>

    <div class="overflow-clip px-5 pb-6 sm:px-10 sm:pb-10">
        <img data-footer-wordmark src="{{ asset('img/logo/logo-dark.png') }}" alt="The Ordinary Agency" width="1223" height="329" loading="lazy" class="block h-auto w-full">
    </div>
</footer>
