<?php

declare(strict_types=1);

namespace App\Models;

use App\Domain\Portfolio\Enums\ProjectStatus;
use App\Filament\Resources\Concerns\HasRichEditorRendering;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Spatie\Tags\HasTags;

class Project extends Model implements HasMedia
{
    use HasRichEditorRendering;
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
        'user_id',
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

    protected function getEditorContent(): string|array|null
    {
        return $this->content;
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

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
