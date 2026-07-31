<?php

declare(strict_types=1);

namespace App\Domain\Content\Policies;

use App\Domain\Content\Enums\PageStatus;
use App\Models\Page;

class PageVisibilityPolicy
{
    public function isPubliclyVisible(Page $page): bool
    {
        return $page->status === PageStatus::Published
            && $page->published_at !== null
            && $page->published_at->isPast();
    }
}
