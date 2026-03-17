<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
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
