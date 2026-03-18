<?php

declare(strict_types=1);

namespace App\Notifications\Telegram\Base;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramChannel;
use NotificationChannels\Telegram\TelegramMessage;

abstract class TelegramBaseNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function via(object $notifiable): array
    {
        return [TelegramChannel::class];
    }

    public function toTelegram(object $notifiable): TelegramMessage
    {
        return $this->buildMessage(
            TelegramMessage::create()->to($notifiable->routeNotificationForTelegram())
        );
    }

    public function toArray(object $notifiable): array
    {
        return [];
    }

    /**
     * Cada notification concreta implementa apenas o conteúdo.
     */
    abstract protected function buildMessage(TelegramMessage $message): TelegramMessage;

    // ─── Helpers de formatação Markdown ──────────────────────────────────────

    protected function bold(string $text): string
    {
        return "*{$text}*";
    }

    protected function italic(string $text): string
    {
        return "_{$text}_";
    }

    protected function code(string $text): string
    {
        return "`{$text}`";
    }

    protected function line(string $text = ''): string
    {
        return $text."\n";
    }

    protected function separator(): string
    {
        return "\n—————————————\n";
    }

    /**
     * Monta um bloco de campos chave → valor formatado.
     *
     * @param  array<string, string>  $fields
     */
    protected function fields(array $fields): string
    {
        return collect($fields)
            ->map(fn ($value, $label) => "{$this->bold($label.':')} {$value}")
            ->implode("\n");
    }
}
