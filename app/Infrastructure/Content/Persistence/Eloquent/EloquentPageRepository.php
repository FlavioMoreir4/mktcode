<?php

declare(strict_types=1);

namespace App\Infrastructure\Content\Persistence\Eloquent;

use App\Domain\Content\Contracts\PageRepository;
use App\Domain\Content\Policies\PageVisibilityPolicy;
use App\Models\Page;
use Illuminate\Support\LazyCollection;

class EloquentPageRepository implements PageRepository
{
    public function __construct(private readonly PageVisibilityPolicy $visibility) {}

    public function findPublicBySlug(string $slug): ?Page
    {
        $page = Page::query()
            ->where('slug', $slug)
            ->first();

        if (! $page instanceof Page) {
            return null;
        }

        return $this->visibility->isPubliclyVisible($page) ? $page : null;
    }

    /**
     * @return LazyCollection<int, Page>
     */
    public function cursorPublic(): LazyCollection
    {
        return Page::query()
            ->where('status', \App\Domain\Content\Enums\PageStatus::Published)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->cursor();
    }

    /**
     * Published legal pages (privacy, terms, cookies) available for the footer.
     *
     * @return \Illuminate\Database\Eloquent\Collection<int, Page>
     */
    public function findLegalPages(): \Illuminate\Database\Eloquent\Collection
    {
        return Page::query()
            ->whereIn('slug', ['politica-de-privacidade', 'termos-de-servico', 'politica-de-cookies'])
            ->where('status', \App\Domain\Content\Enums\PageStatus::Published)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->orderBy('slug')
            ->get();
    }
}
