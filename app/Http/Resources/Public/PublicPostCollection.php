<?php

declare(strict_types=1);

namespace App\Http\Resources\Public;

use App\SEO\Builders\PageSeoBuilder;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

/**
 * @mixin \Illuminate\Pagination\LengthAwarePaginator<int, \App\Models\Post>
 */
class PublicPostCollection extends ResourceCollection
{
    /**
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => PublicPostResource::collection($this->collection),
        ];
    }

    public function with(Request $request): array
    {
        $seo = (new PageSeoBuilder)->build(
            route: 'public.blog.index',
            title: 'Blog da MC - Marketing & Code',
            description: 'Conteúdos sobre marketing digital, SEO, Laravel e desenvolvimento web.',
            keywords: ['blog marketing digital', 'artigos laravel', 'SEO blog']
        );

        return [
            'seo' => $seo,
        ];
    }
}
