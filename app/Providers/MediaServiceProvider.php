<?php

declare(strict_types=1);

namespace App\Providers;

use App\Infrastructure\Shared\Media\Builders\PostMediaBuilder;
use App\Infrastructure\Shared\Media\Builders\ProjectMediaBuilder;
use App\Infrastructure\Shared\Media\Builders\UserMediaBuilder;
use App\Infrastructure\Shared\Media\PublicMediaRegistry;
use App\Infrastructure\Shared\Media\PublicMediaService;
use Illuminate\Support\ServiceProvider;

class MediaServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        /**
         * Registry
         */
        $this->app->singleton(PublicMediaRegistry::class, function ($app): PublicMediaRegistry {
            return new PublicMediaRegistry([
                $app->make(PostMediaBuilder::class),
                $app->make(ProjectMediaBuilder::class),
                $app->make(UserMediaBuilder::class),
            ]);
        });

        /**
         * Service
         */
        $this->app->singleton(PublicMediaService::class, function ($app): PublicMediaService {
            return new PublicMediaService(
                $app->make(PublicMediaRegistry::class)
            );
        });
    }
}
