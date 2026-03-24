<?php

declare(strict_types=1);

namespace App\Notifications\Telegram;

use App\Models\Inquiry;
use App\Notifications\Telegram\Base\TelegramBaseNotification;
use NotificationChannels\Telegram\TelegramMessage;

class NewInquiryNotification extends TelegramBaseNotification
{
    public function __construct(
        private readonly Inquiry $inquiry
    ) {}

    protected function buildMessage(TelegramMessage $message): TelegramMessage
    {
        $content = implode('', [
            $this->line($this->bold('Nova mensagem recebida')),
            $this->separator(),
            $this->fields([
                'Nome' => $this->inquiry->name,
                'E-mail' => $this->inquiry->email,
                'WhatsApp' => $this->inquiry->whatsapp ?? '—',
            ]),
            $this->separator(),
            $this->bold('Mensagem:')."\n".$this->italic($this->inquiry->message),
            "\n\n",
            $this->code(
                $this->inquiry->created_at
                    ->setTimezone('America/Sao_Paulo')
                    ->format('d/m/Y \à\s H:i')
            ),
        ]);

        $message->content($content);

        if ($this->inquiry->whatsapp) {
            $message->button('Abrir no WhatsApp', $this->whatsAppLink());
        }

        if (app()->isProduction()) {
            $message->button('Abrir no painel', route('filament.admin.resources.inquiries.view', $this->inquiry));
        }

        return $message;
    }

    private function whatsAppLink(): string
    {
        return 'https://wa.me/'.$this->inquiry->whatsapp;
    }
}
