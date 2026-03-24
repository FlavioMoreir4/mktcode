<?php

declare(strict_types=1);

namespace App\Application\Content\Queries;

use App\Domain\Content\Contracts\PostRepository;
use App\Models\Post;

class GetPublicPostQuery
{
    public function __construct(private readonly PostRepository $posts) {}

    public function findBySlug(string $slug): ?Post
    {
        return $this->posts->findPublicBySlug($slug);
    }
}
