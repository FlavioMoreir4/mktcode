<?php

declare(strict_types=1);

use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\post;
use App\Notifications\Telegram\NewInquiryNotification;
use App\Services\Telegram\TelegramBotTarget;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

it('persists a contact inquiry in the database', function () {
    Notification::fake();
    Log::spy();

    $data = [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'whatsapp' => '11999999999',
        'message' => 'This is a test message for persistence.',
    ];

    $response = post('/inquiry', $data);

    $response->assertRedirect()
        ->assertSessionHas('success', 'Mensagem enviada com sucesso! Entraremos em contato em breve.');

    assertDatabaseCount('inquiries', 1);
    assertDatabaseHas('inquiries', [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'whatsapp' => '11999999999',
        'message' => 'This is a test message for persistence.',
    ]);

    Notification::assertSentTo(
        new TelegramBotTarget(config('services.telegram.default_chat_id')),
        NewInquiryNotification::class
    );

    Log::shouldHaveReceived('info')
        ->once()
        ->withArgs(function (string $message, array $context): bool {
            return $message === 'Inquiry processed'
                && array_key_exists('inquiry_id', $context)
                && array_key_exists('status', $context)
                && ! array_key_exists('email', $context)
                && ! array_key_exists('message', $context)
                && ! array_key_exists('whatsapp', $context);
        });
});

it('validates required fields for inquiry persistence', function () {
    $response = $this->from('/contato')->post('/inquiry', []);

    $response->assertStatus(302)
        ->assertRedirect('/contato')
        ->assertSessionHasErrors(['name', 'email', 'message']);

    assertDatabaseCount('inquiries', 0);
});
