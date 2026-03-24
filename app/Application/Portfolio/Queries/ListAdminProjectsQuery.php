<?php

declare(strict_types=1);

namespace App\Application\Portfolio\Queries;

use Illuminate\Database\Eloquent\Builder;

class ListAdminProjectsQuery
{
    public function apply(Builder $query): Builder
    {
        return $query
            ->with('author')
            ->orderByDesc('featured')
            ->orderBy('sort_order')
            ->orderByDesc('created_at');
    }
}
