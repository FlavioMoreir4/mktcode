<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\Media\Builders;

use App\Application\Shared\DTOs\PublicMediaData;
use App\Domain\Shared\Contracts\PublicMediaBuilder;
use App\Models\Project;
use InvalidArgumentException;

class ProjectMediaBuilder implements PublicMediaBuilder
{
    public function supports(object $resource): bool
    {
        return $resource instanceof Project;
    }

    public function build(object $resource): PublicMediaData
    {
        if (! $resource instanceof Project) {
            throw new InvalidArgumentException('ProjectMediaBuilder expects a Project model.');
        }

        if (! $resource->relationLoaded('media')) {
            return new PublicMediaData;
        }

        $cover = $resource->getFirstMedia('cover');

        $gallery = $resource->getMedia('screenshots')
            ->map(fn ($media) => ['url' => $media->getUrl()])
            ->all();

        return new PublicMediaData(
            cover: $cover ? ['url' => $cover->getUrl()] : null,
            gallery: $gallery,
        );
    }
}
