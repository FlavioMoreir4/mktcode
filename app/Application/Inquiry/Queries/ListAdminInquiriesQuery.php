<?php

declare(strict_types=1);

namespace App\Application\Inquiry\Queries;

use App\Domain\Inquiry\Enums\InquiryStatus;
use Illuminate\Database\Eloquent\Builder;

class ListAdminInquiriesQuery
{
    public function apply(Builder $query): Builder
    {
        return $query->orderByRaw(
            'CASE WHEN status = ? THEN 0 ELSE 1 END',
            [InquiryStatus::New->value]
        );
    }
}
