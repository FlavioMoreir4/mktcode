<?php

declare(strict_types=1);

namespace App\Infrastructure\Portfolio\Persistence\Eloquent;

use App\Domain\Portfolio\Contracts\ProjectRepository;
use App\Domain\Portfolio\Enums\ProjectStatus;
use App\Domain\Portfolio\Policies\ProjectVisibilityPolicy;
use App\Models\Project;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EloquentProjectRepository implements ProjectRepository
{
    public function __construct(private readonly ProjectVisibilityPolicy $visibility) {}

    public function paginatePublic(int $perPage = 9): LengthAwarePaginator
    {
        return Project::query()
            ->where('status', ProjectStatus::Published)
            ->orderByDesc('featured')
            ->orderBy('sort_order')
            ->orderByDesc('created_at')
            ->with(['author.media', 'media', 'tags'])
            ->paginate($perPage)
            ->withQueryString();
    }

    public function findPublicBySlug(string $slug): ?Project
    {
        $project = Project::query()
            ->where('slug', $slug)
            ->with(['author.media', 'media', 'tags'])
            ->first();

        if (! $project instanceof Project) {
            return null;
        }

        return $this->visibility->isPubliclyVisible($project) ? $project : null;
    }
}
