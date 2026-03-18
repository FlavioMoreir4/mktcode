<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Post;
use Illuminate\Support\Facades\Artisan;

class PostObserver
{
    public function saved(Post $post): void
    {
        $isPublished = $post->status === 'published'
            && $post->published_at !== null
            && $post->published_at->isPast();

        $wasPublished = $post->getOriginal('status') === 'published';

        if ($isPublished || $wasPublished) {
            Artisan::queue('app:generate-sitemap');
        }
    }

    public function deleted(Post $post): void
    {
        Artisan::queue('app:generate-sitemap');
    }
}
