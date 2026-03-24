<?php

declare(strict_types=1);

namespace App\Application\Portfolio\Commands;

use App\Models\Project;

class ReorderProjects
{
    public function handle(Project $project, int $sortOrder): Project
    {
        $project->sort_order = $sortOrder;
        $project->save();

        return $project->refresh();
    }
}
