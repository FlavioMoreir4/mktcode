<?php

declare(strict_types=1);

namespace App\Http\Resources\Public;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PublicProjectResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $image = $this->getFirstMediaUrl('cover');

        return [
            /*
            |--------------------------------------------------------------------------
            | Conteúdo principal
            |--------------------------------------------------------------------------
            */
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,

            'content' => $this->html,
            'markdown' => $this->markdown,
            'plain_text' => $this->plain_text,

            /*
            |--------------------------------------------------------------------------
            | Dados do projeto
            |--------------------------------------------------------------------------
            */
            'client' => $this->client,
            'year' => $this->year,
            'stack' => $this->stack,
            'url' => $this->url,
            'featured' => $this->featured,

            /*
            |--------------------------------------------------------------------------
            | SEO básico
            |--------------------------------------------------------------------------
            */
            'seo' => [
                'title' => $this->seo_title ?? $this->title,
                'description' => $this->seo_description ?? $this->description,
                'image' => $image,
                'url' => route('public.projects.show', $this->slug),
            ],

            /*
            |--------------------------------------------------------------------------
            | OpenGraph
            |--------------------------------------------------------------------------
            */
            'open_graph' => [
                'type' => 'article',
                'title' => $this->seo_title ?? $this->title,
                'description' => $this->seo_description ?? $this->description,
                'url' => route('public.projects.show', $this->slug),
                'image' => $image,
            ],

            /*
            |--------------------------------------------------------------------------
            | schema.org (JSON-LD)
            |--------------------------------------------------------------------------
            */
            'schema' => [
                '@context' => 'https://schema.org',
                '@type' => 'CreativeWork',
                'name' => $this->title,
                'description' => $this->description,
                'url' => route('public.projects.show', $this->slug),
                'image' => $image,
                'datePublished' => optional($this->created_at)->toIso8601String(),
                'dateModified' => optional($this->updated_at)->toIso8601String(),
            ],

            /*
            |--------------------------------------------------------------------------
            | Media
            |--------------------------------------------------------------------------
            */
            'media' => $this->getMedia()->map(fn ($media) => [
                'id' => $media->id,
                'collection' => $media->collection_name,
                'original_url' => $media->getUrl(),
                'thumb' => $media->getUrl('thumb'),
            ]),

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
            | Datas
            |--------------------------------------------------------------------------
            */
            'created_at' => optional($this->created_at)->toIso8601String(),
            'updated_at' => optional($this->updated_at)->toIso8601String(),
        ];
    }
}
