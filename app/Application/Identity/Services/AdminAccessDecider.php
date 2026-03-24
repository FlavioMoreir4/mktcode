<?php

declare(strict_types=1);

namespace App\Application\Identity\Services;

use App\Domain\Identity\Policies\AdminPanelAccessPolicy;
use App\Models\User;

class AdminAccessDecider
{
    public function __construct(private readonly AdminPanelAccessPolicy $policy) {}

    public function canAccessAdminPanel(User $user): bool
    {
        return $this->policy->canAccess($user);
    }
}
