<?php

declare(strict_types=1);

namespace App\Models;

use App\Domain\Content\Enums\PageStatus;
use App\Filament\Resources\Concerns\HasRichEditorRendering;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Page extends Model
{
    use HasRichEditorRendering;
    use HasSlug, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'body',
        'excerpt',
        'status',
        'published_at',
        'seo_title',
        'seo_description',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
            'body' => 'array',
            'status' => PageStatus::class,
        ];
    }

    protected function getEditorContent(): string|array|null
    {
        return $this->body;
    }

    protected function getRichContentField(): string
    {
        return 'body';
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Builder<self>  $query
     */
    public function scopePublished(\Illuminate\Database\Eloquent\Builder $query): \Illuminate\Database\Eloquent\Builder
    {
        return $query
            ->where('status', PageStatus::Published)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }
}
