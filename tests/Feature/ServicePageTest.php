<?php

declare(strict_types=1);

use App\Models\Service;

it('shows services page with services from database', function () {
    Service::factory()->create(['title' => 'Serviço de Teste', 'active' => true, 'sort_order' => 1]);
    Service::factory()->create(['title' => 'Serviço Inativo', 'active' => false, 'sort_order' => 2]);

    $response = $this->get('/servicos');

    $response->assertOk();
    $response->assertInertia(
        fn ($page) => $page
            ->component('public/Services')
            ->has('services', 1)
            ->where('services.0.title', 'Serviço de Teste')
    );
});

it('shows homepage with services from database', function () {
    Service::factory()->create(['title' => 'Serviço Homepage', 'active' => true]);

    $response = $this->get('/');

    $response->assertOk();
    $response->assertInertia(
        fn ($page) => $page
            ->component('Welcome')
            ->has('services')
    );
});
