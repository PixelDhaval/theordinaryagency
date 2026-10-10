<?php

namespace App\Models;

use App\Models\Concerns\HasSeo;
use Database\Factories\PostFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

#[Fillable([
    'category_id', 'title', 'slug', 'excerpt', 'body', 'cover_image', 'published_at',
    'meta_title', 'meta_description', 'og_image', 'canonical_url', 'noindex',
])]
class Post extends Model
{
    /** @use HasFactory<PostFactory> */
    use HasFactory, HasSeo;

    private const WORDS_PER_MINUTE = 200;

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
            'reading_time' => 'integer',
            'noindex' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (Post $post): void {
            $words = str_word_count(strip_tags((string) $post->body));
            $post->reading_time = max(1, (int) ceil($words / self::WORDS_PER_MINUTE));
        });
    }

    /**
     * @return BelongsTo<Category, $this>
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /** Live on the site: has a publish date that has already passed. */
    #[Scope]
    protected function published(Builder $query): void
    {
        $query->whereNotNull('published_at')->where('published_at', '<=', now());
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
                ->orWhereLike('excerpt', "%{$term}%")
                ->orWhereLike('body', "%{$term}%");
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
}
