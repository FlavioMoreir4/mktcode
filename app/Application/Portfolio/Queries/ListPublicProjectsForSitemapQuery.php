<?php

declare(strict_types=1);

namespace App\Application\Portfolio\Queries;

use App\Domain\Portfolio\Enums\ProjectStatus;
use App\Models\Project;
use Illuminate\Support\LazyCollection;

class ListPublicProjectsForSitemapQuery
{
    /**
     * @return LazyCollection<int, Project>
     */
    public function cursor(): LazyCollection
    {
        return Project::query()
            ->with(['author', 'media'])
            ->where('status', ProjectStatus::Published)
            ->orderByDesc('featured')
            ->orderBy('sort_order')
            ->orderByDesc('created_at')
            ->cursor();
    }
}
