<?php

declare(strict_types=1);

namespace App\SEO\Contracts;

use App\SEO\DTO\SeoData;

interface HasSeo
{
    public function getSeo(): SeoData;
}
