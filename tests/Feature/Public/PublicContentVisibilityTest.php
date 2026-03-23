<?php

declare(strict_types=1);

use App\Enums\PostStatus;
use App\Enums\ProjectStatus;
use App\Models\Category;
use App\Models\Post;
use App\Models\Project;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

function richEditorDocument(string $text): array
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

test('draft posts are not visible on public routes', function () {
    $author = User::factory()->create(['username' => 'writer']);
    $category = Category::query()->create(['name' => 'News']);

    $post = Post::query()->create([
        'title' => 'Private draft',
        'slug' => 'private-draft',
        'body' => richEditorDocument('Private draft body'),
        'status' => PostStatus::Draft,
        'author_id' => $author->id,
        'category_id' => $category->id,
    ]);

    $this->get(route('public.blog.show', $post->slug))->assertNotFound();
});

test('scheduled posts are not visible on public routes before publication', function () {
    $author = User::factory()->create(['username' => 'writer']);
    $category = Category::query()->create(['name' => 'News']);

    $post = Post::query()->create([
        'title' => 'Scheduled post',
        'slug' => 'scheduled-post',
        'body' => richEditorDocument('Scheduled post body'),
        'status' => PostStatus::Published,
        'published_at' => now()->addDay(),
        'author_id' => $author->id,
        'category_id' => $category->id,
    ]);

    $this->get(route('public.blog.show', $post->slug))->assertNotFound();
});

test('draft projects are not visible on public routes', function () {
    $project = Project::query()->create([
        'title' => 'Internal project',
        'slug' => 'internal-project',
        'description' => 'Hidden from the public site',
        'content' => richEditorDocument('Internal project body'),
        'status' => ProjectStatus::Draft,
    ]);

    $this->get(route('public.projects.show', $project->slug))->assertNotFound();
});

test('public profile only exposes published posts', function () {
    $user = User::factory()->create([
        'username' => 'jane-doe',
        'title' => 'Developer',
    ]);

    $category = Category::query()->create(['name' => 'Insights']);

    $publishedPost = Post::query()->create([
        'title' => 'Visible post',
        'slug' => 'visible-post',
        'body' => richEditorDocument('Visible post body'),
        'status' => PostStatus::Published,
        'published_at' => now()->subHour(),
        'author_id' => $user->id,
        'category_id' => $category->id,
    ]);

    Post::query()->create([
        'title' => 'Hidden draft',
        'slug' => 'hidden-draft',
        'body' => richEditorDocument('Hidden draft body'),
        'status' => PostStatus::Draft,
        'author_id' => $user->id,
        'category_id' => $category->id,
    ]);

    $this->get(route('public.user.show', $user->username))
        ->assertSuccessful()
        ->assertInertia(fn (Assert $page) => $page
            ->component('public/user/Show')
            ->has('user.posts.data', 1)
            ->where('user.posts.data.0.slug', $publishedPost->slug));
});
