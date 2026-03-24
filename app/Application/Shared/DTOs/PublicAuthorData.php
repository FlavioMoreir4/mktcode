<?php

declare(strict_types=1);

namespace App\Application\Shared\DTOs;

use App\Application\Shared\Contracts\PublicPayloadData;
use App\Models\User;

final readonly class PublicAuthorData implements PublicPayloadData
{
    public function __construct(
        public string $name,
        public string $username,
        public ?string $title,
        public string $avatarUrl,
        public ?string $profileUrl,
    ) {}

    public static function summary(User $user): self
    {
        return new self(
            name: $user->name,
            username: $user->username ?? '',
            title: $user->title,
            avatarUrl: $user->profile_photo_url,
            profileUrl: $user->username ? route('public.user.show', $user->username) : null,
        );
    }

    public static function detail(User $user): self
    {
        return self::summary($user);
    }

    /**
     * @return array{name: string, username: string, title: string|null, avatar_url: string, profile_url: string|null}
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'username' => $this->username,
            'title' => $this->title,
            'avatar_url' => $this->avatarUrl,
            'profile_url' => $this->profileUrl,
        ];
    }
}
