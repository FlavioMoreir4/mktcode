<?php

declare(strict_types=1);

namespace App\Http\Resources\Public;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\Project
 * @mixin \App\Models\Post
 * @mixin \App\Models\User
 *
 * @property-read \App\Models\User $author
 */
abstract class PublicResource extends JsonResource
{
    protected function cover(?string $collection = 'cover'): ?string
    {
        return $this->getFirstMediaUrl($collection) ?: null;
    }

    /**
     * @return array<string, mixed>
     */
    protected function seo(?Request $request = null): array
    {
        return [
            'title' => $this->seo_title ?? $this->title ?? $this->name ?? null,
            'description' => $this->seo_description ?? $this->excerpt ?? $this->description ?? null,
            'image' => $this->cover(),
            'keywords' => $this->seo_keywords ?? $this->keywords ?? null,
            'author' => $this->whenLoaded('author', fn () => $this->author->name) ?? null,
            'url' => $this->url ?? $request?->url() ?? null,
            'type' => $this->type ?? null,
        ];
    }
}
