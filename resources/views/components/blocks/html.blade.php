@php
    use App\Support\HtmlEmbed;

    $isInline = ($data['mode'] ?? HtmlEmbed::MODE_ISOLATED) === HtmlEmbed::MODE_INLINE;
    $fixedHeight = ! empty($data['height']) ? (int) $data['height'] : null;
@endphp

@if ($isInline)
    {{-- Trusted admin content, rendered as-is. CSS is confined to this block with @scope. --}}
    @php($code = HtmlEmbed::resolve($data))
    <div data-embed="{{ $blockId }}">
        @if ($code['css'] !== '')
            <style>{!! HtmlEmbed::scopedCss($code['css'], $blockId) !!}</style>
        @endif
        {!! $code['html'] !!}
        @if ($code['js'] !== '')
            <script>{!! $code['js'] !!}</script>
        @endif
    </div>
@else
    {{-- No allow-same-origin: the frame gets an opaque origin, so its scripts cannot reach this page, its cookies or storage. --}}
    <iframe
        title="Interactive case study content"
        srcdoc="{{ HtmlEmbed::document($data) }}"
        sandbox="allow-scripts allow-popups allow-popups-to-escape-sandbox allow-forms"
        loading="lazy"
        @if ($fixedHeight) style="height: {{ $fixedHeight }}px" @else data-embed-frame style="height: 480px" @endif
        class="block w-full border-0 bg-transparent"
    ></iframe>
@endif
