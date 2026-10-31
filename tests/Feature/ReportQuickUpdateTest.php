<?php

namespace Tests\Feature;

use App\Events\AdditionsReportUpdatedEvent;
use App\Events\ConstructionReportUpdatedEvent;
use App\Events\FlowMeterInspectionReportUpdatedEvent;
use App\Events\InspectionReportUpdatedEvent;
use App\Models\AdditionsReport;
use App\Models\ConstructionReport;
use App\Models\Employee;
use App\Models\FlowMeterInspectionReport;
use App\Models\InspectionReport;
use App\Models\Person;
use App\Models\User;
use Illuminate\Support\Facades\Event;

dataset('quick editable reports', [
    'additions report' => ['additions-reports', AdditionsReport::class, AdditionsReportUpdatedEvent::class],
    'construction report' => ['construction-reports', ConstructionReport::class, ConstructionReportUpdatedEvent::class],
    'inspection report' => ['inspection-reports', InspectionReport::class, InspectionReportUpdatedEvent::class],
    'flow meter inspection report' => ['flow-meter-inspection-reports', FlowMeterInspectionReport::class, FlowMeterInspectionReportUpdatedEvent::class],
]);

dataset('reports with participants', [
    'additions report' => ['additions-reports', AdditionsReport::class],
    'construction report' => ['construction-reports', ConstructionReport::class],
]);

function reportEditor(string $slug, bool $mayUpdate = true): User
{
    $user = User::factory()->create();

    if ($mayUpdate) {
        grantPermission($user, "{$slug}.update.own");
    }

    return $user;
}

function ownReport(string $modelClass, User $user, array $attributes = [])
{
    return $modelClass::factory()->create(array_merge(['employee_id' => $user->employee_id, 'status' => 'new'], $attributes));
}

test('quick update changes the weather and notifies', function (string $slug, string $modelClass, string $eventClass) {
    Event::fake([$eventClass]);
    $user = reportEditor($slug);
    $report = ownReport($modelClass, $user, ['weather' => 'sunny']);

    $this->actingAs($user)->patchJson(route("{$slug}.quick-update", $report), ['weather' => 'snowy'])
        ->assertOk()
        ->assertJson(['field' => 'weather', 'value' => 'snowy', 'changed' => true]);

    expect($report->fresh()->weather)->toBe('snowy');
    Event::assertDispatched($eventClass);
})->with('quick editable reports');

test('quick update without a change does not notify', function (string $slug, string $modelClass, string $eventClass) {
    Event::fake([$eventClass]);
    $user = reportEditor($slug);
    $report = ownReport($modelClass, $user, ['weather' => 'sunny']);

    $this->actingAs($user)->patchJson(route("{$slug}.quick-update", $report), ['weather' => 'sunny'])
        ->assertOk()
        ->assertJson(['changed' => false]);

    Event::assertNotDispatched($eventClass);
})->with('quick editable reports');

test('quick update is forbidden without the update permission', function (string $slug, string $modelClass) {
    $user = reportEditor($slug, mayUpdate: false);
    $report = ownReport($modelClass, $user, ['weather' => 'sunny']);

    $this->actingAs($user)->patchJson(route("{$slug}.quick-update", $report), ['weather' => 'snowy'])->assertForbidden();

    expect($report->fresh()->weather)->toBe('sunny');
})->with('quick editable reports');

test('quick update is forbidden for a finished report', function (string $slug, string $modelClass) {
    $user = reportEditor($slug);
    $report = ownReport($modelClass, $user, ['weather' => 'sunny', 'status' => 'finished']);

    $this->actingAs($user)->patchJson(route("{$slug}.quick-update", $report), ['weather' => 'snowy'])->assertForbidden();

    expect($report->fresh()->weather)->toBe('sunny');
})->with('quick editable reports');

test('quick update rejects an invalid weather and several fields at once', function (string $slug, string $modelClass) {
    $user = reportEditor($slug);
    $report = ownReport($modelClass, $user, ['weather' => 'sunny']);

    $this->actingAs($user)->patchJson(route("{$slug}.quick-update", $report), ['weather' => 'foggy'])->assertUnprocessable();
    $this->patchJson(route("{$slug}.quick-update", $report), ['weather' => 'rainy', 'involved_ids' => [$user->employee_id]])->assertUnprocessable();
    $this->patchJson(route("{$slug}.quick-update", $report), [])->assertUnprocessable();

    expect($report->fresh()->weather)->toBe('sunny');
})->with('quick editable reports');

test('quick update of a signed report sends it back to new and drops its pending signature request', function (string $slug, string $modelClass) {
    $user = reportEditor($slug);
    $report = ownReport($modelClass, $user, ['weather' => 'sunny', 'status' => 'signed']);
    $report->generateSignatureRequest();

    $this->actingAs($user)->patchJson(route("{$slug}.quick-update", $report), ['weather' => 'rainy'])->assertOk();

    $report = $report->fresh();

    expect($report->status)->toBe('new');
    expect($report->signatureRequest)->toBeNull();
})->with('quick editable reports');

