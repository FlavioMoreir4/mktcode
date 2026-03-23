<?php

declare(strict_types=1);

namespace App\Observers;

use App\Console\Commands\GenerateSitemap;
use App\Enums\PostStatus;
use App\Models\Post;
use Illuminate\Support\Facades\Artisan;

/**
 * Regenerates public search metadata whenever a public post changes.
 */
class PostObserver
{
    public function saved(Post $post): void
    {
        $isPublished = $post->status === PostStatus::Published
            && $post->published_at !== null
            && $post->published_at->isPast();

        $wasPublished = $post->getOriginal('status') === PostStatus::Published;

        if ($isPublished || $wasPublished) {
            Artisan::queue(GenerateSitemap::SIGNATURE);
        }
    }

    public function deleted(Post $post): void
    {
        Artisan::queue(GenerateSitemap::SIGNATURE);
    }
}
