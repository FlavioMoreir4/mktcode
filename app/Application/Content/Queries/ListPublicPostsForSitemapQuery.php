<?php

declare(strict_types=1);

namespace App\Application\Content\Queries;

use App\Domain\Content\Enums\PostStatus;
use App\Models\Post;
use Illuminate\Support\LazyCollection;

class ListPublicPostsForSitemapQuery
{
    /**
     * @return LazyCollection<int, Post>
     */
    public function cursor(): LazyCollection
    {
        return Post::query()
            ->with(['media', 'author', 'tags'])
            ->where('status', PostStatus::Published)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->cursor();
    }
}
