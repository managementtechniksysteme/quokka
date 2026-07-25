<?php

namespace Tests\Feature;

use App\Models\User;

test('index is forbidden without tools.scanqr permission', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('qr-scan.index'));

    $response->assertForbidden();
});

test('index is shown for a user with tools.scanqr permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'tools.scanqr');

    $response = $this->actingAs($user)->get(route('qr-scan.index'));

    $response->assertSuccessful();
    $response->assertViewIs('qr-scan');
});
