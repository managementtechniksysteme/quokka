<?php

namespace Tests\Feature;

use App\Models\User;

function projectFinanceUser(array $permissions = []): User
{
    $user = User::factory()->create();

    foreach ($permissions as $permission) {
        grantPermission($user, $permission);
    }

    return $user;
}

test('index is shown for a user with finances.view permission', function () {
    $user = projectFinanceUser(['finances.view']);

    $response = $this->actingAs($user)->get(route('project-finances.index'));

    $response->assertSuccessful();
    $response->assertViewIs('finance.project_overview');
});

test('index is forbidden without finances.view permission', function () {
    $user = projectFinanceUser();

    $response = $this->actingAs($user)->get(route('project-finances.index'));

    $response->assertForbidden();
});

test('download is forbidden without finances.createpdf permission', function () {
    $user = projectFinanceUser(['finances.view']);

    $response = $this->actingAs($user)->get(route('project-finances.download'));

    $response->assertForbidden();
});

test('download renders a real pdf for an authorized user', function () {
    $user = projectFinanceUser(['finances.createpdf']);

    $response = $this->actingAs($user)->get(route('project-finances.download'));

    $response->assertSuccessful();
    $response->assertHeader('content-type', 'application/pdf');
})->group('pdflatex');
