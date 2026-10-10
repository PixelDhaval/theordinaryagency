@if (! empty($data['image']))
    <figure>
        <div data-clip-reveal class="relative overflow-hidden bg-ink/5">
            <img src="{{ Storage::disk('public')->url($data['image']) }}"
                 alt="{{ $data['alt'] ?? '' }}"
                 loading="lazy"
                 decoding="async"
                 class="h-auto w-full">
        </div>
        @if (! empty($data['caption']))
            <figcaption class="mt-4 text-sm text-fg-muted">{{ $data['caption'] }}</figcaption>
        @endif
    </figure>
@endif
