<?php

declare(strict_types=1);

namespace App\SEO;

use App\SEO\Contracts\HasSeo;
use App\SEO\DTO\SeoData;

class SeoFactory
{
    public static function forModel(HasSeo $model): SeoData
    {
        return $model->getSeo();
    }
}
