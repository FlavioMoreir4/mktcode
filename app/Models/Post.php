<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\PostStatus;
use App\Filament\Resources\Concerns\HasRichEditorRendering;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Sitemap\Contracts\Sitemapable;
use Spatie\Sitemap\Tags\Url;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Spatie\Tags\HasTags;
use Spatie\Tags\Tag;

/**
 * App\Models\Post
 *
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property array<string, mixed>|null $body
 * @property string|null $body_markdown
 * @property string|null $content_format
 * @property string|null $excerpt
 * @property PostStatus $status
 * @property \Carbon\Carbon|null $published_at
 * @property int|null $author_id
 * @property int|null $category_id
 * @property string|null $seo_title
 * @property string|null $seo_description
 * @property string|null $seo_keywords
 * @property string|null $keywords
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property \Carbon\Carbon|null $deleted_at
 * @property-read User $author
 * @property-read Category $category
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Tag> $tags
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Post public()
 * @method static \Illuminate\Database\Eloquent\Builder|Post published()
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereStatus(PostStatus $status)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereSlug(string $slug)
 *
 * @mixin \Eloquent
 */
class Post extends Model implements HasMedia, Sitemapable
{
    use HasRichEditorRendering;
    use HasSlug, HasTags, InteractsWithMedia, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'body',
        'body_markdown',
        'content_format',
        'excerpt',
        'status',
        'published_at',
        'author_id',
        'category_id',
        'seo_title',
        'seo_description',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
            'body' => 'array',
            'status' => PostStatus::class,
        ];
    }

    protected function getEditorContent(): string|array|null
    {
        return $this->body;
    }

    public function toSitemapTag(): Url|string|array
    {
        $url = Url::create(route('public.blog.show', $this->slug))
            ->setLastModificationDate($this->updated_at)
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
            ->setPriority(0.8);

        $cover = $this->getFirstMedia('cover');
        if ($cover) {
            $url->addImage($cover->getUrl(), $this->seo_title ?? $this->title);
        }

        return $url;
    }

    public function scopePublic(Builder $query): Builder
    {
        return $query
            ->where('status', PostStatus::Published)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', CarbonImmutable::now()->format('Y-m-d H:i:s'));
    }

    public function scopePublished(Builder $query): Builder
    {
        return $this->scopePublic($query);
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('cover')->singleFile()->useDisk('public');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }
}
