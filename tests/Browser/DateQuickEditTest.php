<?php

namespace Tests\Browser;

use App\Models\Memo;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Models\Vehicle;

function quickEditUser(array $permissions): User
{
    $user = User::factory()->create();

    foreach ($permissions as $permission) {
        grantPermission($user, $permission);
    }

    return $user;
}

test('the due date of a task is picked from the calendar and shown in the page format', function () {
    $user = quickEditUser(['tasks.view.responsible', 'tasks.update.responsible']);
    $task = Task::factory()->create(['employee_id' => $user->employee_id, 'status' => 'new', 'due_on' => '2030-01-10']);

    $this->actingAs($user);

    $page = visit(route('tasks.show', $task))->on()->desktop();

    $page->assertSeeIn('.q-statbar', '10.01.2030')
        ->click('.q-statbar button[aria-label="Fälligkeitsdatum ändern"]')
        ->assertVisible('.q-calendar')
        ->assertSeeIn('.q-calendar__title', 'Januar 2030')
        ->click('.q-calendar__day[data-date="2030-01-20"]')
        ->wait(0.8)
        ->assertSeeIn('.q-statbar', '20.01.2030')
        ->assertNotPresent('.q-calendar')
        ->assertNoJavaScriptErrors();

    expect($task->fresh()->due_on->format('Y-m-d'))->toBe('2030-01-20');
});

test('the calendar navigates to other months', function () {
    $user = quickEditUser(['tasks.view.responsible', 'tasks.update.responsible']);
    $task = Task::factory()->create(['employee_id' => $user->employee_id, 'status' => 'new', 'due_on' => '2030-01-10']);

    $this->actingAs($user);

    $page = visit(route('tasks.show', $task))->on()->desktop();

    $page->click('.q-statbar button[aria-label="Fälligkeitsdatum ändern"]')
        ->click('.q-calendar button[aria-label="Nächster Monat"]')
        ->assertSeeIn('.q-calendar__title', 'Februar 2030')
        ->click('.q-calendar__day[data-date="2030-02-14"]')
        ->wait(0.8)
        ->assertSeeIn('.q-statbar', '14.02.2030');

    expect($task->fresh()->due_on->format('Y-m-d'))->toBe('2030-02-14');
});

test('one click outside closes the calendar and so does escape', function () {
    $user = quickEditUser(['tasks.view.responsible', 'tasks.update.responsible']);
    $task = Task::factory()->create(['employee_id' => $user->employee_id, 'status' => 'new', 'due_on' => '2030-01-10']);

    $this->actingAs($user);

    $page = visit(route('tasks.show', $task))->on()->desktop();

    $page->click('.q-statbar button[aria-label="Fälligkeitsdatum ändern"]')
        ->assertVisible('.q-calendar')
        ->click('.q-title')
        ->assertNotPresent('.q-calendar')
        ->click('.q-statbar button[aria-label="Fälligkeitsdatum ändern"]')
        ->assertVisible('.q-calendar')
        ->keys('.q-calendar__day[tabindex="0"]', 'Escape')
        ->assertNotPresent('.q-calendar')
        ->assertSeeIn('.q-statbar', '10.01.2030');

    expect($task->fresh()->due_on->format('Y-m-d'))->toBe('2030-01-10');
});

test('an optional date can be removed from the calendar', function () {
    $user = quickEditUser(['memos.view.sender', 'memos.update.sender']);
    $memo = Memo::factory()->create(['employee_id' => $user->employee_id, 'draft' => false, 'meeting_held_on' => '2030-03-01', 'next_meeting_on' => '2030-03-15']);

    $this->actingAs($user);

    $page = visit(route('memos.show', $memo))->on()->desktop();

    $page->click('.q-statbar button[aria-label="Nächsten Termin ändern"]')
        ->click('.q-calendar__action--danger')
        ->wait(0.8)
        ->assertSeeIn('.q-statbar', 'kein Termin');

    expect($memo->fresh()->next_meeting_on)->toBeNull();
});

test('a date the server refuses is rolled back with the reason shown next to it', function () {
    $user = quickEditUser(['memos.view.sender', 'memos.update.sender']);
    $memo = Memo::factory()->create(['employee_id' => $user->employee_id, 'draft' => false, 'meeting_held_on' => '2030-03-01', 'next_meeting_on' => '2030-03-15']);

    $this->actingAs($user);

    $page = visit(route('memos.show', $memo))->on()->desktop();

    $page->click('.q-statbar button[aria-label="Datum ändern"]')
        ->click('.q-calendar__day[data-date="2030-03-20"]')
        ->wait(0.8)
        ->assertSeeIn('.q-statbar', 'Das Datum muss vor dem nächsten Termin liegen.')
        ->assertSeeIn('.q-statbar', '01.03.2030');

    expect($memo->fresh()->meeting_held_on->format('Y-m-d'))->toBe('2030-03-01');
});

test('on mobile the calendar opens as a bottom sheet', function () {
    $user = quickEditUser(['tasks.view.responsible', 'tasks.update.responsible']);
    $task = Task::factory()->create(['employee_id' => $user->employee_id, 'status' => 'new', 'due_on' => '2030-01-10']);

    $this->actingAs($user);

    $page = visit(route('tasks.show', $task))->on()->mobile();

    $page->click('.q-statbar button[aria-label="Fälligkeitsdatum ändern"]')
        ->assertVisible('.q-sheet.show .q-calendar')
        ->click('.q-sheet.show .q-calendar__day[data-date="2030-01-22"]')
        ->wait(1.0)
        ->assertSeeIn('.q-statbar .q-quick-edit-date__value', '22.01.2030')
        ->assertNotPresent('.q-sheet.show');

    expect($task->fresh()->due_on->format('Y-m-d'))->toBe('2030-01-22');
});

test('a vehicle is switched to private from its detail page', function () {
    $user = quickEditUser(['vehicles.view', 'vehicles.update']);
    $vehicle = Vehicle::factory()->create(['private' => false]);

    $this->actingAs($user);

    $page = visit(route('vehicles.show', $vehicle))->on()->desktop();

    $page->click('.q-meta:not(.d-md-none) button[aria-label="Sichtbarkeit ändern"]')
        ->click('.q-quick-edit__option:has-text("privat")')
        ->wait(0.8)
        ->assertSee('privat')
        ->assertNoJavaScriptErrors();

    expect($vehicle->fresh()->private)->toBeTrue();
});

test('the pre-execution phase of a project is switched on the overview tab', function () {
    $user = quickEditUser(['projects.view', 'projects.update']);
    $project = Project::factory()->create(['is_pre_execution' => false, 'ends_on' => null]);

    $this->actingAs($user);

    $page = visit(route('projects.show', ['project' => $project, 'tab' => 'overview']))->on()->desktop();

    $page->click('button[aria-label="Vorphase ändern"]')
        ->click('.q-quick-edit__option:has-text("ja")')
        ->wait(1.8)
        ->assertSee('Vorphase');

    expect($project->fresh()->is_pre_execution)->toBeTrue();
});
