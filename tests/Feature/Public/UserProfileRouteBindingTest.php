<?php

declare(strict_types=1);

use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

it('resolves public user profile route by username', function (): void {
    $user = User::factory()->create([
        'username' => 'jane-doe',
        'title' => 'Developer',
    ]);

    $response = $this->get('/u/jane-doe');

    $response->assertOk();
    $response->assertInertia(
        fn (Assert $page) => $page
            ->component('public/user/Show')
            ->where('user.username', $user->username)
            ->where('user.name', $user->name)
            ->where('user.title', $user->title)
            ->has('user.projects.data')
            ->has('user.posts.data')
    );
});
