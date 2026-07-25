<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\WageService;

function wageServiceUser(array $permissions = []): User
{
    $user = User::factory()->create();

    foreach ($permissions as $permission) {
        grantPermission($user, $permission);
    }

    return $user;
}

// index

test('index is shown for a user with view permission', function () {
    $user = wageServiceUser(['wage-services.view']);

    $response = $this->actingAs($user)->get(route('wage-services.index'));

    $response->assertSuccessful();
    $response->assertViewIs('service.index_tab_wage_services');
});

test('index is forbidden without view permission', function () {
    $user = wageServiceUser();

    $response = $this->actingAs($user)->get(route('wage-services.index'));

    $response->assertForbidden();
});

// create / store

test('create form is shown for a user with create permission', function () {
    $user = wageServiceUser(['wage-services.create']);

    $response = $this->actingAs($user)->get(route('wage-services.create'));

    $response->assertSuccessful();
    $response->assertViewIs('wage_service.create');
});

test('store creates a wage service', function () {
    $user = wageServiceUser(['wage-services.create']);

    $response = $this->actingAs($user)->post(route('wage-services.store'), [
        'name' => 'Excavation',
        'description' => 'Digging work',
        'unit' => 'h',
        'costs' => 45.5,
    ]);

    $wageService = WageService::sole();

    $response->assertRedirect(route('wage-services.show', $wageService));
    expect($wageService->name)->toBe('Excavation');
    expect((float) $wageService->costs)->toBe(45.5);
});

test('store is forbidden without create permission', function () {
    $user = wageServiceUser();

    $response = $this->actingAs($user)->post(route('wage-services.store'), [
        'name' => 'Excavation',
        'description' => 'Digging work',
        'unit' => 'h',
    ]);

    $response->assertForbidden();
    expect(WageService::count())->toBe(0);
});

test('store rejects a duplicate name', function () {
    $user = wageServiceUser(['wage-services.create']);
    WageService::factory()->create(['name' => 'Excavation']);

    $response = $this->actingAs($user)->post(route('wage-services.store'), [
        'name' => 'Excavation',
        'description' => 'Digging work',
        'unit' => 'h',
    ]);

    $response->assertSessionHasErrors('name');
    expect(WageService::count())->toBe(1);
});

// show

test('show is allowed with view permission', function () {
    $user = wageServiceUser(['wage-services.view']);
    $wageService = WageService::factory()->create();

    $response = $this->actingAs($user)->get(route('wage-services.show', $wageService));

    $response->assertSuccessful();
    $response->assertViewIs('wage_service.show');
});

test('show is forbidden without view permission', function () {
    $user = wageServiceUser();
    $wageService = WageService::factory()->create();

    $response = $this->actingAs($user)->get(route('wage-services.show', $wageService));

    $response->assertForbidden();
});

// edit

test('edit is shown for a user with update permission', function () {
    $user = wageServiceUser(['wage-services.update']);
    $wageService = WageService::factory()->create();

    $response = $this->actingAs($user)->get(route('wage-services.edit', $wageService));

    $response->assertSuccessful();
    $response->assertViewIs('wage_service.edit');
});

// update

test('update persists changes', function () {
    $user = wageServiceUser(['wage-services.update']);
    $wageService = WageService::factory()->create();

    $response = $this->actingAs($user)->put(route('wage-services.update', $wageService), [
        'name' => 'Updated name',
        'description' => $wageService->description,
        'unit' => $wageService->unit,
    ]);

    $response->assertRedirect(route('wage-services.show', $wageService));
    expect($wageService->fresh()->name)->toBe('Updated name');
});

test('update is forbidden without update permission', function () {
    $user = wageServiceUser();
    $wageService = WageService::factory()->create();

    $response = $this->actingAs($user)->put(route('wage-services.update', $wageService), [
        'name' => 'Updated name',
        'description' => $wageService->description,
        'unit' => $wageService->unit,
    ]);

    $response->assertForbidden();
    expect($wageService->fresh()->name)->not->toBe('Updated name');
});

// destroy

test('destroy removes a wage service', function () {
    $user = wageServiceUser(['wage-services.delete']);
    $wageService = WageService::factory()->create();

    $response = $this->actingAs($user)->delete(route('wage-services.destroy', $wageService));

    $response->assertRedirect(route('wage-services.index'));
    expect(WageService::find($wageService->id))->toBeNull();
});

test('destroy is forbidden without delete permission', function () {
    $user = wageServiceUser();
    $wageService = WageService::factory()->create();

    $response = $this->actingAs($user)->delete(route('wage-services.destroy', $wageService));

    $response->assertForbidden();
    expect(WageService::find($wageService->id))->not->toBeNull();
});
