<?php

declare(strict_types=1);

namespace App\Http\Resources\Public;

use App\SEO\Contracts\HasSeo;
use App\SEO\SeoResolver;
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
    public function with(Request $request): array
    {
        return [
            'seo' => $this->seo(),
        ];
    }

    protected function cover(string $collection = 'cover'): ?string
    {
        return $this->getFirstMediaUrl($collection) ?: null;
    }

    protected function seo(): ?array
    {
        if ($this->resource instanceof HasSeo) {
            return app(SeoResolver::class)->resolve($this->resource)->toArray();
        }

        return null;
    }
}
