<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Support\Facades\Route;

test('password update page is displayed', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->get(route('user-password.edit'));

    $response->assertRedirect('/admin/profile');
});

test('password updates are handled by Filament instead of legacy web routes', function () {
    expect(Route::has('user-password.update'))->toBeFalse();
});

test('legacy password update endpoints are intentionally absent', function () {
    expect(Route::has('user-password.update'))->toBeFalse();
});
