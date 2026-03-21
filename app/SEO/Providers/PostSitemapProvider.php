<?php

declare(strict_types=1);

namespace App\SEO\Providers;

use App\Models\Post;
use App\SEO\Contracts\SitemapProvider;
use Spatie\Sitemap\Sitemap;

class PostSitemapProvider implements SitemapProvider
{
    public function generate(Sitemap $sitemap): void
    {
        Post::public()
            ->with(['media', 'author', 'tags'])
            ->cursor()
            ->each(fn (Post $post) => $sitemap->add($post));
    }

    public function filename(): string
    {
        return 'sitemap-posts.xml';
    }
}
