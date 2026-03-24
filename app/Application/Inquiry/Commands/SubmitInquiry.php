<?php

declare(strict_types=1);

namespace App\Application\Inquiry\Commands;

use App\Application\Inquiry\DTOs\SubmitInquiryData;
use App\Domain\Inquiry\Contracts\InquiryRepository;
use App\Events\Inquiries\InquirySubmitted;
use App\Models\Inquiry;
use Illuminate\Support\Facades\Log;

/**
 * Persists a public inquiry and emits the follow-up workflow event.
 */
class SubmitInquiry
{
    public function __construct(private readonly InquiryRepository $inquiries) {}

    public function handle(SubmitInquiryData $data): Inquiry
    {
        $inquiry = $this->inquiries->create($data);

        InquirySubmitted::dispatch($inquiry);

        Log::info('Inquiry processed', [
            'inquiry_id' => $inquiry->id,
            'status' => $inquiry->status->value,
        ]);

        return $inquiry;
    }
}
