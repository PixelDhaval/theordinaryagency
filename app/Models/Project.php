<?php

namespace App\Models;

use App\Models\Concerns\HasSeo;
use Database\Factories\ProjectFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

#[Fillable([
    'title', 'slug', 'client', 'discipline', 'services', 'year', 'summary', 'content', 'cover_image', 'gallery',
    'is_featured', 'sort_order', 'published_at',
    'meta_title', 'meta_description', 'og_image', 'canonical_url', 'noindex',
])]
class Project extends Model
{
    /** @use HasFactory<ProjectFactory> */
    use HasFactory, HasSeo;

    protected function casts(): array
    {
        return [
            'services' => 'array',
            'content' => 'array',
            'gallery' => 'array',
            'year' => 'integer',
            'is_featured' => 'boolean',
            'sort_order' => 'integer',
            'published_at' => 'datetime',
            'noindex' => 'boolean',
        ];
    }

    /** Live on the site: has a publish date that has already passed. */
    #[Scope]
    protected function published(Builder $query): void
    {
        $query->whereNotNull('published_at')->where('published_at', '<=', now());
    }

    /** Featured first, then the manual sort order, then newest. */
    #[Scope]
    protected function curated(Builder $query): void
    {
        $query->orderByDesc('is_featured')->orderBy('sort_order')->latest('published_at');
    }

    #[Scope]
    protected function search(Builder $query, ?string $term): void
    {
        $term = trim((string) $term);

        if ($term === '') {
            return;
        }

        $query->where(function (Builder $query) use ($term): void {
            $query->whereLike('title', "%{$term}%")
                ->orWhereLike('client', "%{$term}%")
                ->orWhereLike('summary', "%{$term}%");
        });
    }

    public function isPublished(): bool
    {
        return $this->published_at !== null && $this->published_at->isPast();
    }

    /**
     * @return Attribute<string|null, never>
     */
    protected function coverUrl(): Attribute
    {
        return Attribute::get(fn (): ?string => $this->cover_image ? Storage::disk('public')->url($this->cover_image) : null);
    }

    /**
     * @return Attribute<list<string>, never>
     */
    protected function galleryUrls(): Attribute
    {
        return Attribute::get(fn (): array => collect($this->gallery ?? [])
            ->map(fn (string $path): string => Storage::disk('public')->url($path))
            ->values()
            ->all());
    }
}
