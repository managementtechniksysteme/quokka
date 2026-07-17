<?php

namespace Tests\Feature;

use App\Models\User;

test('show is forbidden without help.view permission', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('changelog.show'));

    $response->assertForbidden();
});

test('show is shown for a user with help.view permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'help.view');

    $response = $this->actingAs($user)->get(route('changelog.show'));

    $response->assertSuccessful();
    $response->assertViewIs('help.changelog');
});
