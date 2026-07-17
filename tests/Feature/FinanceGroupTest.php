<?php

namespace Tests\Feature;

use App\Models\FinanceGroup;
use App\Models\FinanceRecord;
use App\Models\User;

function financeGroupUser(array $permissions = []): User
{
    $user = User::factory()->create();

    foreach ($permissions as $permission) {
        grantPermission($user, $permission);
    }

    return $user;
}

// index

test('index is shown for a user with view permission', function () {
    $user = financeGroupUser(['finance-groups.view']);

    $response = $this->actingAs($user)->get(route('finance-groups.index'));

    $response->assertSuccessful();
    $response->assertViewIs('finance_group.index');
});

test('index is forbidden without view permission', function () {
    $user = financeGroupUser();

    $response = $this->actingAs($user)->get(route('finance-groups.index'));

    $response->assertForbidden();
});

// create / store

test('create form is shown for a user with create permission', function () {
    $user = financeGroupUser(['finance-groups.create']);

    $response = $this->actingAs($user)->get(route('finance-groups.create'));

    $response->assertSuccessful();
    $response->assertViewIs('finance_group.create');
});

test('store creates a finance group', function () {
    $user = financeGroupUser(['finance-groups.create']);

    $response = $this->actingAs($user)->post(route('finance-groups.store'), [
        'title' => 'Marketing',
    ]);

    $financeGroup = FinanceGroup::sole();

    $response->assertRedirect(route('finance-groups.show', $financeGroup));
    expect($financeGroup->title)->toBe('Marketing');
});

test('store is forbidden without create permission', function () {
    $user = financeGroupUser();

    $response = $this->actingAs($user)->post(route('finance-groups.store'), [
        'title' => 'Marketing',
    ]);

    $response->assertForbidden();
    expect(FinanceGroup::count())->toBe(0);
});

test('store rejects a duplicate title', function () {
    $user = financeGroupUser(['finance-groups.create']);
    FinanceGroup::factory()->create(['title' => 'Marketing']);

    $response = $this->actingAs($user)->post(route('finance-groups.store'), [
        'title' => 'Marketing',
    ]);

    $response->assertSessionHasErrors('title');
    expect(FinanceGroup::count())->toBe(1);
});

// show

test('show is allowed with view permission', function () {
    $user = financeGroupUser(['finance-groups.view']);
    $financeGroup = FinanceGroup::factory()->create();

    $response = $this->actingAs($user)->get(route('finance-groups.show', $financeGroup));

    $response->assertSuccessful();
    $response->assertViewIs('finance_group.show');
});

test('show is forbidden without view permission', function () {
    $user = financeGroupUser();
    $financeGroup = FinanceGroup::factory()->create();

    $response = $this->actingAs($user)->get(route('finance-groups.show', $financeGroup));

    $response->assertForbidden();
});

// edit

test('edit is shown for a user with update permission', function () {
    $user = financeGroupUser(['finance-groups.update']);
    $financeGroup = FinanceGroup::factory()->create();

    $response = $this->actingAs($user)->get(route('finance-groups.edit', $financeGroup));

    $response->assertSuccessful();
    $response->assertViewIs('finance_group.edit');
});

// update

test('update persists changes', function () {
    $user = financeGroupUser(['finance-groups.update']);
    $financeGroup = FinanceGroup::factory()->create();

    $response = $this->actingAs($user)->put(route('finance-groups.update', $financeGroup), [
        'title' => 'Updated title',
    ]);

    $response->assertRedirect(route('finance-groups.show', $financeGroup));
    expect($financeGroup->fresh()->title)->toBe('Updated title');
});

test('update is forbidden without update permission', function () {
    $user = financeGroupUser();
    $financeGroup = FinanceGroup::factory()->create();

    $response = $this->actingAs($user)->put(route('finance-groups.update', $financeGroup), [
        'title' => 'Updated title',
    ]);

    $response->assertForbidden();
});

// destroy

test('destroy removes a finance group and its finance records', function () {
    $user = financeGroupUser(['finance-groups.delete']);
    $financeGroup = FinanceGroup::factory()->create();
    $financeRecord = FinanceRecord::factory()->create(['finance_group_id' => $financeGroup->id]);

    $response = $this->actingAs($user)->delete(route('finance-groups.destroy', $financeGroup));

    $response->assertRedirect(route('finance-groups.index'));
    expect(FinanceGroup::find($financeGroup->id))->toBeNull();
    expect(FinanceRecord::find($financeRecord->id))->toBeNull();
});

test('destroy is forbidden without delete permission', function () {
    $user = financeGroupUser();
    $financeGroup = FinanceGroup::factory()->create();

    $response = $this->actingAs($user)->delete(route('finance-groups.destroy', $financeGroup));

    $response->assertForbidden();
    expect(FinanceGroup::find($financeGroup->id))->not->toBeNull();
});
