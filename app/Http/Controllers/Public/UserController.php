<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Resources\Public\PublicUserResource;
use App\Models\User;
use App\SEO\Services\SeoService;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    public function show(User $user, SeoService $seo): Response
    {
        $user->load([
            'posts' => fn ($q) => $q->with('category')->latest()->limit(5),
            'projects' => fn ($q) => $q->published()->ordered()->limit(6),
        ]);

        return Inertia::render('public/user/Show', [
            'user' => PublicUserResource::make($user)->resolve(),
        ]);
    }
}
