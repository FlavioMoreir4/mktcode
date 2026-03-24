<?php

declare(strict_types=1);

namespace App\Application\Portfolio\DTOs;

final readonly class ProjectListingData
{
    public function __construct(
        public ?int $ownerId,
        public bool $featured,
        public int $sortOrder,
    ) {}

    /**
     * @param  array{user_id?: int|string|null, featured?: bool, sort_order?: int|string|null}  $attributes
     */
    public static function fromArray(array $attributes): self
    {
        $ownerId = $attributes['user_id'] ?? null;

        return new self(
            ownerId: is_numeric($ownerId) ? (int) $ownerId : null,
            featured: (bool) ($attributes['featured'] ?? false),
            sortOrder: (int) ($attributes['sort_order'] ?? 0),
        );
    }
}
