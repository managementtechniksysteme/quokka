<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Models\User;

function addressUser(array $permissions = []): User
{
    $user = User::factory()->create();

    foreach ($permissions as $permission) {
        grantPermission($user, $permission);
    }

    return $user;
}

// index

test('index is shown for a user with view permission', function () {
    $user = addressUser(['addresses.view']);

    $response = $this->actingAs($user)->get(route('addresses.index'));

    $response->assertSuccessful();
    $response->assertViewIs('address.index');
});

test('index is forbidden without view permission', function () {
    $user = addressUser();

    $response = $this->actingAs($user)->get(route('addresses.index'));

    $response->assertForbidden();
});

// create / store

test('create form is shown for a user with create permission', function () {
    $user = addressUser(['addresses.create']);

    $response = $this->actingAs($user)->get(route('addresses.create'));

    $response->assertSuccessful();
    $response->assertViewIs('address.create');
});

test('store creates an address', function () {
    $user = addressUser(['addresses.create']);

    $response = $this->actingAs($user)->post(route('addresses.store'), [
        'name' => 'HQ',
        'street_number' => 'Main St 1',
        'postcode' => '1010',
        'city' => 'Vienna',
    ]);

    $address = Address::where('name', 'HQ')->sole();

    $response->assertRedirect(route('addresses.show', $address));
    expect($address->city)->toBe('Vienna');
});

test('store is forbidden without create permission', function () {
    $user = addressUser();
    $countBefore = Address::count();

    $response = $this->actingAs($user)->post(route('addresses.store'), [
        'name' => 'HQ',
        'street_number' => 'Main St 1',
        'postcode' => '1010',
        'city' => 'Vienna',
    ]);

    $response->assertForbidden();
    expect(Address::count())->toBe($countBefore);
});

// show

test('show is allowed with view permission', function () {
    $user = addressUser(['addresses.view']);
    $address = Address::factory()->create();

    $response = $this->actingAs($user)->get(route('addresses.show', $address));

    $response->assertSuccessful();
    $response->assertViewIs('address.show');
});

test('show is forbidden without view permission', function () {
    $user = addressUser();
    $address = Address::factory()->create();

    $response = $this->actingAs($user)->get(route('addresses.show', $address));

    $response->assertForbidden();
});

// edit

test('edit is shown for a user with update permission', function () {
    $user = addressUser(['addresses.update']);
    $address = Address::factory()->create();

    $response = $this->actingAs($user)->get(route('addresses.edit', $address));

    $response->assertSuccessful();
    $response->assertViewIs('address.edit');
});

// update

test('update persists changes', function () {
    $user = addressUser(['addresses.update']);
    $address = Address::factory()->create();

    $response = $this->actingAs($user)->put(route('addresses.update', $address), [
        'name' => 'Updated name',
        'street_number' => $address->street_number,
        'postcode' => $address->postcode,
        'city' => $address->city,
    ]);

    $response->assertRedirect(route('addresses.show', $address));
    expect($address->fresh()->name)->toBe('Updated name');
});

test('update is forbidden without update permission', function () {
    $user = addressUser();
    $address = Address::factory()->create();

    $response = $this->actingAs($user)->put(route('addresses.update', $address), [
        'name' => 'Updated name',
        'street_number' => $address->street_number,
        'postcode' => $address->postcode,
        'city' => $address->city,
    ]);

    $response->assertForbidden();
});

// destroy

test('destroy removes an address', function () {
    $user = addressUser(['addresses.delete']);
    $address = Address::factory()->create();

    $response = $this->actingAs($user)->delete(route('addresses.destroy', $address));

    $response->assertRedirect(route('addresses.index'));
    expect(Address::find($address->id))->toBeNull();
});

test('destroy is forbidden without delete permission', function () {
    $user = addressUser();
    $address = Address::factory()->create();

    $response = $this->actingAs($user)->delete(route('addresses.destroy', $address));

    $response->assertForbidden();
    expect(Address::find($address->id))->not->toBeNull();
});
