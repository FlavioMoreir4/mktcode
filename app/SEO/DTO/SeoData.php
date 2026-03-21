<?php

declare(strict_types=1);

namespace App\SEO\DTO;

class SeoData
{
    public function __construct(
        public string $title,
        public string $description,
        public string $image,
        public string $url,

        public string $type = 'website',

        public ?string $canonical = null,
        public ?string $publishedAt = null,
        public ?string $updatedAt = null,

        public ?string $author = null,
        public ?array $category = null,
        public ?array $tags = null,
        public ?array $keywords = null,

        public ?string $imageAlt = null,
        public ?array $breadcrumbs = null,

        public string $robots = 'index, follow',
        public string $locale = 'pt_BR',
    ) {}

    public function toArray(): array
    {
        return array_filter([
            'title' => $this->title,
            'description' => $this->description,
            'image' => $this->image,
            'url' => $this->url,
            'canonical' => $this->canonical ?? $this->url,

            'type' => $this->type,
            'publishedAt' => $this->publishedAt,
            'updatedAt' => $this->updatedAt,

            'author' => $this->author,
            'category' => $this->category,
            'tags' => $this->tags,
            'keywords' => $this->keywords,

            'imageAlt' => $this->imageAlt,
            'breadcrumbs' => $this->breadcrumbs,

            'robots' => $this->robots,
            'locale' => $this->locale,
        ]);
    }
}
