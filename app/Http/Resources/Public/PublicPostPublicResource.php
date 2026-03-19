<?php

declare(strict_types=1);

namespace App\Http\Resources\Public;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PublicPostPublicResource extends JsonResource
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
            | Dados principais
            |--------------------------------------------------------------------------
            */
            'title' => $this->title,
            'slug' => $this->slug,

            /*
            |--------------------------------------------------------------------------
            | Conteúdo renderizado
            |--------------------------------------------------------------------------
            */
            'body' => $this->html,         // HTML pronto para o Vue
            'markdown' => $this->markdown, // opcional (editor, API, etc.)
            'plain_text' => $this->plain_text,

            /*
            |--------------------------------------------------------------------------
            | SEO
            |--------------------------------------------------------------------------
            */
            'seo_title' => $this->seo_title,
            'seo_description' => $this->seo_description,
            'excerpt' => $this->excerpt ?? $this->plain_text,

            /*
            |--------------------------------------------------------------------------
            | Métricas do conteúdo
            |--------------------------------------------------------------------------
            */
            'word_count' => $this->word_count,
            'reading_time' => $this->reading_time,

            /*
            |--------------------------------------------------------------------------
            | Datas
            |--------------------------------------------------------------------------
            */
            'published_at' => optional($this->published_at)->toIso8601String(),
            'created_at' => optional($this->created_at)->toIso8601String(),
            'updated_at' => optional($this->updated_at)->toIso8601String(),

            /*
            |--------------------------------------------------------------------------
            | Autor
            |--------------------------------------------------------------------------
            */
            'author' => $this->whenLoaded('author', function () {
                return [
                    'name' => $this->author->name,
                    'username' => $this->author->username,
                    'profile_photo_url' => $this->author->profile_photo_url,
                    'location' => $this->author->location,
                    'social_links' => $this->author->social_links,
                ];
            }),

            /*
            |--------------------------------------------------------------------------
            | Categoria
            |--------------------------------------------------------------------------
            */
            'category' => $this->whenLoaded('category', function () {
                return [
                    'id' => $this->category->id,
                    'name' => $this->category->name,
                    'slug' => $this->category->slug,
                ];
            }),

            /*
            |--------------------------------------------------------------------------
            | Tags
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
                'id' => $media->id,
                'original_url' => $media->getUrl(),
                // 'thumb' => $media->getUrl('thumb'),
            ]),
        ];
    }
}