test('quick update without a change leaves a signed report signed', function (string $slug, string $modelClass) {
    $user = reportEditor($slug);
    $report = ownReport($modelClass, $user, ['weather' => 'sunny', 'status' => 'signed']);

    $this->actingAs($user)->patchJson(route("{$slug}.quick-update", $report), ['weather' => 'sunny'])->assertOk();

    expect($report->fresh()->status)->toBe('signed');
})->with('quick editable reports');

test('quick update replaces the involved employees', function (string $slug, string $modelClass) {
    $user = reportEditor($slug);
    $report = $modelClass::factory()->withInvolvedEmployees(2)->create(['employee_id' => $user->employee_id, 'status' => 'new']);
    $newEmployee = Employee::factory()->create();

    $this->actingAs($user)->patchJson(route("{$slug}.quick-update", $report), ['involved_ids' => [$newEmployee->person_id]])
        ->assertOk()
        ->assertJson(['field' => 'involved_ids', 'value' => [$newEmployee->person_id], 'changed' => true]);

    expect($report->fresh()->involvedEmployees->pluck('person_id')->all())->toBe([$newEmployee->person_id]);
})->with('reports with participants');

test('quick update does not allow emptying the involved employees', function (string $slug, string $modelClass) {
    $user = reportEditor($slug);
    $report = $modelClass::factory()->withInvolvedEmployees(1)->create(['employee_id' => $user->employee_id, 'status' => 'new']);

    $this->actingAs($user)->patchJson(route("{$slug}.quick-update", $report), ['involved_ids' => []])->assertUnprocessable();

    expect($report->fresh()->involvedEmployees)->toHaveCount(1);
})->with('reports with participants');

test('quick update replaces and empties the present people', function (string $slug, string $modelClass) {
    $user = reportEditor($slug);
    $report = $modelClass::factory()->withPresentPeople(2)->create(['employee_id' => $user->employee_id, 'status' => 'new']);
    $newPerson = Person::factory()->create();

    $this->actingAs($user)->patchJson(route("{$slug}.quick-update", $report), ['present_ids' => [$newPerson->id]])
        ->assertOk()
        ->assertJson(['field' => 'present_ids', 'value' => [$newPerson->id]]);

    expect($report->fresh()->presentPeople->pluck('id')->all())->toBe([$newPerson->id]);

    $this->patchJson(route("{$slug}.quick-update", $report), ['present_ids' => []])->assertOk();

    expect($report->fresh()->presentPeople)->toHaveCount(0);
})->with('reports with participants');

test('reports without participants reject participant quick edits', function (string $slug, string $modelClass) {
    $user = reportEditor($slug);
    $report = ownReport($modelClass, $user);

    $this->actingAs($user)->patchJson(route("{$slug}.quick-update", $report), ['involved_ids' => [$user->employee_id]])->assertUnprocessable();
    $this->patchJson(route("{$slug}.quick-update", $report), ['present_ids' => []])->assertUnprocessable();
})->with([
    'inspection report' => ['inspection-reports', InspectionReport::class],
    'flow meter inspection report' => ['flow-meter-inspection-reports', FlowMeterInspectionReport::class],
]);

test('show offers in-place editing only to users who may update the report', function (string $slug, string $modelClass) {
    $editor = reportEditor($slug);
    grantPermission($editor, "{$slug}.view.own");
    $viewer = User::factory()->create();
    grantPermission($viewer, "{$slug}.view.own");

    $this->actingAs($editor)->get(route("{$slug}.show", ownReport($modelClass, $editor)))
        ->assertSuccessful()
        ->assertSee('<quick-edit-chip', false);

    $this->actingAs($viewer)->get(route("{$slug}.show", ownReport($modelClass, $viewer)))
        ->assertSuccessful()
        ->assertDontSee('<quick-edit-chip', false);
})->with([
    'additions report' => ['additions-reports', AdditionsReport::class],
    'construction report' => ['construction-reports', ConstructionReport::class],
    'inspection report' => ['inspection-reports', InspectionReport::class],
    'flow meter inspection report' => ['flow-meter-inspection-reports', FlowMeterInspectionReport::class],
]);

test('show offers participant editing on reports that have participants', function (string $slug, string $modelClass) {
    $editor = reportEditor($slug);
    grantPermission($editor, "{$slug}.view.own");

    $this->actingAs($editor)->get(route("{$slug}.show", ownReport($modelClass, $editor)))
        ->assertSee('<quick-edit-people', false);
})->with('reports with participants');

test('quick update with the same participants does not notify or unsign', function (string $slug, string $modelClass) {
    $user = reportEditor($slug);
    $report = $modelClass::factory()->withInvolvedEmployees(1)->withPresentPeople(1)->create(['employee_id' => $user->employee_id, 'status' => 'signed']);

    $this->actingAs($user)->patchJson(route("{$slug}.quick-update", $report), ['involved_ids' => $report->involvedEmployees->pluck('person_id')->all()])
        ->assertOk()->assertJson(['changed' => false]);
    $this->patchJson(route("{$slug}.quick-update", $report), ['present_ids' => $report->presentPeople->pluck('id')->all()])
        ->assertOk()->assertJson(['changed' => false]);

    expect($report->fresh()->status)->toBe('signed');
})->with('reports with participants');
