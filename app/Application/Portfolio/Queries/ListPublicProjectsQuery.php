<?php

declare(strict_types=1);

namespace App\Application\Portfolio\Queries;

use App\Domain\Portfolio\Contracts\ProjectRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ListPublicProjectsQuery
{
    public function __construct(private readonly ProjectRepository $projects) {}

    public function paginate(int $perPage = 9): LengthAwarePaginator
    {
        return $this->projects->paginatePublic($perPage);
    }
}
