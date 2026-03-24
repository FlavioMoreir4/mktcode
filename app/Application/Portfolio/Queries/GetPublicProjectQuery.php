<?php

declare(strict_types=1);

namespace App\Application\Portfolio\Queries;

use App\Domain\Portfolio\Contracts\ProjectRepository;
use App\Models\Project;

class GetPublicProjectQuery
{
    public function __construct(private readonly ProjectRepository $projects) {}

    public function findBySlug(string $slug): ?Project
    {
        return $this->projects->findPublicBySlug($slug);
    }
}
