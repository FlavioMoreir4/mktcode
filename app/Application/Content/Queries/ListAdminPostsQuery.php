<?php

declare(strict_types=1);

namespace App\Application\Content\Queries;

use Illuminate\Database\Eloquent\Builder;

class ListAdminPostsQuery
{
    public function apply(Builder $query): Builder
    {
        return $query
            ->with(['author', 'category'])
            ->latest('published_at')
            ->latest('created_at');
    }
}
