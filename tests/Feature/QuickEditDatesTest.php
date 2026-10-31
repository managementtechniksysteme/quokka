<?php

namespace Tests\Feature;

use App\Events\AdditionsReportUpdatedEvent;
use App\Events\MemoUpdatedEvent;
use App\Models\AdditionsReport;
use App\Models\ConstructionReport;
use App\Models\FlowMeterInspectionReport;
use App\Models\InspectionReport;
use App\Models\Memo;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Event;

function dateEditor(array $permissions): User
{
    $user = User::factory()->create();

    foreach ($permissions as $permission) {
        grantPermission($user, $permission);
    }

    return $user;
}

// tasks

test('task quick update sets and clears the due date and reports the due hint', function () {
    $user = dateEditor(['tasks.update.responsible']);
    $task = Task::factory()->create(['employee_id' => $user->employee_id, 'status' => 'new', 'due_on' => null]);

    $response = $this->actingAs($user)->patchJson(route('tasks.quick-update', $task), ['due_on' => now()->subDays(3)->format('Y-m-d')]);
    $response
        ->assertOk()
        ->assertJson(['field' => 'due_on', 'value' => now()->subDays(3)->format('Y-m-d'), 'hint' => ['text' => 'überfällig', 'class' => 'q-statbar__urgent']]);

    $this->patchJson(route('tasks.quick-update', $task), ['due_on' => now()->addYear()->format('Y-m-d')])
        ->assertOk()
        ->assertJson(['hint' => null]);

    $this->patchJson(route('tasks.quick-update', $task), ['due_on' => null])
        ->assertOk()
        ->assertJson(['value' => null, 'hint' => null]);

    expect($task->fresh()->due_on)->toBeNull();
});

test('task quick update keeps the start and end dates consistent', function () {
    $user = dateEditor(['tasks.update.responsible']);
    $task = Task::factory()->create(['employee_id' => $user->employee_id, 'status' => 'finished', 'starts_on' => '2026-01-10', 'ends_on' => '2026-01-20']);

    $url = route('tasks.quick-update', $task);

    // the end may not precede the start; the start may not pass the end or be removed while an end exists
    $this->actingAs($user)->patchJson($url, ['ends_on' => '2026-01-05'])->assertUnprocessable()->assertJsonValidationErrors('ends_on');
    $this->patchJson($url, ['starts_on' => '2026-02-01'])->assertUnprocessable()->assertJsonValidationErrors('starts_on');
    $this->patchJson($url, ['starts_on' => null])->assertUnprocessable()->assertJsonValidationErrors('starts_on');

    $this->patchJson($url, ['starts_on' => '2026-01-12'])->assertOk()->assertJson(['value' => '2026-01-12']);
    $this->patchJson($url, ['ends_on' => '2026-01-25'])->assertOk()->assertJson(['value' => '2026-01-25']);

    expect($task->fresh())->starts_on->format('Y-m-d')->toBe('2026-01-12')->ends_on->format('Y-m-d')->toBe('2026-01-25');
});

test('task quick update only sets an end date on a finished task', function () {
    $user = dateEditor(['tasks.update.responsible']);
    $task = Task::factory()->create(['employee_id' => $user->employee_id, 'status' => 'in progress', 'starts_on' => '2026-01-10', 'ends_on' => null]);

    $this->actingAs($user)->patchJson(route('tasks.quick-update', $task), ['ends_on' => '2026-01-20'])
        ->assertUnprocessable()
        ->assertJsonValidationErrors('ends_on');

    expect($task->fresh()->ends_on)->toBeNull();
});

test('task quick update rejects an invalid date', function () {
    $user = dateEditor(['tasks.update.responsible']);
    $task = Task::factory()->create(['employee_id' => $user->employee_id]);

    $this->actingAs($user)->patchJson(route('tasks.quick-update', $task), ['due_on' => 'tomorrow-ish'])->assertUnprocessable();
});

// memos

test('memo quick update changes the dates and keeps the next meeting after the meeting', function () {
    Event::fake([MemoUpdatedEvent::class]);
    $user = dateEditor(['memos.view.sender', 'memos.update.sender']);
    $memo = Memo::factory()->create(['employee_id' => $user->employee_id, 'draft' => false, 'meeting_held_on' => '2026-03-01', 'next_meeting_on' => '2026-03-15']);
    $url = route('memos.quick-update', $memo);

    $this->actingAs($user)->patchJson($url, ['meeting_held_on' => '2026-03-15'])->assertUnprocessable()->assertJsonValidationErrors('meeting_held_on');
    $this->patchJson($url, ['next_meeting_on' => '2026-03-01'])->assertUnprocessable()->assertJsonValidationErrors('next_meeting_on');

    $this->patchJson($url, ['meeting_held_on' => '2026-03-10'])->assertOk()->assertJson(['value' => '2026-03-10', 'changed' => true]);
    $this->patchJson($url, ['next_meeting_on' => '2026-04-01'])->assertOk()->assertJson(['value' => '2026-04-01']);
    $this->patchJson($url, ['next_meeting_on' => null])->assertOk()->assertJson(['value' => null]);

    expect($memo->fresh())->meeting_held_on->format('Y-m-d')->toBe('2026-03-10')->next_meeting_on->toBeNull();
    Event::assertDispatched(MemoUpdatedEvent::class);
});

