<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Laravel\Sanctum\Sanctum;

test('index returns the acting user\'s profile and permissions', function () {
    $user = User::factory()->create();
    grantPermission($user, 'accounting.view.own');
    Sanctum::actingAs($user, ['authenticate']);

    $response = $this->getJson('/api/user');

    $response->assertSuccessful();
    $response->assertJsonPath('data.username', $user->username);
    $response->assertJsonPath('data.permissions', ['accounting.view.own']);
});

test('index requires a valid sanctum ability', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user, ['refresh']);

    $response = $this->getJson('/api/user');

    $response->assertForbidden();
});
