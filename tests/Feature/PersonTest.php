<?php

namespace Tests\Feature;

use App\Models\Person;
use App\Models\User;

function personUser(array $permissions = []): User
{
    $user = User::factory()->create();

    foreach ($permissions as $permission) {
        grantPermission($user, $permission);
    }

    return $user;
}

// index

test('index is shown for a user with view permission', function () {
    $user = personUser(['people.view']);

    $response = $this->actingAs($user)->get(route('people.index'));

    $response->assertSuccessful();
    $response->assertViewIs('person.index');
});

test('index is forbidden without view permission', function () {
    $user = personUser();

    $response = $this->actingAs($user)->get(route('people.index'));

    $response->assertForbidden();
});

// create / store

test('create form is shown for a user with create permission', function () {
    $user = personUser(['people.create']);

    $response = $this->actingAs($user)->get(route('people.create'));

    $response->assertSuccessful();
    $response->assertViewIs('person.create');
});

test('store creates a person', function () {
    $user = personUser(['people.create']);

    $response = $this->actingAs($user)->post(route('people.store'), [
        'first_name' => 'Jane',
        'last_name' => 'Doe',
        'gender' => 'female',
    ]);

    $person = Person::where('first_name', 'Jane')->sole();

    $response->assertRedirect(route('people.show', $person));
    expect($person->last_name)->toBe('Doe');
});

test('store is forbidden without create permission', function () {
    $user = personUser();
    $countBefore = Person::count();

    $response = $this->actingAs($user)->post(route('people.store'), [
        'first_name' => 'Jane',
        'last_name' => 'Doe',
        'gender' => 'female',
    ]);

    $response->assertForbidden();
    expect(Person::count())->toBe($countBefore);
});

// show

test('show is allowed with view permission', function () {
    $user = personUser(['people.view']);
    $person = Person::factory()->create();

    $response = $this->actingAs($user)->get(route('people.show', $person));

    $response->assertSuccessful();
    $response->assertViewIs('person.show');
});

test('show is forbidden without view permission', function () {
    $user = personUser();
    $person = Person::factory()->create();

    $response = $this->actingAs($user)->get(route('people.show', $person));

    $response->assertForbidden();
});

// edit

test('edit is shown for a user with update permission', function () {
    $user = personUser(['people.update']);
    $person = Person::factory()->create();

    $response = $this->actingAs($user)->get(route('people.edit', $person));

    $response->assertSuccessful();
    $response->assertViewIs('person.edit');
});

// update

test('update persists changes', function () {
    $user = personUser(['people.update']);
    $person = Person::factory()->create();

    $response = $this->actingAs($user)->put(route('people.update', $person), [
        'first_name' => 'Updated',
        'last_name' => $person->last_name,
        'gender' => $person->gender,
    ]);

    $response->assertRedirect(route('people.show', $person));
    expect($person->fresh()->first_name)->toBe('Updated');
});

test('update is forbidden without update permission', function () {
    $user = personUser();
    $person = Person::factory()->create();

    $response = $this->actingAs($user)->put(route('people.update', $person), [
        'first_name' => 'Updated',
        'last_name' => $person->last_name,
        'gender' => $person->gender,
    ]);

    $response->assertForbidden();
});

// destroy

test('destroy removes a person', function () {
    $user = personUser(['people.delete']);
    $person = Person::factory()->create();

    $response = $this->actingAs($user)->delete(route('people.destroy', $person));

    $response->assertRedirect(route('people.index'));
    expect(Person::find($person->id))->toBeNull();
});

test('destroy is forbidden without delete permission', function () {
    $user = personUser();
    $person = Person::factory()->create();

    $response = $this->actingAs($user)->delete(route('people.destroy', $person));

    $response->assertForbidden();
    expect(Person::find($person->id))->not->toBeNull();
});
