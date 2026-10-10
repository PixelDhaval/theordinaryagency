<?php

namespace App\Models\Concerns;

use Illuminate\Support\Facades\Storage;

/**
 * Shared SEO overrides (meta_title, meta_description, og_image, canonical_url, noindex)
 * that fall back to the record's own content when left empty in the admin.
 */
trait HasSeo
{
    public function seoTitle(string $fallback): string
    {
        return $this->meta_title ?: $fallback;
    }

    public function seoDescription(?string $fallback = null): ?string
    {
        return $this->meta_description ?: $fallback;
    }

    public function seoImageUrl(): ?string
    {
        $path = $this->og_image ?: $this->cover_image;

        return $path ? Storage::disk('public')->url($path) : null;
    }

    public function seoCanonical(string $fallback): string
    {
        return $this->canonical_url ?: $fallback;
    }

    public function seoRobots(): ?string
    {
        return $this->noindex ? 'noindex, follow' : null;
    }
}
