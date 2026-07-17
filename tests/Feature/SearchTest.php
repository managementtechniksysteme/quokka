<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\User;

test('index is forbidden without search permission', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('search.index', ['query' => 'anything']));

    $response->assertForbidden();
});

test('index finds a matching company for a user with companies.view permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'search');
    grantPermission($user, 'companies.view');
    $company = Company::factory()->create(['name' => 'Findable Construction AG']);

    $response = $this->actingAs($user)->get(route('search.index', ['query' => 'Findable Construction']));

    $response->assertSuccessful();
    $response->assertViewIs('search.index');
    $response->assertSee($company->name);
});

test('index omits a matching company without companies.view permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'search');
    $company = Company::factory()->create(['name' => 'Findable Construction AG']);

    $response = $this->actingAs($user)->get(route('search.index', ['query' => 'Findable Construction']));

    $response->assertSuccessful();
    $response->assertDontSee($company->name);
});
