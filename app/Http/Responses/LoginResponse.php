<?php

declare(strict_types=1);

namespace App\Http\Responses;

use App\Application\Identity\Services\AdminAccessDecider;
use App\Models\User;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

/**
 * Keeps Fortify's POST `/login` endpoint aligned with the application's split:
 * Filament owns the admin experience, while non-admin users land on the Inertia dashboard.
 */
class LoginResponse implements LoginResponseContract
{
    public function __construct(private readonly AdminAccessDecider $adminAccess) {}

    public function toResponse($request): mixed
    {
        $user = $request->user();

        if ($user instanceof User && $this->adminAccess->canAccessAdminPanel($user)) {
            return redirect()->intended('/admin');
        }

        return redirect()->intended('/dashboard');
    }
}
