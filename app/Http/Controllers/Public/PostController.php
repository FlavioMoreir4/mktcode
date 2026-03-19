<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Resources\Public\PublicPostIndexResource;
use App\Http\Resources\Public\PublicPostPublicResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PostController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function index(Request $request): Response
    {
        $posts = Post::published()
            ->with(['author', 'category', 'media', 'tags'])
            ->latest('published_at')
            ->paginate(12)
            ->withQueryString();

        return Inertia::render('public/blog/Index', [
            'posts' => PublicPostIndexResource::collection($posts),
        ]);
    }

    public function show(Post $post): Response
    {
        $post->load(['author.media', 'category', 'media']);

        return Inertia::render('public/blog/Show', [
            'post' => PublicPostPublicResource::make($post)->resolve(),
        ]);
    }
}
