<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\Media\Builders;

use App\Application\Shared\DTOs\PublicMediaData;
use App\Domain\Shared\Contracts\PublicMediaBuilder;
use App\Models\Post;
use InvalidArgumentException;

class PostMediaBuilder implements PublicMediaBuilder
{
    public function supports(object $resource): bool
    {
        return $resource instanceof Post;
    }

    public function build(object $resource): PublicMediaData
    {
        if (! $resource instanceof Post) {
            throw new InvalidArgumentException('PostMediaBuilder expects a Post model.');
        }

        if (! $resource->relationLoaded('media')) {
            return new PublicMediaData;
        }

        $cover = $resource->getFirstMedia('cover');

        return new PublicMediaData(
            cover: $cover ? ['url' => $cover->getUrl()] : null,
        );
    }
}
