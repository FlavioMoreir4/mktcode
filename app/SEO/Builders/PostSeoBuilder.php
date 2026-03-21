<?php

declare(strict_types=1);

namespace App\SEO\Builders;

use App\Models\Post;
use App\SEO\DTO\SeoData;
use App\Settings\GeneralSettings;
use Spatie\Tags\Tag;

class PostSeoBuilder
{
    public function __construct(protected GeneralSettings $settings) {}

    public function build(Post $post): SeoData
    {
        $url = route('public.blog.show', $post->slug);

        $coverUrl = $post->getFirstMediaUrl('cover');
        $image = ! blank($coverUrl) ? $coverUrl : $this->settings->ogImageUrl();

        $keywords = $post->relationLoaded('tags')
            ? $post->tags->pluck('name')->toArray()
            : $this->settings->parsedKeywords();

        return new SeoData(
            title: $post->seo_title ?? $post->title,

            description: $post->seo_description
                ?? $post->excerpt
                ?? str($post->plain_text)->limit(155)->toString()
                ?? $this->settings->site_description,

            image: $image,
            imageAlt: $post->title,

            url: $url,
            canonical: $url,

            type: 'article',

            publishedAt: $post->published_at?->toIso8601String(),
            updatedAt: $post->updated_at?->toIso8601String(),

            author: $post->relationLoaded('author')
                ? $post->author->name
                : $this->settings->site_author,

            category: $post->relationLoaded('category') && $post->category
                ? ['name' => $post->category->name, 'slug' => $post->category->slug]
                : null,

            tags: $post->relationLoaded('tags')
                ? $post->tags->map(fn (Tag $tag): array => [
                    'name' => $tag->name,
                    'slug' => $tag->slug,
                ])->toArray()
                : null,

            keywords: $keywords,

            locale: $this->settings->site_locale,

            breadcrumbs: [
                ['name' => 'Home', 'url' => route('home')],
                ['name' => 'Blog', 'url' => route('public.blog.index')],
                ['name' => $post->title, 'url' => $url],
            ],
        );
    }
}
