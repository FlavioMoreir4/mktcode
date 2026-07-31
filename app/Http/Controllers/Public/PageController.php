<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public;

use App\Application\Content\DTOs\PublicPageViewData;
use App\Application\Content\Queries\GetPublicPageQuery;
use App\Http\Controllers\Controller;
use App\Infrastructure\Shared\SEO\SeoService;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Public static page adapter backed by Inertia.
 */
class PageController extends Controller
{
    public function show(string $page, GetPublicPageQuery $getPublicPage, SeoService $seoService): Response
    {
        $resolvedPage = $getPublicPage->findBySlug($page);

        if ($resolvedPage === null) {
            throw new NotFoundHttpException;
        }

        return Inertia::render('public/PageShow', [
            'page' => PublicPageViewData::detail($resolvedPage)->toArray(),
            'seo' => $seoService->forPageModel($resolvedPage),
        ]);
    }
}
