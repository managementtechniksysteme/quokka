<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Note;
use App\Models\User;

// search

test('search is forbidden without search permission', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('cross-references.search', ['query' => 'anything']));

    $response->assertForbidden();
});

test('search finds a matching record for a user with viewAny permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'search');
    grantPermission($user, 'companies.view');
    $company = Company::factory()->create(['name' => 'Findable Construction AG']);

    $response = $this->actingAs($user)->get(route('cross-references.search', ['query' => 'Findable Construction']));

    $response->assertSuccessful();
    $response->assertJsonFragment(['name' => $company->name]);
});

test('search omits a matching record without viewAny permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'search');
    $company = Company::factory()->create(['name' => 'Findable Construction AG']);

    $response = $this->actingAs($user)->get(route('cross-references.search', ['query' => 'Findable Construction']));

    $response->assertSuccessful();
    $response->assertJsonMissing(['name' => $company->name]);
});

test('search returns an empty array for a blank query', function () {
    $user = User::factory()->create();
    grantPermission($user, 'search');

    $response = $this->actingAs($user)->get(route('cross-references.search', ['query' => '']));

    $response->assertSuccessful();
    $response->assertExactJson([]);
});

// resolve

test('resolve is forbidden without search permission', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('cross-references.resolve'), ['tokens' => []]);

    $response->assertForbidden();
});

test('resolve returns data for a token the user can view', function () {
    $user = User::factory()->create();
    grantPermission($user, 'search');
    $note = Note::factory()->create(['employee_id' => $user->employee_id]);

    $response = $this->actingAs($user)->post(route('cross-references.resolve'), ['tokens' => ["note-{$note->id}"]]);

    $response->assertSuccessful();
    $response->assertJsonStructure(["note-{$note->id}" => ['token', 'type', 'name', 'route', 'icon']]);
});

test('resolve omits a token for a note the user does not own', function () {
    $user = User::factory()->create();
    grantPermission($user, 'search');
    $note = Note::factory()->create();

    $response = $this->actingAs($user)->post(route('cross-references.resolve'), ['tokens' => ["note-{$note->id}"]]);

    $response->assertSuccessful();
    $response->assertExactJson([]);
});

test('resolve ignores a malformed token', function () {
    $user = User::factory()->create();
    grantPermission($user, 'search');

    $response = $this->actingAs($user)->post(route('cross-references.resolve'), ['tokens' => ['garbage']]);

    $response->assertSuccessful();
    $response->assertExactJson([]);
});
