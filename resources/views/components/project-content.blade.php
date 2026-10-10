@props([
    'blocks' => [],
])

@php
    $widthClasses = [
        'contained' => 'px-5 sm:px-10 [&>*]:mx-auto [&>*]:max-w-3xl',
        'wide' => 'px-5 sm:px-10',
        'full' => '',
    ];
@endphp

@if (! empty($blocks))
    <div {{ $attributes->class(['space-y-16 sm:space-y-24']) }}>
        @foreach ($blocks as $index => $block)
            @php
                $type = $block['type'] ?? null;
                $data = $block['data'] ?? [];
                $width = $type === 'markdown' ? 'contained' : ($data['width'] ?? 'wide');
            @endphp

            @if (in_array($type, ['markdown', 'image', 'video', 'html'], true))
                <div class="{{ $widthClasses[$width] ?? $widthClasses['wide'] }}">
                    @include('components.blocks.'.$type, ['data' => $data, 'blockId' => 'block-'.$index])
                </div>
            @endif
        @endforeach
    </div>
@endif
