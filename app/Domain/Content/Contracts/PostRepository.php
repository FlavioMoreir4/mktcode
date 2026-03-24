<?php

declare(strict_types=1);

namespace App\Domain\Content\Contracts;

use App\Models\Post;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface PostRepository
{
    public function paginatePublic(int $perPage = 12): LengthAwarePaginator;

    public function findPublicBySlug(string $slug): ?Post;
}
