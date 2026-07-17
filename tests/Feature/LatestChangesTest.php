<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\User;

test('index is forbidden without tools.viewlatestchanges permission', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('latest-changes.index'));

    $response->assertForbidden();
});

test('index lists a recently changed company for an authorized user', function () {
    $user = User::factory()->create();
    grantPermission($user, 'tools.viewlatestchanges');
    grantPermission($user, 'companies.view');
    $company = Company::factory()->create(['name' => 'Recently Changed AG']);

    $response = $this->actingAs($user)->get(route('latest-changes.index'));

    $response->assertSuccessful();
    $response->assertViewIs('latest_changes.index');
    $response->assertSee($company->name);
});

test('index omits a recently changed company without viewAny permission for it', function () {
    $user = User::factory()->create();
    grantPermission($user, 'tools.viewlatestchanges');
    $company = Company::factory()->create(['name' => 'Recently Changed AG']);

    $response = $this->actingAs($user)->get(route('latest-changes.index'));

    $response->assertSuccessful();
    $response->assertDontSee($company->name);
});
