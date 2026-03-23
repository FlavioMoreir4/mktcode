<?php

declare(strict_types=1);

namespace App\Listeners\Inquiries;

use App\Events\Inquiries\InquirySubmitted;
use App\Notifications\Telegram\NewInquiryNotification;
use App\Services\Telegram\TelegramNotifier;

/**
 * Sends operational notifications for newly submitted public inquiries.
 */
class SendInquirySubmittedNotification
{
    public function __construct(private readonly TelegramNotifier $telegramNotifier) {}

    public function handle(InquirySubmitted $event): void
    {
        $this->telegramNotifier->send(new NewInquiryNotification($event->inquiry));
    }
}
