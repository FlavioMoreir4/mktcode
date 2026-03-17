<?php

namespace App\Observers;

use App\Models\Inquiry;
use App\Notifications\Telegram\NewInquiryNotification;
use App\Services\Telegram\TelegramBotTarget;
use App\Services\Telegram\TelegramNotifier;

class InquiryObserver
{
    public function __construct(
        private readonly TelegramNotifier $telegramNotifier
    ) {}

    public function created(Inquiry $inquiry): void
    {
        $this->telegramNotifier->sendTo(TelegramBotTarget::default()->chatId, new NewInquiryNotification($inquiry));
    }
}
