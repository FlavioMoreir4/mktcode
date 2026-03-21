<?php

declare(strict_types=1);

namespace App\Http\Resources\Public;

use App\SEO\Builders\PageSeoBuilder;
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
        $seo = (new PageSeoBuilder)->build(
            route: 'public.projects',
            title: 'Portfólio de Projetos',
            description: 'Confira todos os projetos desenvolvidos pela MC - Marketing & Code.',
            keywords: ['portfólio web', 'projetos desenvolvidos', 'case study'],
        );

        return [
            'seo' => $seo,
        ];
    }
}
