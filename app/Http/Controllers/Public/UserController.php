<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public;

use App\Application\Identity\DTOs\PublicProfileViewData;
use App\Application\Identity\Queries\GetPublicProfileQuery;
use App\Http\Controllers\Controller;
use App\Http\Resources\Public\PublicUserResource;
use App\Infrastructure\Shared\SEO\SeoService;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Public team/profile adapter backed by Inertia pages.
 */
class UserController extends Controller
{
    public function show(string $user, GetPublicProfileQuery $getPublicProfile, SeoService $seo): Response
    {
        $profile = $getPublicProfile->findByUsername($user);
        if ($profile === null) {
            throw new NotFoundHttpException;
        }

        return Inertia::render('public/user/Show', [
            'user' => PublicUserResource::make(PublicProfileViewData::fromModel($profile))->resolve(),
            'seo' => $seo->forUser($profile),
        ]);
    }
}
