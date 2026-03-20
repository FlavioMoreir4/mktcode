<?php

declare(strict_types=1);

namespace App\Http\Resources\Public;

use Illuminate\Http\Request;

/** @mixin \App\Models\Project */
class PublicProjectResource extends PublicResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            /*
            |--------------------------------------------------------------------------
            | Básico
            |--------------------------------------------------------------------------
            */
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'content' => $this->content,

            /*
            |--------------------------------------------------------------------------
            | Informações do projeto
            |--------------------------------------------------------------------------
            */
            'client' => $this->client,
            'year' => $this->year,
            'stack' => $this->stack,
            'url' => $this->url,
            'featured' => $this->featured,

            /*
            |--------------------------------------------------------------------------
            | Media (apenas capa)
            |--------------------------------------------------------------------------
            */
            'cover' => $this->cover(),
            'gallery' => $this->getMedia('screenshots')->map->getUrl(),

            /*
            |--------------------------------------------------------------------------
            | SEO
            |--------------------------------------------------------------------------
            */
            'seo' => [
                ...$this->seo($request),
                'type' => 'project',
            ],
        ];
    }
}
