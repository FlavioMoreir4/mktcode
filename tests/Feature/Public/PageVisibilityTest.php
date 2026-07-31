<?php

declare(strict_types=1);

use App\Domain\Content\Enums\PageStatus;
use App\Models\Page;
use Inertia\Testing\AssertableInertia as Assert;

function richPageDocument(string $text): array
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

test('draft pages are not visible on public routes', function () {
    $page = Page::query()->create([
        'title' => 'Private draft page',
        'slug' => 'private-draft-page',
        'body' => richPageDocument('Private draft body'),
        'status' => PageStatus::Draft,
    ]);

    $this->get(route('public.page.show', $page->slug))->assertNotFound();
});

test('scheduled pages are not visible on public routes before publication', function () {
    $page = Page::query()->create([
        'title' => 'Scheduled page',
        'slug' => 'scheduled-page',
        'body' => richPageDocument('Scheduled page body'),
        'status' => PageStatus::Published,
        'published_at' => now()->addDay(),
    ]);

    $this->get(route('public.page.show', $page->slug))->assertNotFound();
});

test('published pages are visible on public routes', function () {
    $page = Page::query()->create([
        'title' => 'Public page',
        'slug' => 'public-page',
        'body' => richPageDocument('Public page body'),
        'excerpt' => 'Public page excerpt',
        'status' => PageStatus::Published,
        'published_at' => now()->subHour(),
    ]);

    $this->get(route('public.page.show', $page->slug))
        ->assertSuccessful()
        ->assertInertia(fn (Assert $page) => $page
            ->component('public/PageShow')
            ->where('page.slug', 'public-page')
            ->where('page.title', 'Public page')
            ->where('page.excerpt', 'Public page excerpt')
            ->has('page.body')
            ->has('page.word_count')
            ->has('page.reading_time'));
});

test('published page appears in the dynamic sitemap', function () {
    Page::query()->create([
        'title' => 'Privacy Policy',
        'slug' => 'politica-de-privacidade',
        'body' => richPageDocument('Privacy body'),
        'status' => PageStatus::Published,
        'published_at' => now()->subHour(),
    ]);

    $generator = app(\App\Infrastructure\Shared\Sitemap\SitemapGenerator::class);
    $generator->generate();

    $sitemap = file_get_contents(public_path('sitemap-content-pages.xml'));

    expect($sitemap)->toContain(route('public.page.show', 'politica-de-privacidade'));
});
