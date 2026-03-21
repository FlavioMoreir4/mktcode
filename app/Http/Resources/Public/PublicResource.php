<?php

declare(strict_types=1);

namespace App\Http\Resources\Public;

use App\SEO\Contracts\HasSeo;
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
    protected function cover(string $collection = 'cover'): ?string
    {
        return $this->getFirstMediaUrl($collection) ?: null;
    }

    protected function seo(): ?array
    {
        if ($this->resource instanceof HasSeo) {
            return $this->resource->getSeo()->toArray();
        }

        return null;
    }
}
