<?php

declare(strict_types=1);

namespace App\Application\Inquiry\Commands;

use App\Domain\Inquiry\Contracts\InquiryRepository;
use App\Domain\Inquiry\Enums\InquiryStatus;
use App\Models\Inquiry;

class ResolveInquiry
{
    public function __construct(private readonly InquiryRepository $inquiries) {}

    public function handle(Inquiry $inquiry): Inquiry
    {
        $inquiry->status = InquiryStatus::Resolved;

        return $this->inquiries->save($inquiry);
    }
}
