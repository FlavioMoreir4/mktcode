<?php

declare(strict_types=1);

namespace App\Application\Content\Queries;

use App\Domain\Content\Contracts\PostRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ListPublicPostsQuery
{
    public function __construct(private readonly PostRepository $posts) {}

    public function paginate(int $perPage = 12): LengthAwarePaginator
    {
        return $this->posts->paginatePublic($perPage);
    }
}
