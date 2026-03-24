<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\SEO;

use App\Application\Shared\DTOs\SeoData;
use App\Domain\Shared\Contracts\SeoDataBuilder;
use InvalidArgumentException;

class SeoRegistry
{
    /**
     * @param  iterable<int, SeoDataBuilder>  $builders
     */
    public function __construct(private readonly iterable $builders) {}

    public function build(object $resource): SeoData
    {
        foreach ($this->builders as $builder) {
            if ($builder->supports($resource)) {
                return $builder->build($resource);
            }
        }

        throw new InvalidArgumentException('Seo builder not configured for '.get_class($resource));
    }
}
