<?php

declare(strict_types=1);

namespace App\Http\Responses;

use Illuminate\Http\Request;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Symfony\Component\HttpFoundation\Response;

class LoginResponse implements LoginResponseContract
{
    public function toResponse(Request $request): Response
    {
        $user = $request->user();

        if ($user->hasRole(['super_admin', 'admin', 'editor', 'author'])) {
            return redirect()->intended('/admin');
        }

        return redirect()->intended('/dashboard');
    }
}
