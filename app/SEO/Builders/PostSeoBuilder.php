<?php

declare(strict_types=1);

namespace App\SEO\Builders;

use App\Models\Post;
use App\SEO\DTO\SeoData;
use Spatie\Tags\Tag;

class PostSeoBuilder
{
    public function build(Post $post): SeoData
    {
        $url = route('public.blog.show', $post->slug);

        return new SeoData(
            /*
            |--------------------------------------------------------------------------
            | SEO básico
            |--------------------------------------------------------------------------
            */
            title: $post->seo_title ?? $post->title,
            description: $post->seo_description
                ?? $post->excerpt
                ?? str($post->plain_text)->limit(155),

            /*
            |--------------------------------------------------------------------------
            | OpenGraph / Social
            |--------------------------------------------------------------------------
            */
            image: $post->getFirstMediaUrl('cover'),
            imageAlt: $post->title,

            /*
            |--------------------------------------------------------------------------
            | URLs
            |--------------------------------------------------------------------------
            */
            url: $url,
            canonical: $url,

            /*
            |--------------------------------------------------------------------------
            | Tipo de página
            |--------------------------------------------------------------------------
            */
            type: 'article',

            /*
            |--------------------------------------------------------------------------
            | Datas
            |--------------------------------------------------------------------------
            */
            publishedAt: $post->published_at?->toIso8601String(),
            updatedAt: $post->updated_at?->toIso8601String(),

            /*
            |--------------------------------------------------------------------------
            | Autor
            |--------------------------------------------------------------------------
            */
            author: $post->relationLoaded('author')
                ? $post->author->name
                : null,

            /*
            |--------------------------------------------------------------------------
            | Categoria
            |--------------------------------------------------------------------------
            */
            category: $post->relationLoaded('category') && $post->category
                ? [
                    'name' => $post->category->name,
                    'slug' => $post->category->slug,
                ]
                : null,

            /*
            |--------------------------------------------------------------------------
            | Tags
            |--------------------------------------------------------------------------
            */
            tags: $post->relationLoaded('tags')
                ? $post->tags->map(fn (Tag $tag) => [
                    'name' => $tag->name,
                    'slug' => $tag->slug,
                ])->toArray()
                : null,

            /*
            |--------------------------------------------------------------------------
            | Keywords
            |--------------------------------------------------------------------------
            */
            keywords: $post->relationLoaded('tags')
                ? $post->tags->pluck('name')->toArray()
                : null,

            /*
            |--------------------------------------------------------------------------
            | Breadcrumbs SEO
            |--------------------------------------------------------------------------
            */
            breadcrumbs: [
                [
                    'name' => 'Home',
                    'url' => route('home'),
                ],
                [
                    'name' => 'Blog',
                    'url' => route('public.blog.index'),
                ],
                [
                    'name' => $post->title,
                    'url' => $url,
                ],
            ],
        );
    }
}
