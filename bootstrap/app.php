<?php

declare(strict_types=1);

use App\Http\Middleware\HandleAppearance;
use App\Http\Middleware\HandleInertiaRequests;
use App\Http\Middleware\TrustProxies;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets;
use Illuminate\Http\Request;
use Inertia\Inertia;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->encryptCookies(except: ['appearance', 'sidebar_state']);

        $middleware->prepend(TrustProxies::class);

        $middleware->web(append: [
            HandleAppearance::class,
            HandleInertiaRequests::class,
            AddLinkHeadersForPreloadedAssets::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->respond(function ($response, Throwable $e, Request $request) {

            $status = $response->getStatusCode();

            // Só intercepta erros "apresentáveis"
            if (! in_array($status, [403, 404, 419, 500, 503])) {
                return $response;
            }

            // Session expirada (419) → redirect com flash
            if ($status === 419) {
                return redirect()->back()->withErrors([
                    'message' => 'Sua sessão expirou. Tente novamente.',
                ]);
            }

            // return Inertia::render('Error', ['status' => $status])
            //     ->toResponse($request)
            //     ->setStatusCode($status);

            // Request Inertia → responde com componente Vue
            if ($request->header('X-Inertia')) {
                return Inertia::render('Error', ['status' => $status])
                    ->toResponse($request)
                    ->setStatusCode($status);
            }

            // Request normal (bot, curl, SSR, acesso direto) → Blade
            // Laravel resolve automaticamente resources/views/errors/{status}.blade.php
            return $response;
        });

    })->create();
