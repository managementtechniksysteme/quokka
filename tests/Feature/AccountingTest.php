<?php

namespace Tests\Feature;

use App\Models\Accounting;
use App\Models\ApplicationSettings;
use App\Models\Employee;
use App\Models\MaterialService;
use App\Models\Project;
use App\Models\User;
use App\Models\WageService;

function accountingUser(array $permissions = []): User
{
    $user = User::factory()->create();

    foreach ($permissions as $permission) {
        grantPermission($user, $permission);
    }

    return $user;
}

function ownAccounting(User $user, array $attributes = []): Accounting
{
    return Accounting::factory()->create(array_merge(['employee_id' => $user->employee_id], $attributes));
}

// index

test('index is shown for a user with a view permission', function () {
    $user = accountingUser(['accounting.view.own']);

    $response = $this->actingAs($user)->get(route('accounting.index'));

    $response->assertSuccessful();
    $response->assertViewIs('accounting.index');
});

test('index is forbidden without any view permission', function () {
    $user = accountingUser();

    $response = $this->actingAs($user)->get(route('accounting.index'));

    $response->assertForbidden();
});

test('ajax index requires only_own for a view.own-only user', function () {
    $user = accountingUser(['accounting.view.own']);
    ownAccounting($user);

    $response = $this->actingAs($user)
        ->withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
        ->getJson(route('accounting.index'));

    $response->assertStatus(422);
});

test('ajax index succeeds for a view.own-only user with only_own set', function () {
    $user = accountingUser(['accounting.view.own']);
    $own = ownAccounting($user);
    Accounting::factory()->create();

    $response = $this->actingAs($user)
        ->withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
        ->getJson(route('accounting.index', ['only_own' => 1]));

    $response->assertSuccessful();
    expect(collect($response->json())->pluck('id'))->toEqual(collect([$own->id]));
});

// store

test('store creates a material-type accounting entry', function () {
    $user = accountingUser(['accounting.create']);
    $project = Project::factory()->create();
    $service = MaterialService::factory()->create();

    $response = $this->actingAs($user)->postJson(route('accounting.store'), [
        'service_provided_on' => '2026-01-01',
        'project_id' => $project->id,
        'service_id' => $service->id,
        'amount' => 10,
    ]);

    $response->assertCreated();
    $accounting = Accounting::sole();
    expect($accounting->employee_id)->toBe($user->employee_id);
});

test('store rejects an hour-based wage service missing time fields, instead of crashing', function () {
    $user = accountingUser(['accounting.create']);
    $project = Project::factory()->create();
    ApplicationSettings::get()->update(['services_hour_unit' => 'h']);
    ApplicationSettings::refreshCache();
    $service = WageService::factory()->create(['unit' => 'h']);

    $response = $this->actingAs($user)->postJson(route('accounting.store'), [
        'service_provided_on' => '2026-01-01',
        'project_id' => $project->id,
        'service_id' => $service->id,
        'amount' => 1,
    ]);

    $response->assertUnprocessable();
    $response->assertJsonValidationErrors(['service_provided_started_at', 'service_provided_ended_at']);
});

test('store is forbidden without create permission', function () {
    $user = accountingUser();
    $project = Project::factory()->create();
    $service = MaterialService::factory()->create();

    $response = $this->actingAs($user)->postJson(route('accounting.store'), [
        'service_provided_on' => '2026-01-01',
        'project_id' => $project->id,
        'service_id' => $service->id,
        'amount' => 10,
    ]);

    $response->assertForbidden();
    expect(Accounting::count())->toBe(0);
});

// update

test('update is allowed for an own entry with update.own permission', function () {
    $user = accountingUser(['accounting.create', 'accounting.update.own']);
    $accounting = ownAccounting($user, ['service_id' => MaterialService::factory()->create()->id]);

    $response = $this->actingAs($user)->putJson(route('accounting.update', $accounting), [
        'service_provided_on' => '2026-01-01',
        'project_id' => $accounting->project_id,
        'service_id' => $accounting->service_id,
        'amount' => 20,
    ]);

    $response->assertNoContent();
    expect((float) $accounting->fresh()->amount)->toBe(20.0);
});

