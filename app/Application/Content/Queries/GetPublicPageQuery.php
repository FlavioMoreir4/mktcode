<?php

declare(strict_types=1);

namespace App\Application\Content\Queries;

use App\Domain\Content\Contracts\PageRepository;
use App\Models\Page;

class GetPublicPageQuery
{
    public function __construct(private readonly PageRepository $pages) {}

    public function findBySlug(string $slug): ?Page
    {
        return $this->pages->findPublicBySlug($slug);
    }
}
