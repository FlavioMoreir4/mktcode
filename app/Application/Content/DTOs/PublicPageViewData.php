<?php

declare(strict_types=1);

namespace App\Application\Content\DTOs;

use App\Application\Shared\Contracts\PublicPayloadData;
use App\Models\Page;

final readonly class PublicPageViewData implements PublicPayloadData
{
    public function __construct(
        public string $title,
        public string $slug,
        public ?string $excerpt,
        public ?string $publishedAt,
        public int $wordCount,
        public int $readingTime,
        public ?string $body = null,
        public ?string $markdown = null,
        public ?string $plainText = null,
        public ?string $createdAt = null,
        public ?string $updatedAt = null,
    ) {}

    public static function detail(Page $page): self
    {
        return new self(
            title: $page->title,
            slug: $page->slug,
            excerpt: $page->excerpt,
            publishedAt: $page->published_at?->toIso8601String(),
            wordCount: $page->word_count,
            readingTime: $page->reading_time,
            body: $page->html,
            markdown: $page->markdown,
            plainText: $page->plain_text,
            createdAt: $page->created_at?->toIso8601String(),
            updatedAt: $page->updated_at?->toIso8601String(),
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
            'body' => $this->body,
            'markdown' => $this->markdown,
            'plain_text' => $this->plainText,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt,
        ], fn (mixed $value): bool => $value !== null);
    }
}
