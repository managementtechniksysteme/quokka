<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Vehicle;

function vehicleUser(array $permissions = []): User
{
    $user = User::factory()->create();

    foreach ($permissions as $permission) {
        grantPermission($user, $permission);
    }

    return $user;
}

// index

test('index is shown for a user with view permission', function () {
    $user = vehicleUser(['vehicles.view']);

    $response = $this->actingAs($user)->get(route('vehicles.index'));

    $response->assertSuccessful();
    $response->assertViewIs('vehicle.index');
});

test('index is forbidden without view permission', function () {
    $user = vehicleUser();

    $response = $this->actingAs($user)->get(route('vehicles.index'));

    $response->assertForbidden();
});

// create / store

test('create form is shown for a user with create permission', function () {
    $user = vehicleUser(['vehicles.create']);

    $response = $this->actingAs($user)->get(route('vehicles.create'));

    $response->assertSuccessful();
    $response->assertViewIs('vehicle.create');
});

test('store creates a vehicle', function () {
    $user = vehicleUser(['vehicles.create']);

    $response = $this->actingAs($user)->post(route('vehicles.store'), [
        'make' => 'Ford',
        'model' => 'Transit',
        'registration_identifier' => 'V-12345',
        'private' => 0,
    ]);

    $vehicle = Vehicle::where('registration_identifier', 'V-12345')->sole();

    $response->assertRedirect(route('vehicles.show', $vehicle));
    expect($vehicle->make)->toBe('Ford');
});

test('store is forbidden without create permission', function () {
    $user = vehicleUser();
    $countBefore = Vehicle::count();

    $response = $this->actingAs($user)->post(route('vehicles.store'), [
        'make' => 'Ford',
        'model' => 'Transit',
        'registration_identifier' => 'V-12345',
        'private' => 0,
    ]);

    $response->assertForbidden();
    expect(Vehicle::count())->toBe($countBefore);
});

// show

test('show is allowed with view permission', function () {
    $user = vehicleUser(['vehicles.view']);
    $vehicle = Vehicle::factory()->create();

    $response = $this->actingAs($user)->get(route('vehicles.show', $vehicle));

    $response->assertSuccessful();
    $response->assertViewIs('vehicle.show');
});

test('show is forbidden without view permission', function () {
    $user = vehicleUser();
    $vehicle = Vehicle::factory()->create();

    $response = $this->actingAs($user)->get(route('vehicles.show', $vehicle));

    $response->assertForbidden();
});

// edit

test('edit is shown for a user with update permission', function () {
    $user = vehicleUser(['vehicles.update']);
    $vehicle = Vehicle::factory()->create();

    $response = $this->actingAs($user)->get(route('vehicles.edit', $vehicle));

    $response->assertSuccessful();
    $response->assertViewIs('vehicle.edit');
});

// update

test('update persists changes', function () {
    $user = vehicleUser(['vehicles.update']);
    $vehicle = Vehicle::factory()->create();

    $response = $this->actingAs($user)->put(route('vehicles.update', $vehicle), [
        'make' => 'Updated make',
        'model' => $vehicle->model,
        'registration_identifier' => $vehicle->registration_identifier,
        'private' => 0,
    ]);

    $response->assertRedirect(route('vehicles.show', $vehicle));
    expect($vehicle->fresh()->make)->toBe('Updated make');
});

test('update is forbidden without update permission', function () {
    $user = vehicleUser();
    $vehicle = Vehicle::factory()->create();

    $response = $this->actingAs($user)->put(route('vehicles.update', $vehicle), [
        'make' => 'Updated make',
        'model' => $vehicle->model,
        'registration_identifier' => $vehicle->registration_identifier,
        'private' => 0,
    ]);

    $response->assertForbidden();
});

// destroy

test('destroy removes a vehicle', function () {
    $user = vehicleUser(['vehicles.delete']);
    $vehicle = Vehicle::factory()->create();

    $response = $this->actingAs($user)->delete(route('vehicles.destroy', $vehicle));

    $response->assertRedirect(route('vehicles.index'));
    expect(Vehicle::find($vehicle->id))->toBeNull();
});

test('destroy is forbidden without delete permission', function () {
    $user = vehicleUser();
    $vehicle = Vehicle::factory()->create();

    $response = $this->actingAs($user)->delete(route('vehicles.destroy', $vehicle));

    $response->assertForbidden();
    expect(Vehicle::find($vehicle->id))->not->toBeNull();
});
