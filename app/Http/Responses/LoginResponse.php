<?php

namespace App\Http\Responses;

use Illuminate\Http\Request;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Symfony\Component\HttpFoundation\Response;

class LoginResponse implements LoginResponseContract
{
    /**
     * @param  Request  $request
     * @return Response
     */
    public function toResponse($request)
    {
        $user = $request->user();

        // Se o usuário tem permissão de admin, manda para o painel
        if ($user->hasRole(['super_admin', 'admin', 'editor', 'author'])) {
            return redirect()->intended('/admin');
        }

        // Caso contrário, manda para o dashboard do frontend
        return redirect()->intended('/dashboard');
    }
}
