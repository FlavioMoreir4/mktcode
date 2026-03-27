<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\Media\Builders;

use App\Application\Shared\DTOs\PublicMediaData;
use App\Domain\Shared\Contracts\PublicMediaBuilder;
use App\Models\User;
use InvalidArgumentException;

class UserMediaBuilder implements PublicMediaBuilder
{
    public function supports(object $resource): bool
    {
        return $resource instanceof User;
    }

    public function build(object $resource): PublicMediaData
    {
        if (! $resource instanceof User) {
            throw new InvalidArgumentException('UserMediaBuilder expects a User model.');
        }

        $avatar = $resource->getFirstMedia('profile_photo');
        $cover = $resource->getFirstMedia('cover_photo');

        return new PublicMediaData(
            avatar: $avatar ? ($avatar->hasGeneratedConversion('webp') ? $avatar->getUrl('webp') : $avatar->getUrl()) : '',
            profileCover: $cover ? ($cover->hasGeneratedConversion('webp') ? $cover->getUrl('webp') : $cover->getUrl()) : '',
        );
    }
}
