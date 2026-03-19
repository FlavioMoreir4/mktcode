<?php

declare(strict_types=1);

namespace App\Http\Resources\Public;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PublicPostIndexResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
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
            // 'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,

            /*
            |--------------------------------------------------------------------------
            | Conteúdo leve (apenas para reading time)
            |--------------------------------------------------------------------------
            */
            'body' => $this->plain_text,
            'reading_time' => $this->reading_time,

            /*
            |--------------------------------------------------------------------------
            | Excerpt
            |--------------------------------------------------------------------------
            */
            'excerpt' => $this->excerpt ?? str($this->plain_text)->limit(160),

            /*
            |--------------------------------------------------------------------------
            | Datas
            |--------------------------------------------------------------------------
            */
            'published_at' => optional($this->published_at)->toIso8601String(),

            /*
            |--------------------------------------------------------------------------
            | Autor
            |--------------------------------------------------------------------------
            */
            'author' => $this->whenLoaded('author', function () {
                return [
                    'name' => $this->author->name,
                    'profile_photo_url' => $this->author->profile_photo_url,
                ];
            }),

            /*
            |--------------------------------------------------------------------------
            | Categoria
            |--------------------------------------------------------------------------
            */
            'category' => $this->whenLoaded('category', function () {
                return [
                    'name' => $this->category->name,
                    'slug' => $this->category->slug,
                ];
            }),

            /*
            |--------------------------------------------------------------------------
            | Tags (somente nome + slug)
            |--------------------------------------------------------------------------
            */
            'tags' => $this->whenLoaded('tags', function () {
                return $this->tags->map(fn ($tag) => [
                    'id' => $tag->id,
                    'name' => $tag->name,
                    'slug' => $tag->slug,
                ]);
            }),

            /*
            |--------------------------------------------------------------------------
            | Media (imagem principal)
            |--------------------------------------------------------------------------
            */
            'media' => $this->getMedia('cover')->map(fn ($media) => [
                'original_url' => $media->getUrl(),
            ]),
        ];
    }
}
