<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ProjectStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Sitemap\Contracts\Sitemapable;
use Spatie\Sitemap\Tags\Url;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Spatie\Tags\HasTags;

class Project extends Model implements HasMedia, Sitemapable
{
    use HasSlug, HasTags, InteractsWithMedia, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'content',
        'client',
        'year',
        'status',
        'stack',
        'url',
        'featured',
        'sort_order',
        'seo_title',
        'seo_description',
    ];

    protected function casts(): array
    {
        return [
            'stack' => 'array',
            'featured' => 'boolean',
            'content' => 'array',
            'status' => ProjectStatus::class,
        ];
    }

    public function toSitemapTag(): Url|string|array
    {
        $url = Url::create(route('public.projects.show', $this->slug))
            ->setLastModificationDate($this->updated_at)
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
            ->setPriority(0.7);

        $cover = $this->getFirstMedia('cover');
        if ($cover) {
            $url->addImage($cover->getUrl(), $this->seo_title ?? $this->title);
        }

        return $url;
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', ProjectStatus::Published)->orderBy('sort_order');
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('created_at', 'desc');
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->published()->where('featured', true);
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('cover')->singleFile()->useDisk('public');
        $this->addMediaCollection('screenshots')->useDisk('public');
    }
}
