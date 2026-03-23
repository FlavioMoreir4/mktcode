<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Resources\Public\PublicPostCollection;
use App\Http\Resources\Public\PublicPostShowResource;
use App\Models\Post;
use App\SEO\Services\SeoService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PostController extends Controller
{
    public function index(Request $request, SeoService $seo): Response
    {
        $posts = Post::published()
            ->with(['author', 'category', 'media', 'tags'])
            ->latest('published_at')
            ->paginate(12)
            ->withQueryString();

        return Inertia::render('public/blog/Index', [
            'posts' => new PublicPostCollection($posts),
            'seo' => $seo->forPage(
                route: 'public.blog.index',
                title: 'Blog',
            ),
        ]);
    }

    public function show(Post $post, SeoService $seo): Response
    {
        $post->load(['author.media', 'category', 'media', 'tags']);

        return Inertia::render('public/blog/Show', [
            'post' => PublicPostShowResource::make($post)->resolve(),
            'seo' => $seo->forPost($post),
        ]);
    }
}
