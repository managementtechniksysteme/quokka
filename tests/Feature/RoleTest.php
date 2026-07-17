<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Spatie\Permission\Models\Role;

function roleUser(array $permissions = []): User
{
    $user = User::factory()->create();

    foreach ($permissions as $permission) {
        grantPermission($user, $permission);
    }

    return $user;
}

function otherRole(array $attributes = []): Role
{
    return Role::create(array_merge(['name' => fake()->unique()->word], $attributes));
}

// index

test('index is shown for a user with view permission', function () {
    $user = roleUser(['roles.view']);

    $response = $this->actingAs($user)->get(route('roles.index'));

    $response->assertSuccessful();
    $response->assertViewIs('role.index');
});

test('index is forbidden without view permission', function () {
    $user = roleUser();

    $response = $this->actingAs($user)->get(route('roles.index'));

    $response->assertForbidden();
});

// create / store

// role/create and role/edit render a hand-written checkbox per permission (permission/fields.blade.php),
// calling hasPermissionTo() against every permission string in the app - unlike every other domain's
// views, these two genuinely need the full permission catalog seeded, not just the couple of
// permissions each test cares about.
test('create form is shown for a user with create permission', function () {
    $this->seed(RolesAndPermissionsSeeder::class);
    $user = roleUser(['roles.create']);

    $response = $this->actingAs($user)->get(route('roles.create'));

    $response->assertSuccessful();
    $response->assertViewIs('role.create');
});

test('store creates a role', function () {
    $user = roleUser(['roles.create']);

    $response = $this->actingAs($user)->post(route('roles.store'), [
        'name' => 'Custom Role',
    ]);

    $role = Role::where('name', 'Custom Role')->sole();

    $response->assertRedirect(route('roles.show', $role));
});

test('store grants the selected permissions', function () {
    $user = roleUser(['roles.create']);
    grantPermission($user, 'vehicles.view');

    $this->actingAs($user)->post(route('roles.store'), [
        'name' => 'Custom Role',
        'vehicles_view' => 1,
    ]);

    $role = Role::where('name', 'Custom Role')->sole();

    expect($role->hasPermissionTo('vehicles.view'))->toBeTrue();
});

test('store is forbidden without create permission', function () {
    $user = roleUser();
    $countBefore = Role::count();

    $response = $this->actingAs($user)->post(route('roles.store'), [
        'name' => 'Custom Role',
    ]);

    $response->assertForbidden();
    expect(Role::count())->toBe($countBefore);
});

// show

test('show is allowed with view permission', function () {
    $this->seed(RolesAndPermissionsSeeder::class);
    $user = roleUser(['roles.view']);
    $role = otherRole();

    $response = $this->actingAs($user)->get(route('roles.show', $role));

    $response->assertSuccessful();
    $response->assertViewIs('role.show');
});

test('show is forbidden without view permission', function () {
    $user = roleUser();
    $role = otherRole();

    $response = $this->actingAs($user)->get(route('roles.show', $role));

    $response->assertForbidden();
});

// edit

test('edit is shown for a user with update permission', function () {
    $this->seed(RolesAndPermissionsSeeder::class);
    $user = roleUser(['roles.update']);
    $role = otherRole();

    $response = $this->actingAs($user)->get(route('roles.edit', $role));

    $response->assertSuccessful();
    $response->assertViewIs('role.edit');
});

// update

test('update persists the name change', function () {
    $user = roleUser(['roles.update']);
    $role = otherRole();

    $response = $this->actingAs($user)->put(route('roles.update', $role), [
        'name' => 'Renamed role',
    ]);

    $response->assertRedirect(route('roles.show', $role));
    expect($role->fresh()->name)->toBe('Renamed role');
});

test('update syncs permissions, revoking ones no longer selected', function () {
    $user = roleUser(['roles.update']);
    $role = otherRole();
    \Spatie\Permission\Models\Permission::firstOrCreate(['name' => 'vehicles.view']);
    $role->givePermissionTo('vehicles.view');

    $this->actingAs($user)->put(route('roles.update', $role), [
        'name' => $role->name,
    ]);

    expect($role->fresh()->hasPermissionTo('vehicles.view'))->toBeFalse();
});

test('update is forbidden without update permission', function () {
    $user = roleUser();
    $role = otherRole();

    $response = $this->actingAs($user)->put(route('roles.update', $role), [
        'name' => 'Renamed role',
    ]);

    $response->assertForbidden();
    expect($role->fresh()->name)->not->toBe('Renamed role');
});

// destroy

test('destroy removes a role', function () {
    $user = roleUser(['roles.delete']);
    $role = otherRole();

    $response = $this->actingAs($user)->delete(route('roles.destroy', $role));

    $response->assertRedirect(route('roles.index'));
    expect(Role::find($role->id))->toBeNull();
});

test('destroy is forbidden without delete permission', function () {
    $user = roleUser();
    $role = otherRole();

    $response = $this->actingAs($user)->delete(route('roles.destroy', $role));

    $response->assertForbidden();
    expect(Role::find($role->id))->not->toBeNull();
});
