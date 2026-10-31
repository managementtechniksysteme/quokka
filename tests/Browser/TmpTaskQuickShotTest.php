<?php

use App\Models\Employee;
use App\Models\Task;
use App\Models\User;

test('tmp task quick shots', function () {
    $user = User::factory()->create();
    foreach (['tasks.view.responsible', 'tasks.update.responsible', 'tasks.create', 'tasks.create.private'] as $p) { grantPermission($user, $p); }
    $task = Task::factory()->create(['employee_id' => $user->employee_id, 'status' => 'in progress', 'priority' => 'medium']);
    $task->involvedEmployees()->attach(Employee::factory()->count(2)->create()->pluck('person_id'), ['employee_type' => 'involved']);
    $this->actingAs($user);

    $d = visit(route('tasks.show', $task))->on()->desktop()->wait(0.8);
    $d->screenshot(filename: 'tmp-tq-desktop')
      ->click('.q-page-head button[aria-label="Priorität ändern"]')->wait(0.5)->screenshot(filename: 'tmp-tq-desktop-open');

    visit(route('tasks.show', $task))->on()->mobile()->wait(0.8)->screenshot(filename: 'tmp-tq-mobile')
      ->click('.q-meta.d-flex.d-md-none button[aria-label="Priorität ändern"]')->wait(0.8)->screenshot(filename: 'tmp-tq-mobile-sheet');
});
