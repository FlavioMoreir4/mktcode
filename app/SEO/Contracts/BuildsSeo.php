<?php

declare(strict_types=1);

namespace App\SEO\Contracts;

use App\SEO\DTO\SeoData;

interface BuildsSeo
{
    public function build(): SeoData;
}
