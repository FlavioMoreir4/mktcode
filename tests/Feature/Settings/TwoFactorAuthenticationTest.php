<?php

declare(strict_types=1);

use App\Models\User;
use Laravel\Fortify\Features;

test('two factor settings page can be rendered', function () {
    $this->skipUnlessFortifyFeature(Features::twoFactorAuthentication());

    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('two-factor.show'))
        ->assertRedirect('/admin/profile');
});

test('two factor settings route remains a bridge to Filament when confirmation is enabled', function () {
    $this->skipUnlessFortifyFeature(Features::twoFactorAuthentication());

    $user = User::factory()->create();

    Features::twoFactorAuthentication([
        'confirm' => true,
        'confirmPassword' => true,
    ]);

    $response = $this->actingAs($user)
        ->get(route('two-factor.show'));

    $response->assertRedirect('/admin/profile');
});

test('two factor settings route remains a bridge to Filament when confirmation is disabled', function () {
    $this->skipUnlessFortifyFeature(Features::twoFactorAuthentication());

    $user = User::factory()->create();

    Features::twoFactorAuthentication([
        'confirm' => true,
        'confirmPassword' => false,
    ]);

    $this->actingAs($user)
        ->get(route('two-factor.show'))
        ->assertRedirect('/admin/profile');
});

test('two factor settings route still redirects to Filament when feature is disabled', function () {
    $this->skipUnlessFortifyFeature(Features::twoFactorAuthentication());

    config(['fortify.features' => []]);

    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('two-factor.show'))
        ->assertRedirect('/admin/profile');
});
