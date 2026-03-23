<?php

declare(strict_types=1);

use App\Models\User;
use Spatie\Permission\Models\Role;

test('users without admin permissions cannot access the Filament panel', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get('/admin')
        ->assertForbidden();
});

test('users with a content role can access the Filament panel', function () {
    Role::create(['name' => 'author']);

    $user = User::factory()->create();
    $user->assignRole('author');

    $this->actingAs($user)
        ->get('/admin')
        ->assertSuccessful();
});
