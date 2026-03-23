<?php

declare(strict_types=1);

use App\Console\Commands\GenerateSitemap;
use App\Enums\PostStatus;
use App\Enums\ProjectStatus;
use App\Models\Category;
use App\Models\Post;
use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;

function richEditorDocumentForSeo(string $text): array
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

test('published posts queue sitemap generation', function () {
    Artisan::spy();

    $author = User::factory()->create();
    $category = Category::query()->create(['name' => 'SEO']);

    Post::query()->create([
        'title' => 'Published post',
        'slug' => 'published-post',
        'body' => richEditorDocumentForSeo('Published post body'),
        'status' => PostStatus::Published,
        'published_at' => now()->subMinute(),
        'author_id' => $author->id,
        'category_id' => $category->id,
    ]);

    Artisan::shouldHaveReceived('queue')->with(GenerateSitemap::SIGNATURE);
});

test('published projects queue sitemap generation', function () {
    Artisan::spy();

    Project::query()->create([
        'title' => 'Published project',
        'slug' => 'published-project',
        'description' => 'Public portfolio case',
        'content' => richEditorDocumentForSeo('Published project body'),
        'status' => ProjectStatus::Published,
    ]);

    Artisan::shouldHaveReceived('queue')->with(GenerateSitemap::SIGNATURE);
});

test('public profile changes queue sitemap generation', function () {
    Artisan::spy();

    $user = User::factory()->create();

    $user->update(['username' => 'public-author']);

    Artisan::shouldHaveReceived('queue')->with(GenerateSitemap::SIGNATURE);
});
