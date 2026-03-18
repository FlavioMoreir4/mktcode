<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Filament\Forms\Components\RichEditor\RichContentRenderer;
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
            ->with(['author', 'category', 'media'])
            ->latest('published_at')
            ->paginate(12)
            ->withQueryString();

        $posts->getCollection()->transform(function ($post) {
            $post->body = RichContentRenderer::make($post->body)->toHtml();

            return $post;
        });

        return Inertia::render('public/blog/Index', [
            'posts' => $posts,
        ]);
    }

    public function show(Post $post): Response
    {
        $post->load(['author.media', 'category', 'media']);
        $post->body = RichContentRenderer::make($post->body)->toHtml();

        return Inertia::render('public/blog/Show', [
            'post' => $post,
        ]);
    }
}
