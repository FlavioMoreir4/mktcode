<?php

declare(strict_types=1);

namespace App\Domain\Portfolio\Contracts;

use App\Models\Project;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ProjectRepository
{
    public function paginatePublic(int $perPage = 9): LengthAwarePaginator;

    public function findPublicBySlug(string $slug): ?Project;
}
