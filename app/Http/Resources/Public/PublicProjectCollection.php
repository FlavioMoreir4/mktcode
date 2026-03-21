<?php

declare(strict_types=1);

namespace App\Http\Resources\Public;

use App\SEO\Services\SeoService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

/**
 * @mixin \Illuminate\Pagination\LengthAwarePaginator<int, \App\Models\Project>
 */
class PublicProjectCollection extends ResourceCollection
{
    /**
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => PublicProjectResource::collection($this->collection),
        ];
    }

    public function with(Request $request): array
    {
        return [
            'seo' => app(SeoService::class)->forPage(
                route: 'public.projects',
                title: 'Projetos',
            ),
        ];
    }
}
