<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Support\Facades\Route;

test('profile page is displayed', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->get(route('profile.edit'));

    $response->assertRedirect('/admin/profile');
});

test('profile updates are handled by Filament instead of legacy web routes', function () {
    expect(Route::has('profile.update'))->toBeFalse();
});

test('profile deletion is handled by Filament instead of legacy web routes', function () {
    expect(Route::has('profile.destroy'))->toBeFalse();
});
