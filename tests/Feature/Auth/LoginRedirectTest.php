<?php

declare(strict_types=1);

use App\Http\Responses\LoginResponse;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

test('login response redirects admin users to Filament', function () {
    Role::create(['name' => 'admin']);

    $user = User::factory()->create();
    $user->assignRole('admin');

    $request = Request::create('/login', 'POST');
    $request->setUserResolver(fn (): User => $user);

    $response = app(LoginResponse::class)->toResponse($request);

    expect($response->getTargetUrl())->toEndWith('/admin');
    expect($user->canAccessPanel(Filament::getPanel('admin')))->toBeTrue();
});

test('login response redirects non admin users to the dashboard', function () {
    $user = User::factory()->create();

    $request = Request::create('/login', 'POST');
    $request->setUserResolver(fn (): User => $user);

    $response = app(LoginResponse::class)->toResponse($request);

    expect($response->getTargetUrl())->toEndWith('/dashboard');
    expect($user->canAccessAdminPanel())->toBeFalse();
});
