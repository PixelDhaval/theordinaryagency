@if (! empty($data['video']))
    @php
        $isLoop = ($data['playback'] ?? 'loop') === 'loop';
        $mime = str_ends_with(strtolower($data['video']), '.webm') ? 'video/webm' : 'video/mp4';
    @endphp
    <figure>
        <div class="relative overflow-hidden bg-ink">
            <video
                class="h-auto w-full"
                playsinline
                preload="metadata"
                @if (! empty($data['poster'])) poster="{{ Storage::disk('public')->url($data['poster']) }}" @endif
                @if ($isLoop) autoplay muted loop data-autoplay-video aria-hidden="true" @else controls @endif
            >
                <source src="{{ Storage::disk('public')->url($data['video']) }}" type="{{ $mime }}">
            </video>
        </div>
        @if (! empty($data['caption']))
            <figcaption class="mt-4 text-sm text-fg-muted">{{ $data['caption'] }}</figcaption>
        @endif
    </figure>
@endif
