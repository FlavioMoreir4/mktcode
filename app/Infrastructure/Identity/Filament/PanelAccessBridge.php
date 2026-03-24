<?php

declare(strict_types=1);

namespace App\Infrastructure\Identity\Filament;

use App\Application\Identity\Services\AdminAccessDecider;
use App\Models\User;

class PanelAccessBridge
{
    private static ?AdminAccessDecider $adminAccessDecider = null;

    public static function bootstrap(AdminAccessDecider $adminAccessDecider): void
    {
        self::$adminAccessDecider = $adminAccessDecider;
    }

    public static function canAccessAdminPanel(User $user): bool
    {
        return self::$adminAccessDecider?->canAccessAdminPanel($user) ?? false;
    }
}
