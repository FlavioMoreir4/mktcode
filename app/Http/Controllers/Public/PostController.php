<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Resources\Public\PublicPostCollection;
use App\Http\Resources\Public\PublicPostShowResource;
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
            'posts' => new PublicPostCollection($posts),
        ]);
    }

    public function show(Post $post): Response
    {
        $post->load(['author.media', 'category', 'media', 'tags']);

        return Inertia::render('public/blog/Show', [
            'post' => PublicPostShowResource::make($post)->resolve(),
        ]);
    }
}
