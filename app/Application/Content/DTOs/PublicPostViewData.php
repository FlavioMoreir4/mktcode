<?php

declare(strict_types=1);

namespace App\Application\Content\DTOs;

use App\Application\Shared\Contracts\PublicPayloadData;
use App\Application\Shared\DTOs\PublicAuthorData;
use App\Application\Shared\DTOs\PublicCategoryData;
use App\Application\Shared\DTOs\PublicTagData;
use App\Models\Post;

final readonly class PublicPostViewData implements PublicPayloadData
{
    /**
     * @param  list<array{name: string, slug: string}>  $tags
     */
    public function __construct(
        public string $title,
        public string $slug,
        public string $excerpt,
        public ?string $publishedAt,
        public int $wordCount,
        public int $readingTime,
        // public ?string $cover,
        public ?array $media,
        public ?array $author,
        public ?array $category,
        public array $tags,
        public ?string $body = null,
        public ?string $markdown = null,
        public ?string $plainText = null,
        public ?string $createdAt = null,
        public ?string $updatedAt = null,
    ) {}

    public static function summary(Post $post, ?array $media = null): self
    {
        return new self(
            title: $post->title,
            slug: $post->slug,
            excerpt: $post->excerpt ?? $post->plain_text,
            publishedAt: $post->published_at?->toIso8601String(),
            wordCount: $post->word_count,
            readingTime: $post->reading_time,
            // cover: $post->getFirstMediaUrl('cover') ?: null,
            media: $media,
            author: $post->relationLoaded('author') && $post->author
                ? PublicAuthorData::summary($post->author)->toArray()
                : null,
            category: $post->relationLoaded('category') && $post->category
                ? PublicCategoryData::fromModel($post->category)->toArray()
                : null,
            tags: $post->relationLoaded('tags')
                ? $post->tags->map(fn ($tag): array => PublicTagData::fromModel($tag)->toArray())->all()
                : [],
        );
    }

    public static function detail(Post $post, ?array $media = null): self
    {
        return new self(
            title: $post->title,
            slug: $post->slug,
            excerpt: $post->excerpt ?? $post->plain_text,
            publishedAt: $post->published_at?->toIso8601String(),
            wordCount: $post->word_count,
            readingTime: $post->reading_time,
            // cover: $post->getFirstMediaUrl('cover') ?: null,
            media: $media,
            author: $post->relationLoaded('author') && $post->author
                ? PublicAuthorData::detail($post->author)->toArray()
                : null,
            category: $post->relationLoaded('category') && $post->category
                ? PublicCategoryData::fromModel($post->category)->toArray()
                : null,
            tags: $post->relationLoaded('tags')
                ? $post->tags->map(fn ($tag): array => PublicTagData::fromModel($tag)->toArray())->all()
                : [],
            body: $post->html,
            markdown: $post->markdown,
            plainText: $post->plain_text,
            createdAt: $post->created_at?->toIso8601String(),
            updatedAt: $post->updated_at?->toIso8601String(),
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return array_filter([
            'title' => $this->title,
            'slug' => $this->slug,
            'excerpt' => $this->excerpt,
            'published_at' => $this->publishedAt,
            'word_count' => $this->wordCount,
            'reading_time' => $this->readingTime,
            // 'cover' => $this->cover,
            'media' => $this->media,
            'author' => $this->author,
            'category' => $this->category,
            'tags' => $this->tags,
            'body' => $this->body,
            'markdown' => $this->markdown,
            'plain_text' => $this->plainText,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt,
        ], fn (mixed $value): bool => $value !== null);
    }
}
