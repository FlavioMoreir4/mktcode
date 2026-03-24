<?php

declare(strict_types=1);

namespace App\Application\Shared\DTOs;

use App\Application\Shared\Contracts\PublicPayloadData;

final readonly class PublicMediaData implements PublicPayloadData
{
    public function __construct(
        public ?array $cover = null,
        public array $gallery = [],
        public ?array $avatar = null,
        public ?array $profileCover = null,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return array_filter([
            'cover' => $this->cover,
            'gallery' => $this->gallery,
            'avatar' => $this->avatar,
            'profile_cover' => $this->profileCover,
        ], fn ($value) => $value !== null && $value !== []);
    }
}
