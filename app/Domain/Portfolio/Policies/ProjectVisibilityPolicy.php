<?php

declare(strict_types=1);

namespace App\Domain\Portfolio\Policies;

use App\Domain\Portfolio\Enums\ProjectStatus;
use App\Models\Project;

class ProjectVisibilityPolicy
{
    public function isPubliclyVisible(Project $project): bool
    {
        return $project->status === ProjectStatus::Published;
    }
}
