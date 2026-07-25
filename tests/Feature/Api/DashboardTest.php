<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Laravel\Sanctum\Sanctum;

test('index is shown for any authenticated user', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user, ['authenticate']);

    $response = $this->getJson('/api/dashboard');

    $response->assertSuccessful();
    $response->assertJsonStructure(['data' => ['holidays', 'mtd_kilometres']]);
});

test('index requires a valid sanctum ability', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user, ['refresh']);

    $response = $this->getJson('/api/dashboard');

    $response->assertForbidden();
});

test('index omits signed-report totals without approve permission', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user, ['authenticate']);

    $response = $this->getJson('/api/dashboard');

    $response->assertJsonMissingPath('data.signed_service_reports');
});

test('index includes signed-report totals with approve permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'service-reports.approve');
    Sanctum::actingAs($user, ['authenticate']);

    $response = $this->getJson('/api/dashboard');

    $response->assertJsonPath('data.signed_service_reports', 0);
});
