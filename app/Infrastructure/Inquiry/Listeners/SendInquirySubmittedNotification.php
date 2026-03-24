<?php

declare(strict_types=1);

namespace App\Infrastructure\Inquiry\Listeners;

use App\Events\Inquiries\InquirySubmitted;
use App\Notifications\Telegram\NewInquiryNotification;
use App\Services\Telegram\TelegramNotifier;

/**
 * Sends operational notifications after a public inquiry enters the workflow.
 */
class SendInquirySubmittedNotification
{
    public function __construct(private readonly TelegramNotifier $telegramNotifier) {}

    public function handle(InquirySubmitted $event): void
    {
        $this->telegramNotifier->send(new NewInquiryNotification($event->inquiry));
    }
}
