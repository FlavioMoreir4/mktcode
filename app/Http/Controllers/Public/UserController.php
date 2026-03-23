<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Resources\Public\PublicUserResource;
use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Public team/profile adapter backed by Inertia pages.
 */
class UserController extends Controller
{
    public function show(User $user): Response
    {
        $user->load([
            'posts' => fn ($q) => $q->public()->with('category')->latest('published_at')->limit(5),
            'projects' => fn ($q) => $q->published()->ordered()->with('media')->limit(6),
        ]);

        return Inertia::render('public/user/Show', [
            'user' => PublicUserResource::make($user)->resolve(),
        ]);
    }
}
