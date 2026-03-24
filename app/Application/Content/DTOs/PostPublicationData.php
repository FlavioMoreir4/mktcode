<?php

declare(strict_types=1);

namespace App\Application\Content\DTOs;

use App\Domain\Content\Enums\PostStatus;
use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;

final readonly class PostPublicationData
{
    public function __construct(
        public PostStatus $status,
        public ?CarbonImmutable $publishedAt,
    ) {}

    /**
     * @param  array{status: PostStatus|string, published_at?: CarbonInterface|string|null}  $attributes
     */
    public static function fromArray(array $attributes): self
    {
        $publishedAt = $attributes['published_at'] ?? null;

        if ($publishedAt instanceof CarbonInterface) {
            $publishedAt = CarbonImmutable::instance($publishedAt);
        } elseif (is_string($publishedAt) && $publishedAt !== '') {
            $publishedAt = CarbonImmutable::parse($publishedAt);
        } else {
            $publishedAt = null;
        }

        return new self(
            status: $attributes['status'] instanceof PostStatus
                ? $attributes['status']
                : PostStatus::from($attributes['status']),
            publishedAt: $publishedAt,
        );
    }
}
