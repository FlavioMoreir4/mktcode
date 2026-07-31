<?php

declare(strict_types=1);

namespace App\Application\Content\Queries;

use App\Domain\Content\Contracts\PageRepository;

class ListPublicPagesForSitemapQuery
{
    public function __construct(private readonly PageRepository $pages) {}

    /**
     * @return \Illuminate\Support\LazyCollection<int, \App\Models\Page>
     */
    public function cursor()
    {
        return $this->pages->cursorPublic();
    }
}
