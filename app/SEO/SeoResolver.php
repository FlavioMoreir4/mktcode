<?php

declare(strict_types=1);

namespace App\SEO;

use App\SEO\Contracts\HasSeo;
use App\SEO\DTO\SeoData;

use function get_class;

class SeoResolver
{
    public function resolve(HasSeo $model): SeoData
    {
        $builderClass = str_replace('App\\Models\\', 'App\\SEO\\Builders\\', get_class($model)).'SeoBuilder';

        throw_if(! class_exists($builderClass), 'SeoBuilder not found for model: '.get_class($model));

        return app($builderClass)->build($model);
    }
}
