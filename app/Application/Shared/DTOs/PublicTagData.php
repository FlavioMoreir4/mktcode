<?php

declare(strict_types=1);

namespace App\Application\Shared\DTOs;

use App\Application\Shared\Contracts\PublicPayloadData;
use Spatie\Tags\Tag;

final readonly class PublicTagData implements PublicPayloadData
{
    public function __construct(
        public string $name,
        public string $slug,
    ) {}

    public static function fromModel(Tag $tag): self
    {
        return new self(
            name: $tag->name,
            slug: $tag->slug,
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