test('update is forbidden for an own entry without update.own permission', function () {
    $user = accountingUser(['accounting.update.other']);
    $accounting = ownAccounting($user, ['service_id' => MaterialService::factory()->create()->id]);

    $response = $this->actingAs($user)->putJson(route('accounting.update', $accounting), [
        'service_provided_on' => '2026-01-01',
        'project_id' => $accounting->project_id,
        'service_id' => $accounting->service_id,
        'amount' => 20,
    ]);

    $response->assertForbidden();
});

// destroy

test('destroy removes an own entry with delete.own permission', function () {
    $user = accountingUser(['accounting.delete.own']);
    $accounting = ownAccounting($user);

    $response = $this->actingAs($user)->deleteJson(route('accounting.destroy', $accounting));

    $response->assertNoContent();
    expect(Accounting::find($accounting->id))->toBeNull();
});

test('destroy is forbidden for an own entry without delete.own permission', function () {
    $user = accountingUser(['accounting.delete.other']);
    $accounting = ownAccounting($user);

    $response = $this->actingAs($user)->deleteJson(route('accounting.destroy', $accounting));

    $response->assertForbidden();
    expect(Accounting::find($accounting->id))->not->toBeNull();
});

// download authorization regression: 'download' was missing from resourceAbilityMap(),
// so any authenticated user could hit the route regardless of accounting.createpdf.

test('download is forbidden without createpdf permission', function () {
    $user = accountingUser(['accounting.view.own']);

    $response = $this->actingAs($user)->get(route('accounting.download'));

    $response->assertForbidden();
});

// employee_ids exclusion regression: for a view.other-only user, the not_in restriction
// was applied to the whole employee_ids array instead of employee_ids.* and was a no-op -
// they could include (and download) their own accounting entries anyway.

test('download rejects a view.other-only user including their own employee_id', function () {
    $user = accountingUser(['accounting.view.other', 'accounting.createpdf']);

    $response = $this->actingAs($user)->get(route('accounting.download', [
        'employee_ids' => [$user->employee_id],
    ]));

    $response->assertForbidden();
});

test('download allows a view.own-only user restricted to their own employee_id', function () {
    $user = accountingUser(['accounting.view.own', 'accounting.createpdf']);

    $response = $this->actingAs($user)->get(route('accounting.download', [
        'employee_ids' => [$user->employee_id],
    ]));

    $response->assertSuccessful();
})->group('pdflatex');

// holiday balance observer regression

test('creating a holiday-service entry decrements the employee holiday balance', function () {
    $holidayService = WageService::factory()->create();
    ApplicationSettings::get()->update(['holiday_service_id' => $holidayService->id]);
    ApplicationSettings::refreshCache();

    $employee = Employee::factory()->create(['holidays' => 25]);

    Accounting::factory()->create([
        'employee_id' => $employee->person_id,
        'service_id' => $holidayService->id,
        'amount' => 2,
    ]);

    expect($employee->fresh()->holidays)->toEqual(23);
});

test('deleting a holiday-service entry restores the employee holiday balance', function () {
    $holidayService = WageService::factory()->create();
    ApplicationSettings::get()->update(['holiday_service_id' => $holidayService->id]);
    ApplicationSettings::refreshCache();

    $employee = Employee::factory()->create(['holidays' => 25]);

    $accounting = Accounting::factory()->create([
        'employee_id' => $employee->person_id,
        'service_id' => $holidayService->id,
        'amount' => 2,
    ]);

    expect($employee->fresh()->holidays)->toEqual(23);

    $accounting->delete();

    expect($employee->fresh()->holidays)->toEqual(25);
});

test('updating a holiday-service entry adjusts the employee holiday balance by the delta', function () {
    $holidayService = WageService::factory()->create();
    ApplicationSettings::get()->update(['holiday_service_id' => $holidayService->id]);
    ApplicationSettings::refreshCache();

    $employee = Employee::factory()->create(['holidays' => 25]);

    $accounting = Accounting::factory()->create([
        'employee_id' => $employee->person_id,
        'service_id' => $holidayService->id,
        'amount' => 2,
    ]);

    expect($employee->fresh()->holidays)->toEqual(23);

    $accounting->update(['amount' => 5]);

    expect($employee->fresh()->holidays)->toEqual(20);
});
