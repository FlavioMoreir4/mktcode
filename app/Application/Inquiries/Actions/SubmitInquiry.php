<?php

declare(strict_types=1);

namespace App\Application\Inquiries\Actions;

use App\Events\Inquiries\InquirySubmitted;
use App\Models\Inquiry;
use Illuminate\Support\Facades\Log;

/**
 * Application use case responsible for receiving and persisting a public inquiry.
 */
class SubmitInquiry
{
    /**
     * @param  array{name: string, email: string, message: string, whatsapp?: string|null}  $attributes
     */
    public function handle(array $attributes): Inquiry
    {
        $inquiry = Inquiry::query()->create($attributes);
        $inquiry->refresh();

        InquirySubmitted::dispatch($inquiry);

        Log::info('Inquiry processed', [
            'inquiry_id' => $inquiry->id,
            'status' => $inquiry->status->value,
        ]);

        return $inquiry;
    }
}
