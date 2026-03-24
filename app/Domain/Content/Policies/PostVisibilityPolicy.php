<?php

declare(strict_types=1);

namespace App\Domain\Content\Policies;

use App\Domain\Content\Enums\PostStatus;
use App\Models\Post;

class PostVisibilityPolicy
{
    public function isPubliclyVisible(Post $post): bool
    {
        return $post->status === PostStatus::Published
            && $post->published_at !== null
            && $post->published_at->isPast();
    }
}
