<?php

declare(strict_types=1);

namespace App\Observers;

use App\Enums\PostStatus;
use App\Models\Post;
use Illuminate\Support\Facades\Artisan;

class PostObserver
{
    public function saved(Post $post): void
    {
        $isPublished = $post->status === PostStatus::Published
            && $post->published_at !== null
            && $post->published_at->isPast();

        $wasPublished = $post->getOriginal('status') === PostStatus::Published;

        if ($isPublished || $wasPublished) {
            Artisan::queue('app:generate-sitemap');
        }
    }

    public function deleted(Post $post): void
    {
        Artisan::queue('app:generate-sitemap');
    }
}
