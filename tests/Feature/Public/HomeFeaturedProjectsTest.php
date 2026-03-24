<?php

declare(strict_types=1);

use App\Domain\Portfolio\Enums\ProjectStatus;
use App\Models\Project;
use App\Models\Service;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

function homeProjectDocument(string $text): array
{
    return [
        'type' => 'doc',
        'content' => [
            [
                'type' => 'paragraph',
                'content' => [
                    [
                        'type' => 'text',
                        'text' => $text,
                    ],
                ],
            ],
        ],
    ];
}

test('homepage only shows public projects using shared ordering semantics', function () {
    Service::factory()->create(['title' => 'Serviço Homepage', 'active' => true]);
    $owner = User::factory()->create([
        'username' => 'featured-owner',
        'title' => 'Founder',
    ]);

    Project::query()->create([
        'title' => 'Draft project',
        'slug' => 'draft-project',
        'description' => 'Hidden',
        'content' => homeProjectDocument('Hidden body'),
        'status' => ProjectStatus::Draft,
        'featured' => true,
        'sort_order' => 0,
    ]);

    Project::query()->create([
        'title' => 'Later project',
        'slug' => 'later-project',
        'description' => 'Later',
        'content' => homeProjectDocument('Later body'),
        'status' => ProjectStatus::Published,
        'featured' => false,
        'sort_order' => 2,
        'user_id' => $owner->id,
    ]);

    Project::query()->create([
        'title' => 'Featured project',
        'slug' => 'featured-project-home',
        'description' => 'Featured',
        'content' => homeProjectDocument('Featured body'),
        'status' => ProjectStatus::Published,
        'featured' => true,
        'sort_order' => 99,
        'user_id' => $owner->id,
    ]);

    Project::query()->create([
        'title' => 'Ordered project',
        'slug' => 'ordered-project-home',
        'description' => 'Ordered',
        'content' => homeProjectDocument('Ordered body'),
        'status' => ProjectStatus::Published,
        'featured' => false,
        'sort_order' => 1,
        'user_id' => $owner->id,
    ]);

    $this->get(route('home'))
        ->assertSuccessful()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Welcome')
            ->where('projects.0.slug', 'featured-project-home')
            ->where('projects.0.author.username', $owner->username)
            ->where('projects.0.author.profile_url', route('public.user.show', $owner->username))
            ->where('projects.1.slug', 'ordered-project-home')
            ->where('projects.2.slug', 'later-project'));
});
