<?php

declare(strict_types=1);

namespace App\Events\Inquiries;

use App\Models\Inquiry;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class InquirySubmitted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public readonly Inquiry $inquiry) {}
}
