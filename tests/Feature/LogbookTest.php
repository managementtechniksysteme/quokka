<?php

namespace Tests\Feature;

use App\Models\Logbook;
use App\Models\User;
use App\Models\Vehicle;

function logbookUser(array $permissions = []): User
{
    $user = User::factory()->create();

    foreach ($permissions as $permission) {
        grantPermission($user, $permission);
    }

    return $user;
}

function ownLogbookEntry(User $user, array $attributes = []): Logbook
{
    return Logbook::factory()->create(array_merge(['employee_id' => $user->employee_id], $attributes));
}

function logbookPayload(array $overrides = []): array
{
    return array_merge([
        'driven_on' => '2026-01-01',
        'start_kilometres' => 100,
        'end_kilometres' => 150,
        'driven_kilometres' => 50,
        'origin' => 'Vienna',
        'destination' => 'Graz',
        'vehicle_id' => Vehicle::factory()->create()->id,
    ], $overrides);
}

// index

test('index is shown for a user with a view permission', function () {
    $user = logbookUser(['logbook.view.own']);

    $response = $this->actingAs($user)->get(route('logbook.index'));

    $response->assertSuccessful();
    $response->assertViewIs('logbook.index');
});

test('index is forbidden without any view permission', function () {
    $user = logbookUser();

    $response = $this->actingAs($user)->get(route('logbook.index'));

    $response->assertForbidden();
});

test('ajax index requires only_own for a view.own-only user', function () {
    $user = logbookUser(['logbook.view.own']);
    ownLogbookEntry($user);

    $response = $this->actingAs($user)
        ->withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
        ->getJson(route('logbook.index'));

    $response->assertStatus(422);
});

test('ajax index succeeds for a view.own-only user with only_own set', function () {
    $user = logbookUser(['logbook.view.own']);
    $own = ownLogbookEntry($user);
    Logbook::factory()->create();

    $response = $this->actingAs($user)
        ->withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
        ->getJson(route('logbook.index', ['only_own' => 1]));

    $response->assertSuccessful();
    expect(collect($response->json())->pluck('id'))->toEqual(collect([$own->id]));
});

test('ajax index excludes own entries for a view.other-only user', function () {
    $user = logbookUser(['logbook.view.other']);
    ownLogbookEntry($user);
    $other = Logbook::factory()->create();

    $response = $this->actingAs($user)
        ->withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
        ->getJson(route('logbook.index'));

    $response->assertSuccessful();
    expect(collect($response->json())->pluck('id'))->toEqual(collect([$other->id]));
});

// store

test('store creates a logbook entry', function () {
    $user = logbookUser(['logbook.create']);

    $response = $this->actingAs($user)->postJson(route('logbook.store'), logbookPayload());

    $response->assertCreated();
    $entry = Logbook::sole();
    expect($entry->employee_id)->toBe($user->employee_id);
    expect($entry->driven_kilometres)->toBe(50);
});

test('store is forbidden without create permission', function () {
    $user = logbookUser();

    $response = $this->actingAs($user)->postJson(route('logbook.store'), logbookPayload());

    $response->assertForbidden();
    expect(Logbook::count())->toBe(0);
});

test('store rejects a non-numeric kilometre value, instead of crashing', function () {
    $user = logbookUser(['logbook.create']);

    $response = $this->actingAs($user)->postJson(route('logbook.store'), logbookPayload([
        'end_kilometres' => 'not-a-number',
    ]));

    $response->assertStatus(422);
    expect(Logbook::count())->toBe(0);
});

test('store rejects inconsistent kilometre values', function () {
    $user = logbookUser(['logbook.create']);

    $response = $this->actingAs($user)->postJson(route('logbook.store'), logbookPayload([
        'driven_kilometres' => 999,
    ]));

    $response->assertStatus(422);
    expect(Logbook::count())->toBe(0);
});

// update

test('update is allowed for an own entry with update.own permission', function () {
    $user = logbookUser(['logbook.create', 'logbook.update.own']);
    $entry = ownLogbookEntry($user, ['vehicle_id' => Vehicle::factory()->create()->id]);

    $response = $this->actingAs($user)->putJson(route('logbook.update', $entry), logbookPayload([
        'vehicle_id' => $entry->vehicle_id,
        'driven_kilometres' => 75,
        'start_kilometres' => 100,
        'end_kilometres' => 175,
    ]));

    $response->assertNoContent();
    expect($entry->fresh()->driven_kilometres)->toBe(75);
});

test('update is forbidden for an own entry without update.own permission', function () {
    $user = logbookUser(['logbook.update.other']);
    $entry = ownLogbookEntry($user, ['vehicle_id' => Vehicle::factory()->create()->id]);

    $response = $this->actingAs($user)->putJson(route('logbook.update', $entry), logbookPayload([
        'vehicle_id' => $entry->vehicle_id,
    ]));

    $response->assertForbidden();
});

// destroy

test('destroy removes an own entry with delete.own permission', function () {
    $user = logbookUser(['logbook.delete.own']);
    $entry = ownLogbookEntry($user);

    $response = $this->actingAs($user)->deleteJson(route('logbook.destroy', $entry));

    $response->assertNoContent();
    expect(Logbook::find($entry->id))->toBeNull();
});

test('destroy is forbidden for an own entry without delete.own permission', function () {
    $user = logbookUser(['logbook.delete.other']);
    $entry = ownLogbookEntry($user);

    $response = $this->actingAs($user)->deleteJson(route('logbook.destroy', $entry));

    $response->assertForbidden();
    expect(Logbook::find($entry->id))->not->toBeNull();
});

// download authorization regression: 'download' was missing from resourceAbilityMap(),
// so any authenticated user could hit the route regardless of logbook.createpdf.

test('download is forbidden without createpdf permission', function () {
    $user = logbookUser(['logbook.view.own']);

    $response = $this->actingAs($user)->get(route('logbook.download'));

    $response->assertForbidden();
});

test('download renders a real pdf for an authorized user', function () {
    $user = logbookUser(['logbook.view.own', 'logbook.createpdf']);
    ownLogbookEntry($user);

    $response = $this->actingAs($user)->get(route('logbook.download', ['only_own' => 1]));

    $response->assertSuccessful();
    $response->assertHeader('content-type', 'application/pdf');
})->group('pdflatex');
