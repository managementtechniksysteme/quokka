<?php

namespace Tests\Feature;

use App\Models\User;

function financeUser(array $permissions = []): User
{
    $user = User::factory()->create();

    foreach ($permissions as $permission) {
        grantPermission($user, $permission);
    }

    return $user;
}

test('index is shown for a user with finances.view permission', function () {
    $user = financeUser(['finances.view']);

    $response = $this->actingAs($user)->get(route('finances.index'));

    $response->assertSuccessful();
    $response->assertViewIs('finance.index');
});

test('index is forbidden without finances.view permission', function () {
    $user = financeUser();

    $response = $this->actingAs($user)->get(route('finances.index'));

    $response->assertForbidden();
});

test('download is forbidden without finances.createpdf permission', function () {
    $user = financeUser(['finances.view']);

    $response = $this->actingAs($user)->get(route('finances.download'));

    $response->assertForbidden();
});

test('download renders a real pdf for an authorized user', function () {
    $user = financeUser(['finances.createpdf']);

    $response = $this->actingAs($user)->get(route('finances.download'));

    $response->assertSuccessful();
    $response->assertHeader('content-type', 'application/pdf');
})->group('pdflatex');
