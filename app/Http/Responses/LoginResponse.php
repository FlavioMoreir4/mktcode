<?php

declare(strict_types=1);

namespace App\Http\Responses;

use App\Models\User;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

/**
 * Keeps Fortify's POST `/login` endpoint aligned with the application's split:
 * Filament owns the admin experience, while non-admin users land on the Inertia dashboard.
 */
class LoginResponse implements LoginResponseContract
{
    public function toResponse($request): mixed
    {
        $user = $request->user();

        if ($user instanceof User && $user->canAccessAdminPanel()) {
            return redirect()->intended('/admin');
        }

        return redirect()->intended('/dashboard');
    }
}
