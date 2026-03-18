<?php

declare(strict_types=1);

namespace App\Services\Telegram;

use Illuminate\Notifications\Notification;

class TelegramNotifier
{
    public function __construct(
        private readonly TelegramBotTarget $target
    ) {}

    public function send(Notification $notification): void
    {
        $this->target->notify($notification);
    }

    /**
     * Disparo para um chat específico (sobrepõe o padrão).
     */
    public function sendTo(string $chatId, Notification $notification): void
    {
        (new TelegramBotTarget($chatId))->notify($notification);
    }
}
