<?php

declare(strict_types=1);

namespace App\Application\Identity\Services;

use App\Application\Content\DTOs\PublicPostViewData;
use App\Application\Identity\DTOs\PublicProfileViewData;
use App\Application\Portfolio\DTOs\PublicProjectViewData;
use App\Infrastructure\Shared\Media\PublicMediaService;
use App\Models\User;

class PublicProfileFactory
{
    public function __construct(private readonly PublicMediaService $media) {}

    public function make(User $user): PublicProfileViewData
    {
        $media = $this->media->for($user);

        return new PublicProfileViewData(
            name: $user->name,
            username: $user->username ?? '',
            title: $user->title,
            bio: $user->bio,
            location: $user->location,
            avatar: $media->avatar ?? '',
            cover: $media->profileCover ?? '',
            social: $user->social_links,
            skills: $user->skills,
            projectsCount: $user->relationLoaded('projects') ? $user->projects->count() : 0,
            postsCount: $user->relationLoaded('posts') ? $user->posts->count() : 0,
            projects: $user->relationLoaded('projects')
                ? $user->projects->map(fn ($project) => PublicProjectViewData::fromModel($project)->toArray())->all()
                : [],
            posts: $user->relationLoaded('posts')
                ? $user->posts->map(fn ($post) => PublicPostViewData::summary($post)->toArray())->all()
                : [],
        );
    }
}
