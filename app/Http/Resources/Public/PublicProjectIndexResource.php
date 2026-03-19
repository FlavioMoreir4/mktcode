<?php

declare(strict_types=1);

namespace App\Http\Resources\Public;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PublicProjectIndexResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            /*
            |--------------------------------------------------------------------------
            | Básico
            |--------------------------------------------------------------------------
            */
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,

            /*
            |--------------------------------------------------------------------------
            | Informações do projeto
            |--------------------------------------------------------------------------
            */
            'client' => $this->client,
            'year' => $this->year,
            'stack' => $this->stack,
            'featured' => $this->featured,

            /*
            |--------------------------------------------------------------------------
            | Media (apenas capa)
            |--------------------------------------------------------------------------
            */
            'media' => $this->getMedia('cover')->map(fn ($media) => [
                'original_url' => $media->getUrl(),
            ]),
        ];
    }
}
