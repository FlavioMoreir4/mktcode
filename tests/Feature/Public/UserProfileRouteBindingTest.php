<?php

declare(strict_types=1);

use App\Models\User;

it('resolves public user profile route by username', function (): void {
    $user = User::factory()->create([
        'username' => 'jane-doe',
        'title' => 'Developer',
    ]);

    $response = $this->get('/u/jane-doe');

    $response->assertOk();
    $response->assertInertia(
        fn ($page) => $page
            ->component('public/user/Show')
            ->where('user.username', $user->username)
    );
});
