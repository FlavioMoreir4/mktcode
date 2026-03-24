<?php

declare(strict_types=1);

namespace App\Infrastructure\Content\Persistence\Eloquent;

use App\Domain\Content\Contracts\PostRepository;
use App\Domain\Content\Enums\PostStatus;
use App\Domain\Content\Policies\PostVisibilityPolicy;
use App\Models\Post;
use Carbon\CarbonImmutable;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EloquentPostRepository implements PostRepository
{
    public function __construct(private readonly PostVisibilityPolicy $visibility) {}

    public function paginatePublic(int $perPage = 12): LengthAwarePaginator
    {
        return Post::query()
            ->where('status', PostStatus::Published)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', CarbonImmutable::now())
            ->with(['author', 'category', 'media', 'tags'])
            ->latest('published_at')
            ->paginate($perPage)
            ->withQueryString();
    }

    public function findPublicBySlug(string $slug): ?Post
    {
        $post = Post::query()
            ->where('slug', $slug)
            ->with(['author.media', 'category', 'media', 'tags'])
            ->first();

        if (! $post instanceof Post) {
            return null;
        }

        return $this->visibility->isPubliclyVisible($post) ? $post : null;
    }
}
