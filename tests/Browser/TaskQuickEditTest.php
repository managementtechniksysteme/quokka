<?php

namespace Tests\Browser;

use App\Models\Employee;
use App\Models\Task;
use App\Models\User;

function quickEditTaskUser(): User
{
    $user = User::factory()->create();

    foreach (['tasks.view.responsible', 'tasks.update.responsible', 'tasks.create', 'tasks.create.private'] as $permission) {
        grantPermission($user, $permission);
    }

    return $user;
}

test('the priority chip expands its choices on desktop and saves the pick in place', function () {
    $user = quickEditTaskUser();
    $task = Task::factory()->create(['employee_id' => $user->employee_id, 'priority' => 'medium']);

    $this->actingAs($user);

    $page = visit(route('tasks.show', $task))->on()->desktop();

    $page->assertSeeIn('.q-page-head', 'Priorität mittel')
        ->click('.q-page-head button[aria-label="Priorität ändern"]')
        ->assertVisible('.q-page-head .q-quick-edit__options')
        ->click('.q-page-head .q-quick-edit__option:has-text("hoch")')
        ->wait(0.8)
        ->assertSeeIn('.q-page-head', 'Priorität hoch')
        ->assertNotPresent('.q-page-head .q-quick-edit__options')
        ->assertNoJavaScriptErrors();

    expect($task->fresh()->priority)->toBe('high');
});

test('finishing a task through the status chip stamps the dates and reloads the page', function () {
    $user = quickEditTaskUser();
    $task = Task::factory()->create(['employee_id' => $user->employee_id, 'status' => 'in progress', 'ends_on' => null]);

    $this->actingAs($user);

    $page = visit(route('tasks.show', $task))->on()->desktop();

    $page->click('.q-page-head button[aria-label="Status ändern"]')
        ->click('.q-page-head .q-quick-edit__option:has-text("erledigt")')
        ->wait(1.5)
        ->assertSeeIn('.q-page-head .q-status', 'erledigt')
        ->assertNotPresent('.q-page-head a:has-text("Erledigen")');

    expect($task->fresh())->status->toBe('finished')->ends_on->not->toBeNull();
});

test('on mobile the chip opens a bottom sheet with the choices', function () {
    $user = quickEditTaskUser();
    $task = Task::factory()->create(['employee_id' => $user->employee_id, 'billed' => 'no']);

    $this->actingAs($user);

    $page = visit(route('tasks.show', $task))->on()->mobile();

    $page->click('.q-statbar button[aria-label="Verrechnung ändern"]')
        ->assertVisible('.q-sheet.show')
        ->click('.q-sheet.show .q-row:has-text("Garantie")')
        ->wait(1.0)
        ->assertSeeIn('.q-statbar .q-quick-edit__chip', 'Garantie')
        ->assertNotPresent('.q-sheet.show');

    expect($task->fresh()->billed)->toBe('warranty');
});

test('the involved employees can be changed from the aside and are saved with one click', function () {
    $user = quickEditTaskUser();
    $task = Task::factory()->create(['employee_id' => $user->employee_id]);
    $colleague = Employee::factory()->create();
    $colleagueName = $colleague->person->name;

    $this->actingAs($user);

    $page = visit(route('tasks.show', $task))->on()->desktop();

    $page->assertSeeIn('.q-aside', 'keine Mitwirkenden')
        ->click('.q-aside button[aria-label="Mitwirkende ändern"]')
        ->click('.q-aside .vs__dropdown-toggle')
        ->click(".q-aside .vs__dropdown-option:has-text(\"{$colleagueName}\")")
        ->click('.q-aside button:has-text("Speichern")')
        ->wait(0.8)
        ->assertSeeIn('.q-aside', $colleagueName)
        ->assertDontSeeIn('.q-aside', 'keine Mitwirkenden');

    expect($task->fresh()->involvedEmployees->pluck('person_id')->all())->toBe([$colleague->person_id]);
});

test('picking another responsible employee saves right away', function () {
    $user = quickEditTaskUser();
    $task = Task::factory()->create(['employee_id' => $user->employee_id]);
    $colleague = Employee::factory()->create();
    $colleagueName = $colleague->person->name;
    $task->involvedEmployees()->attach($colleague->person_id, ['employee_type' => 'involved']);

    $this->actingAs($user);

    $page = visit(route('tasks.show', $task))->on()->desktop();

    $page->assertSeeIn('.q-quick-edit-people:nth-child(2)', $colleagueName)
        ->click('.q-aside button[aria-label="Verantwortlich ändern"]')
        ->click('.q-aside .vs__dropdown-toggle')
        ->click(".q-aside .vs__dropdown-option:has-text(\"{$colleagueName}\")")
        ->wait(1.0)
        ->assertSeeIn('.q-quick-edit-people:nth-child(1)', $colleagueName)
        // the new responsible employee is no longer listed as involved
        ->assertSeeIn('.q-quick-edit-people:nth-child(2)', 'keine Mitwirkenden');

    expect($task->fresh())
        ->employee_id->toBe($colleague->person_id)
        ->involvedEmployees->toHaveCount(0);
});
