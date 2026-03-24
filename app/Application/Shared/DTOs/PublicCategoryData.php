<?php

declare(strict_types=1);

namespace App\Application\Shared\DTOs;

use App\Application\Shared\Contracts\PublicPayloadData;
use App\Models\Category;

final readonly class PublicCategoryData implements PublicPayloadData
{
    public function __construct(
        public string $name,
        public string $slug,
    ) {}

    public static function fromModel(Category $category): self
    {
        return new self(
            name: $category->name,
            slug: $category->slug,
        );
    }

    /**
     * @return array{name: string, slug: string}
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'slug' => $this->slug,
        ];
    }
}
