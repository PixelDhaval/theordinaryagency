<header id="site-header" class="pointer-events-none fixed inset-x-0 top-0 z-[120] text-white mix-blend-difference">
    <div class="flex items-center justify-between px-5 py-5 sm:px-10 sm:py-7">

        <a href="{{ route('home') }}" class="pointer-events-auto block" aria-label="The Ordinary Agency — home" data-cursor="Home">
            <img src="{{ asset('img/logo/logo-dark.png') }}" alt="The Ordinary Agency" width="1223" height="329" class="h-8 w-auto sm:h-9">
        </a>

        <nav class="pointer-events-auto hidden items-center gap-9 lg:flex" aria-label="Primary">
            @foreach (array_slice($navigation, 0, 5) as $item)
                <a href="{{ route($item['route']) }}" class="roll text-sm" @if (request()->routeIs($item['route'], $item['route'].'.*')) aria-current="page" @endif>
                    <span>{{ $item['label'] }}</span>
                    <span aria-hidden="true">{{ $item['label'] }}</span>
                </a>
            @endforeach
        </nav>

        <div class="pointer-events-auto flex items-center gap-6">
            <a href="{{ route('contact') }}" class="group hidden items-center gap-2 rounded-full border border-white/60 py-2.5 pl-5 pr-4 text-sm transition-colors duration-500 ease-expo hover:bg-white hover:text-black md:inline-flex">
                <span class="roll"><span>Book a call</span><span aria-hidden="true">Book a call</span></span>
                <x-phosphor-arrow-up-right-thin class="size-4 transition-transform duration-500 ease-expo group-hover:rotate-45" aria-hidden="true" />
            </a>

            <button id="menu-toggle" type="button" class="group flex items-center gap-3 text-sm" aria-expanded="false" aria-controls="site-menu" data-cursor="Menu">
                <span class="roll">
                    <span data-menu-label>Menu</span>
                    <span aria-hidden="true" data-menu-label-alt>Menu</span>
                </span>
                <span class="relative block h-3 w-7" aria-hidden="true">
                    <span data-menu-line="1" class="absolute left-0 top-0.5 h-px w-full bg-white"></span>
                    <span data-menu-line="2" class="absolute bottom-0.5 left-0 h-px w-full bg-white"></span>
                </span>
            </button>
        </div>
    </div>
</header>
