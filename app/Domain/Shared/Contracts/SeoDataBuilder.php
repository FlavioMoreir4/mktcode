<?php

declare(strict_types=1);

namespace App\Domain\Shared\Contracts;

use App\Application\Shared\DTOs\SeoData;

interface SeoDataBuilder
{
    public function supports(object $resource): bool;

    public function build(object $resource): SeoData;
}
