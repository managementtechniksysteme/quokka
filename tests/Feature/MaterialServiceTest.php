<?php

namespace Tests\Feature;

use App\Models\MaterialService;
use App\Models\User;

function materialServiceUser(array $permissions = []): User
{
    $user = User::factory()->create();

    foreach ($permissions as $permission) {
        grantPermission($user, $permission);
    }

    return $user;
}

// index

test('index is shown for a user with view permission', function () {
    $user = materialServiceUser(['material-services.view']);

    $response = $this->actingAs($user)->get(route('material-services.index'));

    $response->assertSuccessful();
    $response->assertViewIs('service.index_tab_material_services');
});

test('index is forbidden without view permission', function () {
    $user = materialServiceUser();

    $response = $this->actingAs($user)->get(route('material-services.index'));

    $response->assertForbidden();
});

// create / store

test('create form is shown for a user with create permission', function () {
    $user = materialServiceUser(['material-services.create']);

    $response = $this->actingAs($user)->get(route('material-services.create'));

    $response->assertSuccessful();
    $response->assertViewIs('material_service.create');
});

test('store creates a material service', function () {
    $user = materialServiceUser(['material-services.create']);

    $response = $this->actingAs($user)->post(route('material-services.store'), [
        'name' => 'Concrete',
        'description' => 'Ready-mix concrete',
    ]);

    $materialService = MaterialService::sole();

    $response->assertRedirect(route('material-services.show', $materialService));
    expect($materialService->name)->toBe('Concrete');
});

test('store is forbidden without create permission', function () {
    $user = materialServiceUser();

    $response = $this->actingAs($user)->post(route('material-services.store'), [
        'name' => 'Concrete',
        'description' => 'Ready-mix concrete',
    ]);

    $response->assertForbidden();
    expect(MaterialService::count())->toBe(0);
});

test('store rejects a duplicate name', function () {
    $user = materialServiceUser(['material-services.create']);
    MaterialService::factory()->create(['name' => 'Concrete']);

    $response = $this->actingAs($user)->post(route('material-services.store'), [
        'name' => 'Concrete',
        'description' => 'Ready-mix concrete',
    ]);

    $response->assertSessionHasErrors('name');
    expect(MaterialService::count())->toBe(1);
});

// show

test('show is allowed with view permission', function () {
    $user = materialServiceUser(['material-services.view']);
    $materialService = MaterialService::factory()->create();

    $response = $this->actingAs($user)->get(route('material-services.show', $materialService));

    $response->assertSuccessful();
    $response->assertViewIs('material_service.show');
});

test('show is forbidden without view permission', function () {
    $user = materialServiceUser();
    $materialService = MaterialService::factory()->create();

    $response = $this->actingAs($user)->get(route('material-services.show', $materialService));

    $response->assertForbidden();
});

// edit

test('edit is shown for a user with update permission', function () {
    $user = materialServiceUser(['material-services.update']);
    $materialService = MaterialService::factory()->create();

    $response = $this->actingAs($user)->get(route('material-services.edit', $materialService));

    $response->assertSuccessful();
    $response->assertViewIs('material_service.edit');
});

// update

test('update persists changes', function () {
    $user = materialServiceUser(['material-services.update']);
    $materialService = MaterialService::factory()->create();

    $response = $this->actingAs($user)->put(route('material-services.update', $materialService), [
        'name' => 'Updated name',
        'description' => $materialService->description,
    ]);

    $response->assertRedirect(route('material-services.show', $materialService));
    expect($materialService->fresh()->name)->toBe('Updated name');
});

test('update is forbidden without update permission', function () {
    $user = materialServiceUser();
    $materialService = MaterialService::factory()->create();

    $response = $this->actingAs($user)->put(route('material-services.update', $materialService), [
        'name' => 'Updated name',
        'description' => $materialService->description,
    ]);

    $response->assertForbidden();
    expect($materialService->fresh()->name)->not->toBe('Updated name');
});

// destroy

test('destroy removes a material service', function () {
    $user = materialServiceUser(['material-services.delete']);
    $materialService = MaterialService::factory()->create();

    $response = $this->actingAs($user)->delete(route('material-services.destroy', $materialService));

    $response->assertRedirect(route('material-services.index'));
    expect(MaterialService::find($materialService->id))->toBeNull();
});

test('destroy is forbidden without delete permission', function () {
    $user = materialServiceUser();
    $materialService = MaterialService::factory()->create();

    $response = $this->actingAs($user)->delete(route('material-services.destroy', $materialService));

    $response->assertForbidden();
    expect(MaterialService::find($materialService->id))->not->toBeNull();
});
