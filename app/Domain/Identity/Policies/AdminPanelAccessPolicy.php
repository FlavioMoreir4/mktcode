<?php

declare(strict_types=1);

namespace App\Domain\Identity\Policies;

use App\Models\User;

class AdminPanelAccessPolicy
{
    public function canAccess(User $user): bool
    {
        return $user->hasAnyRole(['super_admin', 'admin', 'editor', 'author'])
            || $user->getAllPermissions()->isNotEmpty();
    }
}
