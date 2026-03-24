<?php

declare(strict_types=1);

namespace App\Application\Portfolio\Commands;

use App\Domain\Portfolio\Enums\ProjectStatus;
use App\Models\Project;

class UnpublishProject
{
    public function handle(Project $project): Project
    {
        $project->status = ProjectStatus::Draft;
        $project->save();

        return $project->refresh();
    }
}
