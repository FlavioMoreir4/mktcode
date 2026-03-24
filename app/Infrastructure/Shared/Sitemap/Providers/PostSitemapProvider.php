<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\Sitemap\Providers;

use App\Application\Content\Queries\ListPublicPostsForSitemapQuery;
use App\Domain\Shared\Contracts\SitemapEntryProvider;
use App\Models\Post;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class PostSitemapProvider implements SitemapEntryProvider
{
    public function __construct(private readonly ListPublicPostsForSitemapQuery $posts) {}

    public function generate(Sitemap $sitemap): void
    {
        $this->posts
            ->cursor()
            ->each(function (Post $post) use ($sitemap): void {
                $url = Url::create(route('public.blog.show', $post->slug))
                    ->setLastModificationDate($post->updated_at)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                    ->setPriority(0.8);

                $cover = $post->getFirstMedia('cover');
                if ($cover) {
                    $url->addImage($cover->getUrl(), $post->seo_title ?? $post->title);
                }

                $sitemap->add($url);
            });
    }

    public function filename(): string
    {
        return 'sitemap-posts.xml';
    }
}
