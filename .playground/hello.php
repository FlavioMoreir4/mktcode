<?php

// FIXME Gerar novo token

use App\Models\Inquiry;
use App\Notifications\Telegram\NewInquiryNotification;
use App\Services\Telegram\TelegramBotTarget;
use App\Services\Telegram\TelegramNotifier;
use Illuminate\Support\Facades\Log;

$inquiry = Inquiry::latest()->first();

if (! $inquiry) {
    Log::info('Nenhum');

    return false;
}

$notifier = new TelegramNotifier(TelegramBotTarget::default());
$notifier->send(new NewInquiryNotification($inquiry));
