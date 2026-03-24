<?php

declare(strict_types=1);

namespace App\Application\Portfolio\Queries;

use App\Domain\Portfolio\Enums\ProjectStatus;
use App\Models\Project;
use Illuminate\Database\Eloquent\Collection;

class ListFeaturedHomeProjectsQuery
{
    /**
     * @return Collection<int, Project>
     */
    public function take(int $limit = 3): Collection
    {
        return Project::query()
            ->with(['author.media', 'media'])
            ->where('status', ProjectStatus::Published)
            ->orderByDesc('featured')
            ->orderBy('sort_order')
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get();
    }
}
