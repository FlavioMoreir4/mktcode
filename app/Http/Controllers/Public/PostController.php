<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public;

use App\Application\Content\DTOs\PublicPostViewData;
use App\Application\Content\Queries\GetPublicPostQuery;
use App\Application\Content\Queries\ListPublicPostsQuery;
use App\Http\Controllers\Controller;
use App\Http\Resources\Public\PublicPostCollection;
use App\Http\Resources\Public\PublicPostShowResource;
use App\Infrastructure\Shared\SEO\SeoService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Public blog adapter backed by Inertia pages.
 */
class PostController extends Controller
{
    public function index(Request $request, ListPublicPostsQuery $listPublicPosts, SeoService $seo): Response
    {
        $posts = $listPublicPosts->paginate(12);
        $posts->setCollection(
            $posts->getCollection()->map(fn ($post) => PublicPostViewData::summary($post))
        );

        return Inertia::render('public/blog/Index', [
            'posts' => new PublicPostCollection($posts),
            'seo' => $seo->forPage(
                route: 'public.blog.index',
                title: 'Blog',
            ),
        ]);
    }

    public function show(string $post, GetPublicPostQuery $getPublicPost, SeoService $seo): Response
    {
        $resolvedPost = $getPublicPost->findBySlug($post);
        if ($resolvedPost === null) {
            throw new NotFoundHttpException;
        }

        return Inertia::render('public/blog/Show', [
            'post' => PublicPostShowResource::make(PublicPostViewData::detail($resolvedPost))->resolve(),
            'seo' => $seo->forPost($resolvedPost),
        ]);
    }
}
