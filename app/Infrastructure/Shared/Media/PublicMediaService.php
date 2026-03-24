<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\Media;

use App\Application\Shared\DTOs\PublicMediaData;

class PublicMediaService
{
    public function __construct(private readonly PublicMediaRegistry $registry) {}

    public function for(object $resource): PublicMediaData
    {
        return $this->registry->build($resource);
    }
}
