<?php

declare(strict_types=1);

use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\post;

it('persists a contact inquiry in the database', function () {
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
});

it('validates required fields for inquiry persistence', function () {
    $response = $this->from('/contato')->post('/inquiry', []);

    $response->assertStatus(302)
        ->assertRedirect('/contato')
        ->assertSessionHasErrors(['name', 'email', 'message']);

    assertDatabaseCount('inquiries', 0);
});
