<?php

declare(strict_types=1);

namespace App\Application\Identity\DTOs;

use App\Application\Content\DTOs\PublicPostViewData;
use App\Application\Portfolio\DTOs\PublicProjectViewData;
use App\Application\Shared\Contracts\PublicPayloadData;
use App\Models\User;

final readonly class PublicProfileViewData implements PublicPayloadData
{
    /**
     * @param  list<array<string, mixed>>  $projects
     * @param  list<array<string, mixed>>  $posts
     * @param  array<string, mixed>|null  $social
     */
    public function __construct(
        public string $name,
        public string $username,
        public ?string $title,
        public ?string $bio,
        public ?string $location,
        public string $avatar,
        public string $cover,
        public ?array $social,
        public array $projects,
        public array $posts,
    ) {}

    public static function fromModel(User $user): self
    {
        return new self(
            name: $user->name,
            username: $user->username ?? '',
            title: $user->title,
            bio: $user->bio,
            location: $user->location,
            avatar: $user->profile_photo_url,
            cover: $user->cover_photo_url,
            social: $user->social_links,
            projects: $user->relationLoaded('projects')
                ? $user->projects->map(fn ($project): array => PublicProjectViewData::fromModel($project)->toArray())->all()
                : [],
            posts: $user->relationLoaded('posts')
                ? $user->posts->map(fn ($post): array => PublicPostViewData::summary($post)->toArray())->all()
                : [],
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'username' => $this->username,
            'title' => $this->title,
            'bio' => $this->bio,
            'location' => $this->location,
            'avatar' => $this->avatar,
            'cover' => $this->cover,
            'social' => $this->social,
            'projects' => [
                'data' => $this->projects,
            ],
            'posts' => [
                'data' => $this->posts,
            ],
        ];
    }
}
