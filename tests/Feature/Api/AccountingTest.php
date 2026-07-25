<?php

namespace Tests\Feature\Api;

use App\Models\Accounting;
use App\Models\Employee;
use App\Models\MaterialService;
use App\Models\Project;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

function apiAccountingUser(array $permissions = []): User
{
    $user = User::factory()->create();

    foreach ($permissions as $permission) {
        grantPermission($user, $permission);
    }

    Sanctum::actingAs($user, ['authenticate']);

    return $user;
}

function ownAccounting(User $user, array $attributes = []): Accounting
{
    return Accounting::factory()->create(array_merge(['employee_id' => $user->employee_id], $attributes));
}

test('index requires a valid sanctum ability', function () {
    $user = User::factory()->create();
    grantPermission($user, 'accounting.view.own');
    grantPermission($user, 'accounting.view.other');
    Sanctum::actingAs($user, ['refresh']);

    $response = $this->getJson('/api/accounting');

    $response->assertForbidden();
});

test('index is forbidden without any view permission', function () {
    apiAccountingUser();

    $response = $this->getJson('/api/accounting');

    $response->assertForbidden();
});

test('index requires only_own when the user can only view their own records', function () {
    apiAccountingUser(['accounting.view.own']);

    $response = $this->getJson('/api/accounting');

    $response->assertUnprocessable();
    $response->assertJsonValidationErrors('only_own');
});

test('index lists only own records with only_own', function () {
    $user = apiAccountingUser(['accounting.view.own']);
    $mine = ownAccounting($user);
    Accounting::factory()->create();

    $response = $this->getJson('/api/accounting?only_own=1');

    $response->assertSuccessful();
    $response->assertJsonCount(1, 'data');
    $response->assertJsonPath('data.0.id', $mine->id);
});

test('index filters by employee_id', function () {
    apiAccountingUser(['accounting.view.own', 'accounting.view.other']);
    $employee = Employee::factory()->create();
    $matching = Accounting::factory()->create(['employee_id' => $employee->person_id]);
    Accounting::factory()->create();

    $response = $this->getJson('/api/accounting?employee_id='.$employee->person_id);

    $response->assertSuccessful();
    $response->assertJsonCount(1, 'data');
    $response->assertJsonPath('data.0.id', $matching->id);
});

test('store creates an accounting record owned by the acting user', function () {
    $user = apiAccountingUser(['accounting.create']);
    $project = Project::factory()->create();
    $service = MaterialService::factory()->create();

    $response = $this->postJson('/api/accounting', [
        'service_provided_on' => now()->toDateString(),
        'project_id' => $project->id,
        'service_id' => $service->id,
        'amount' => 12.50,
    ]);

    $response->assertCreated();
    $this->assertDatabaseHas('accounting', [
        'employee_id' => $user->employee_id,
        'project_id' => $project->id,
    ]);
});

test('store is forbidden without create permission', function () {
    apiAccountingUser();
    $project = Project::factory()->create();
    $service = MaterialService::factory()->create();

    $response = $this->postJson('/api/accounting', [
        'service_provided_on' => now()->toDateString(),
        'project_id' => $project->id,
        'service_id' => $service->id,
        'amount' => 12.50,
    ]);

    $response->assertForbidden();
});

test('show is allowed for your own record with view.own permission', function () {
    $user = apiAccountingUser(['accounting.view.own']);
    $accounting = ownAccounting($user);

    $response = $this->getJson("/api/accounting/{$accounting->id}");

    $response->assertSuccessful();
    $response->assertJsonPath('data.id', $accounting->id);
});

test('show is forbidden for someone else\'s record without view.other permission', function () {
    $user = apiAccountingUser(['accounting.view.own']);
    $accounting = Accounting::factory()->create();

    $response = $this->getJson("/api/accounting/{$accounting->id}");

    $response->assertForbidden();
});

test('update persists changes and returns the updated resource', function () {
    $user = apiAccountingUser(['accounting.update.own']);
    $accounting = ownAccounting($user, ['amount' => 1]);

    $response = $this->putJson("/api/accounting/{$accounting->id}", [
        'service_provided_on' => now()->toDateString(),
        'project_id' => $accounting->project_id,
        'service_id' => $accounting->service_id,
        'amount' => 99,
    ]);

    $response->assertSuccessful();
    $response->assertJsonPath('data.amount', 99);
    expect($accounting->fresh()->amount)->toBe(99.0);
});

test('update is forbidden for someone else\'s record without update.other permission', function () {
    apiAccountingUser(['accounting.update.own']);
    $accounting = Accounting::factory()->create();

    $response = $this->putJson("/api/accounting/{$accounting->id}", [
        'service_provided_on' => now()->toDateString(),
        'project_id' => $accounting->project_id,
        'service_id' => $accounting->service_id,
        'amount' => 99,
    ]);

    $response->assertForbidden();
});

test('destroy removes your own record', function () {
    $user = apiAccountingUser(['accounting.delete.own']);
    $accounting = ownAccounting($user);

    $response = $this->deleteJson("/api/accounting/{$accounting->id}");

    $response->assertSuccessful();
    $this->assertModelMissing($accounting);
});

test('destroy is forbidden for someone else\'s record without delete.other permission', function () {
    apiAccountingUser(['accounting.delete.own']);
    $accounting = Accounting::factory()->create();

    $response = $this->deleteJson("/api/accounting/{$accounting->id}");

    $response->assertForbidden();
    $this->assertModelExists($accounting);
});
