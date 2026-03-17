<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Project;
use App\Models\User;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class SitemapController extends Controller
{
    public function __invoke()
    {
        $sitemap = Sitemap::create();

        // Static pages
        $staticPages = [
            ['url' => route('home'),     'priority' => 1.0,  'freq' => Url::CHANGE_FREQUENCY_WEEKLY],
            ['url' => route('public.services'),  'priority' => 0.9,  'freq' => Url::CHANGE_FREQUENCY_MONTHLY],
            ['url' => route('public.projects'),  'priority' => 0.9,  'freq' => Url::CHANGE_FREQUENCY_WEEKLY],
            ['url' => route('public.blog.index'),      'priority' => 0.9,  'freq' => Url::CHANGE_FREQUENCY_DAILY],
            ['url' => route('public.about'),     'priority' => 0.7,  'freq' => Url::CHANGE_FREQUENCY_MONTHLY],
            ['url' => route('public.contact'),   'priority' => 0.6,  'freq' => Url::CHANGE_FREQUENCY_YEARLY],
        ];

        foreach ($staticPages as $page) {
            $sitemap->add(
                Url::create($page['url'])
                    ->setChangeFrequency($page['freq'])
                    ->setPriority($page['priority'])
            );
        }

        // dd(Post::published()
        //     ->with('media')
        //     ->latest('published_at')
        //     ->cursor()->toArray());

        // Posts publicate
        Post::published()
            ->with('media')
            ->latest('published_at')
            ->cursor()
            ->each(fn (Post $post) => $sitemap->add($post));

        // Projects publicate
        Project::published()
            ->with('media')
            ->latest('published_at')
            ->cursor()
            ->each(fn (Project $project) => $sitemap->add($project));

        // Users
        User::all()
            ->each(fn (User $user) => $sitemap->add($user));

        return $sitemap;
    }
}
