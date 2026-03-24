<?php

declare(strict_types=1);

namespace App\Application\Content\Commands;

use App\Domain\Content\Enums\PostStatus;
use App\Models\Post;

class UnpublishPost
{
    public function handle(Post $post): Post
    {
        $post->status = PostStatus::Draft;
        $post->published_at = null;
        $post->save();

        return $post->refresh();
    }
}
