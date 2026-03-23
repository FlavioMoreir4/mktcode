<?php

declare(strict_types=1);

namespace App\Services\Telegram;

use Illuminate\Notifications\Notifiable;

/**
 * Representa o destino fixo do bot.
 * Não é um Eloquent Model — é um Value Object notifiable.
 */
class TelegramBotTarget
{
    use Notifiable;

    public function __construct(
        public readonly string $chatId
    ) {}

    public static function default(): self
    {
        return new self(config('services.telegram.default_chat_id'));
    }

    /**
     * Obrigatório pelo canal do Telegram.
     * O canal chama $notifiable->routeNotificationFor('telegram')
     */
    public function routeNotificationForTelegram(): string
    {
        return $this->chatId;
    }

    public function getKey(): string
    {
        return $this->chatId;
    }
}
