<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\Media;

use App\Application\Shared\DTOs\PublicMediaData;
use App\Domain\Shared\Contracts\PublicMediaBuilder;
use InvalidArgumentException;

class PublicMediaRegistry
{
    /**
     * @param  iterable<PublicMediaBuilder>  $builders
     */
    public function __construct(private readonly iterable $builders) {}

    public function build(object $resource): PublicMediaData
    {
        foreach ($this->builders as $builder) {
            if ($builder->supports($resource)) {
                return $builder->build($resource);
            }
        }

        throw new InvalidArgumentException(
            'Media builder not configured for '.get_class($resource)
        );
    }
}