test('memo quick update without a date change does not notify', function () {
    Event::fake([MemoUpdatedEvent::class]);
    $user = dateEditor(['memos.view.sender', 'memos.update.sender']);
    $memo = Memo::factory()->create(['employee_id' => $user->employee_id, 'draft' => false, 'meeting_held_on' => '2026-03-01', 'next_meeting_on' => null]);

    $this->actingAs($user)->patchJson(route('memos.quick-update', $memo), ['meeting_held_on' => '2026-03-01'])
        ->assertOk()->assertJson(['changed' => false]);

    Event::assertNotDispatched(MemoUpdatedEvent::class);
});

// reports

dataset('reports with a date', [
    'additions report' => ['additions-reports', AdditionsReport::class, 'services_provided_on'],
    'construction report' => ['construction-reports', ConstructionReport::class, 'services_provided_on'],
    'inspection report' => ['inspection-reports', InspectionReport::class, 'inspected_on'],
    'flow meter inspection report' => ['flow-meter-inspection-reports', FlowMeterInspectionReport::class, 'inspected_on'],
]);

test('report quick update changes the date', function (string $slug, string $modelClass, string $column) {
    $user = dateEditor(["{$slug}.update.own"]);
    $report = $modelClass::factory()->create(['employee_id' => $user->employee_id, 'status' => 'new', $column => '2026-05-01']);

    $this->actingAs($user)->patchJson(route("{$slug}.quick-update", $report), ['date' => '2026-05-20'])
        ->assertOk()
        ->assertJson(['field' => 'date', 'value' => '2026-05-20', 'changed' => true]);

    expect($report->fresh()->{$column}->format('Y-m-d'))->toBe('2026-05-20');
})->with('reports with a date');

test('report quick update refuses a date another report of the project already has', function (string $slug, string $modelClass, string $column) {
    $user = dateEditor(["{$slug}.update.own"]);
    $project = Project::factory()->create();
    // inspections are unique per project, date and equipment
    $equipment = in_array($column, ['inspected_on'], true) ? ['equipment_identifier' => 'A-1'] : [];
    $report = $modelClass::factory()->create(array_merge(['employee_id' => $user->employee_id, 'status' => 'new', 'project_id' => $project->id, $column => '2026-05-01'], $equipment));
    $modelClass::factory()->create(array_merge(['status' => 'new', 'project_id' => $project->id, $column => '2026-05-20'], $equipment));

    $this->actingAs($user)->patchJson(route("{$slug}.quick-update", $report), ['date' => '2026-05-20'])
        ->assertUnprocessable()
        ->assertJsonValidationErrors('date');

    expect($report->fresh()->{$column}->format('Y-m-d'))->toBe('2026-05-01');
})->with('reports with a date');

test('report quick update allows the date of a report in another project', function (string $slug, string $modelClass, string $column) {
    $user = dateEditor(["{$slug}.update.own"]);
    $report = $modelClass::factory()->create(['employee_id' => $user->employee_id, 'status' => 'new', $column => '2026-05-01']);
    $modelClass::factory()->create(['status' => 'new', $column => '2026-05-20']);

    $this->actingAs($user)->patchJson(route("{$slug}.quick-update", $report), ['date' => '2026-05-20'])->assertOk();
})->with('reports with a date');

test('inspection reports of other equipment may share a date within a project', function (string $slug, string $modelClass) {
    $user = dateEditor(["{$slug}.update.own"]);
    $project = Project::factory()->create();
    $report = $modelClass::factory()->create(['employee_id' => $user->employee_id, 'status' => 'new', 'project_id' => $project->id, 'inspected_on' => '2026-05-01', 'equipment_identifier' => 'A-1']);
    $modelClass::factory()->create(['status' => 'new', 'project_id' => $project->id, 'inspected_on' => '2026-05-20', 'equipment_identifier' => 'B-2']);

    $this->actingAs($user)->patchJson(route("{$slug}.quick-update", $report), ['date' => '2026-05-20'])->assertOk();
})->with([
    'inspection report' => ['inspection-reports', InspectionReport::class],
    'flow meter inspection report' => ['flow-meter-inspection-reports', FlowMeterInspectionReport::class],
]);

test('report quick update of the date notifies and sends a signed report back to new', function () {
    Event::fake([AdditionsReportUpdatedEvent::class]);
    $user = dateEditor(['additions-reports.update.own']);
    $report = AdditionsReport::factory()->create(['employee_id' => $user->employee_id, 'status' => 'signed', 'services_provided_on' => '2026-05-01']);

    $this->actingAs($user)->patchJson(route('additions-reports.quick-update', $report), ['date' => '2026-05-02'])->assertOk();

    expect($report->fresh()->status)->toBe('new');
    Event::assertDispatched(AdditionsReportUpdatedEvent::class);
});

test('a task is only due soon within the configured number of days', function () {
    $user = dateEditor(['tasks.update.responsible']);
    $soonDays = \App\Models\ApplicationSettings::get()->task_due_soon_days;

    $dueInTwoDays = Task::factory()->create(['employee_id' => $user->employee_id, 'status' => 'new', 'due_on' => now()->addDays(2)]);
    $dueLater = Task::factory()->create(['employee_id' => $user->employee_id, 'status' => 'new', 'due_on' => now()->addDays($soonDays + 20)]);
    $overdue = Task::factory()->create(['employee_id' => $user->employee_id, 'status' => 'new', 'due_on' => now()->subDays(2)]);

    expect($dueInTwoDays->isDueSoon())->toBeTrue()
        ->and($dueLater->isDueSoon())->toBeFalse()
        ->and($overdue->isDueSoon())->toBeFalse()
        ->and($dueLater->due_hint)->toBeNull()
        ->and($dueInTwoDays->due_hint['text'])->toBe('bald fällig');
});
