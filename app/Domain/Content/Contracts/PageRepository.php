<?php

declare(strict_types=1);

namespace App\Domain\Content\Contracts;

use App\Models\Page;

interface PageRepository
{
    public function findPublicBySlug(string $slug): ?Page;

    /**
     * @return \Illuminate\Support\LazyCollection<int, Page>
     */
    public function cursorPublic();

    /**
     * Published legal pages (privacy, terms, cookies) available for the footer.
     *
     * @return \Illuminate\Database\Eloquent\Collection<int, Page>
     */
    public function findLegalPages();
}
