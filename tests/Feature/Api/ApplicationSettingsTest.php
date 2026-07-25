<?php

namespace Tests\Feature\Api;

use App\Models\ApplicationSettings;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

test('index returns the application-wide currency and accounting settings', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user, ['authenticate']);
    ApplicationSettings::get()->update(['currency_unit' => 'CHF']);
    ApplicationSettings::refreshCache();

    $response = $this->getJson('/api/application-settings');

    $response->assertSuccessful();
    $response->assertJsonPath('data.currency_unit', 'CHF');
});

test('index requires a valid sanctum ability', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user, ['refresh']);

    $response = $this->getJson('/api/application-settings');

    $response->assertForbidden();
});
