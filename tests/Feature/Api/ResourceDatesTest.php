<?php

namespace Tests\Feature\Api;

use App\Http\Resources\EmployeeResource;
use App\Http\Resources\ProjectResource;
use App\Models\Accounting;
use App\Models\Employee;
use App\Models\Logbook;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Sanctum\Sanctum;

// Calendar dates go out as plain "Y-m-d". They used to be serialized as the
// UTC timestamp of local midnight ("2026-10-18T22:00:00.000000Z" for
// 19 October), which a client could only map back to the right day by knowing
// the server's UTC offset on that date (see App\Casts\CalendarDate).

function apiDatesUser(array $permissions): User
{
    $user = User::factory()->create();

    foreach ($permissions as $permission) {
        grantPermission($user, $permission);
    }

    Sanctum::actingAs($user, ['authenticate']);

    return $user;
}

test('accounting entries return their service date as a plain calendar date', function () {
    $user = apiDatesUser(['accounting.view.own']);
    $accounting = Accounting::factory()->create(['employee_id' => $user->employee_id, 'service_provided_on' => '2026-10-19']);

    $this->getJson("/api/accounting/{$accounting->id}")
        ->assertSuccessful()
        ->assertJsonPath('data.service_provided_on', '2026-10-19');

    $this->getJson('/api/accounting?only_own=1')
        ->assertSuccessful()
        ->assertJsonPath('data.0.service_provided_on', '2026-10-19');
});

test('logbook entries return their driven date as a plain calendar date', function () {
    $user = apiDatesUser(['logbook.view.own']);
    $logbook = Logbook::factory()->create(['employee_id' => $user->employee_id, 'driven_on' => '2026-10-19']);

    $this->getJson("/api/logbook/{$logbook->id}")
        ->assertSuccessful()
        ->assertJsonPath('data.driven_on', '2026-10-19');

    $this->getJson('/api/logbook?only_own=1')
        ->assertSuccessful()
        ->assertJsonPath('data.0.driven_on', '2026-10-19');
});

test('employees return their entry and leaving dates as plain calendar dates', function () {
    $employee = User::factory()->create()->employee;
    $employee->update(['entered_on' => '2026-07-13', 'left_on' => null]);

    $data = (new EmployeeResource($employee->fresh()))->toArray(Request::create('/'));

    expect($data['entered_on'])->toBe('2026-07-13')
        ->and($data['left_on'])->toBeNull();
});

test('projects return their start and end dates as plain calendar dates', function () {
    $project = Project::factory()->create(['starts_on' => '2026-07-13', 'ends_on' => '2026-11-02']);

    $data = (new ProjectResource($project->fresh()))->toArray(Request::create('/'));

    expect($data['starts_on'])->toBe('2026-07-13')
        ->and($data['ends_on'])->toBe('2026-11-02');
});
