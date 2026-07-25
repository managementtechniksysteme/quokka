<?php

namespace Tests\Feature;

use App\Models\FinanceGroup;
use App\Models\FinanceRecord;
use App\Models\User;

function financeRecordUser(array $permissions = []): User
{
    $user = User::factory()->create();

    foreach ($permissions as $permission) {
        grantPermission($user, $permission);
    }

    return $user;
}

// create / store

test('create form is shown for a user with create permission', function () {
    $user = financeRecordUser(['finance-records.create']);
    $financeGroup = FinanceGroup::factory()->create();

    $response = $this->actingAs($user)->get(route('finance-records.create', $financeGroup));

    $response->assertSuccessful();
    $response->assertViewIs('finance_record.create');
});

test('store creates a finance record under the finance group', function () {
    $user = financeRecordUser(['finance-records.create']);
    $financeGroup = FinanceGroup::factory()->create();

    $response = $this->actingAs($user)->post(route('finance-records.store', $financeGroup), [
        'title' => 'Office supplies',
        'billed_on' => '2026-01-01',
        'amount' => -49.90,
    ]);

    $financeRecord = FinanceRecord::sole();

    $response->assertRedirect(route('finance-records.show', ['finance_group' => $financeGroup, 'finance_record' => $financeRecord]));
    expect($financeRecord->finance_group_id)->toBe($financeGroup->id);
    expect((float) $financeRecord->amount)->toBe(-49.9);
});

test('store is forbidden without create permission', function () {
    $user = financeRecordUser();
    $financeGroup = FinanceGroup::factory()->create();

    $response = $this->actingAs($user)->post(route('finance-records.store', $financeGroup), [
        'title' => 'Office supplies',
        'billed_on' => '2026-01-01',
        'amount' => -49.90,
    ]);

    $response->assertForbidden();
    expect(FinanceRecord::count())->toBe(0);
});

test('store rejects a duplicate title within the same finance group', function () {
    $user = financeRecordUser(['finance-records.create']);
    $financeGroup = FinanceGroup::factory()->create();
    FinanceRecord::factory()->create(['finance_group_id' => $financeGroup->id, 'title' => 'Office supplies']);

    $response = $this->actingAs($user)->post(route('finance-records.store', $financeGroup), [
        'title' => 'Office supplies',
        'billed_on' => '2026-01-01',
        'amount' => -10,
    ]);

    $response->assertSessionHasErrors('title');
    expect(FinanceRecord::count())->toBe(1);
});

test('store allows a duplicate title across different finance groups', function () {
    $user = financeRecordUser(['finance-records.create']);
    $otherGroup = FinanceGroup::factory()->create();
    FinanceRecord::factory()->create(['finance_group_id' => $otherGroup->id, 'title' => 'Office supplies']);
    $financeGroup = FinanceGroup::factory()->create();

    $response = $this->actingAs($user)->post(route('finance-records.store', $financeGroup), [
        'title' => 'Office supplies',
        'billed_on' => '2026-01-01',
        'amount' => -10,
    ]);

    $response->assertSessionDoesntHaveErrors('title');
    expect(FinanceRecord::count())->toBe(2);
});

// show

test('show is allowed with view permission', function () {
    $user = financeRecordUser(['finance-records.view']);
    $financeGroup = FinanceGroup::factory()->create();
    $financeRecord = FinanceRecord::factory()->create(['finance_group_id' => $financeGroup->id]);

    $response = $this->actingAs($user)->get(route('finance-records.show', ['finance_group' => $financeGroup, 'finance_record' => $financeRecord]));

    $response->assertSuccessful();
    $response->assertViewIs('finance_record.show');
});

test('show is forbidden without view permission', function () {
    $user = financeRecordUser();
    $financeGroup = FinanceGroup::factory()->create();
    $financeRecord = FinanceRecord::factory()->create(['finance_group_id' => $financeGroup->id]);

    $response = $this->actingAs($user)->get(route('finance-records.show', ['finance_group' => $financeGroup, 'finance_record' => $financeRecord]));

    $response->assertForbidden();
});

// update

test('update persists changes', function () {
    $user = financeRecordUser(['finance-records.update']);
    $financeGroup = FinanceGroup::factory()->create();
    $financeRecord = FinanceRecord::factory()->create(['finance_group_id' => $financeGroup->id]);

    $response = $this->actingAs($user)->put(route('finance-records.update', ['finance_group' => $financeGroup, 'finance_record' => $financeRecord]), [
        'title' => 'Updated title',
        'billed_on' => '2026-02-01',
        'amount' => 100,
    ]);

    $response->assertRedirect(route('finance-records.show', ['finance_group' => $financeGroup, 'finance_record' => $financeRecord]));
    expect($financeRecord->fresh()->title)->toBe('Updated title');
});

test('update is forbidden without update permission', function () {
    $user = financeRecordUser();
    $financeGroup = FinanceGroup::factory()->create();
    $financeRecord = FinanceRecord::factory()->create(['finance_group_id' => $financeGroup->id]);

    $response = $this->actingAs($user)->put(route('finance-records.update', ['finance_group' => $financeGroup, 'finance_record' => $financeRecord]), [
        'title' => 'Updated title',
        'billed_on' => '2026-02-01',
        'amount' => 100,
    ]);

    $response->assertForbidden();
});

// destroy

test('destroy removes a finance record', function () {
    $user = financeRecordUser(['finance-records.delete']);
    $financeGroup = FinanceGroup::factory()->create();
    $financeRecord = FinanceRecord::factory()->create(['finance_group_id' => $financeGroup->id]);

    $response = $this->actingAs($user)->delete(route('finance-records.destroy', ['finance_group' => $financeGroup, 'finance_record' => $financeRecord]));

    $response->assertRedirect(route('finance-groups.show', $financeGroup));
    expect(FinanceRecord::find($financeRecord->id))->toBeNull();
});

test('destroy is forbidden without delete permission', function () {
    $user = financeRecordUser();
    $financeGroup = FinanceGroup::factory()->create();
    $financeRecord = FinanceRecord::factory()->create(['finance_group_id' => $financeGroup->id]);

    $response = $this->actingAs($user)->delete(route('finance-records.destroy', ['finance_group' => $financeGroup, 'finance_record' => $financeRecord]));

    $response->assertForbidden();
    expect(FinanceRecord::find($financeRecord->id))->not->toBeNull();
});
