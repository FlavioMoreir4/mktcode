<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Resources\Public\UserPublicResource;
use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    public function show(string $username): Response
    {
        $user = User::where('username', $username)
            ->with([
                'posts' => fn ($q) => $q->with('category')->latest()->limit(5),
                'projects' => fn ($q) => $q->published()->ordered()->limit(6),
            ])
            ->firstOrFail();

        return Inertia::render('public/user/Show', [
            'user' => (new UserPublicResource($user))->resolve(),
        ]);
    }
}
