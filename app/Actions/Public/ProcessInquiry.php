<?php

declare(strict_types=1);

namespace App\Actions\Public;

use App\Models\Inquiry;
use Illuminate\Support\Facades\Log;

class ProcessInquiry
{
    /**
     * Process the inquiry.
     *
     * @param  array<string, string>  $data
     */
    public function execute(array $data): void
    {
        $inquiry = Inquiry::create($data);

        Log::info('Inquiry processed', [
            'inquiry_id' => $inquiry->id,
            'status' => $inquiry->status->value,
        ]);
    }
}
