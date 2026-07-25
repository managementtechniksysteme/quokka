<?php

namespace Tests\Feature\Api;

use App\Models\Logbook;
use App\Models\User;
use App\Models\Vehicle;
use Laravel\Sanctum\Sanctum;

function apiLogbookUser(array $permissions = []): User
{
    $user = User::factory()->create();

    foreach ($permissions as $permission) {
        grantPermission($user, $permission);
    }

    Sanctum::actingAs($user, ['authenticate']);

    return $user;
}

function ownLogbookEntry(User $user, array $attributes = []): Logbook
{
    return Logbook::factory()->create(array_merge(['employee_id' => $user->employee_id], $attributes));
}

test('index is forbidden without any view permission', function () {
    apiLogbookUser();

    $response = $this->getJson('/api/logbook');

    $response->assertForbidden();
});

test('index requires only_own when the user can only view their own entries', function () {
    apiLogbookUser(['logbook.view.own']);

    $response = $this->getJson('/api/logbook');

    $response->assertUnprocessable();
    $response->assertJsonValidationErrors('only_own');
});

test('index lists only own entries with only_own', function () {
    $user = apiLogbookUser(['logbook.view.own']);
    $mine = ownLogbookEntry($user);
    Logbook::factory()->create();

    $response = $this->getJson('/api/logbook?only_own=1');

    $response->assertSuccessful();
    $response->assertJsonCount(1, 'data');
    $response->assertJsonPath('data.0.id', $mine->id);
});

test('store creates a logbook entry owned by the acting user', function () {
    $user = apiLogbookUser(['logbook.create']);
    $vehicle = Vehicle::factory()->create();

    $response = $this->postJson('/api/logbook', [
        'driven_on' => now()->toDateString(),
        'start_kilometres' => 100,
        'end_kilometres' => 150,
        'driven_kilometres' => 50,
        'origin' => 'A',
        'destination' => 'B',
        'vehicle_id' => $vehicle->id,
    ]);

    $response->assertCreated();
    $this->assertDatabaseHas('logbook', [
        'employee_id' => $user->employee_id,
        'vehicle_id' => $vehicle->id,
    ]);
});

test('store validates the kilometre fields are internally consistent', function () {
    apiLogbookUser(['logbook.create']);
    $vehicle = Vehicle::factory()->create();

    $response = $this->postJson('/api/logbook', [
        'driven_on' => now()->toDateString(),
        'start_kilometres' => 100,
        'end_kilometres' => 150,
        'driven_kilometres' => 999,
        'origin' => 'A',
        'destination' => 'B',
        'vehicle_id' => $vehicle->id,
    ]);

    $response->assertJsonValidationErrors('driven_kilometres');
});

test('show is forbidden for someone else\'s entry without view.other permission', function () {
    apiLogbookUser(['logbook.view.own']);
    $logbook = Logbook::factory()->create();

    $response = $this->getJson("/api/logbook/{$logbook->id}");

    $response->assertForbidden();
});

test('update persists changes', function () {
    $user = apiLogbookUser(['logbook.update.own']);
    $logbook = ownLogbookEntry($user, ['origin' => 'Old origin']);

    $response = $this->putJson("/api/logbook/{$logbook->id}", [
        'driven_on' => $logbook->driven_on->toDateString(),
        'start_kilometres' => $logbook->start_kilometres,
        'end_kilometres' => $logbook->end_kilometres,
        'driven_kilometres' => $logbook->driven_kilometres,
        'origin' => 'New origin',
        'destination' => $logbook->destination,
        'vehicle_id' => $logbook->vehicle_id,
    ]);

    $response->assertSuccessful();
    expect($logbook->fresh()->origin)->toBe('New origin');
});

test('destroy removes your own entry', function () {
    $user = apiLogbookUser(['logbook.delete.own']);
    $logbook = ownLogbookEntry($user);

    $response = $this->deleteJson("/api/logbook/{$logbook->id}");

    $response->assertSuccessful();
    $this->assertModelMissing($logbook);
});

test('destroy is forbidden for someone else\'s entry without delete.other permission', function () {
    apiLogbookUser(['logbook.delete.own']);
    $logbook = Logbook::factory()->create();

    $response = $this->deleteJson("/api/logbook/{$logbook->id}");

    $response->assertForbidden();
    $this->assertModelExists($logbook);
});
