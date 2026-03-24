<?php

declare(strict_types=1);

namespace App\Application\Portfolio\Commands;

use App\Models\Project;

class AssignProjectOwner
{
    public function handle(Project $project, ?int $ownerId): Project
    {
        $project->user_id = $ownerId;
        $project->save();

        return $project->refresh();
    }
}
