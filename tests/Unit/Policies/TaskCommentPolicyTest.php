<?php

namespace Tests\Unit\Policies;

use App\Models\Task;
use App\Models\TaskComment;
use App\Models\User;

function ownComment(User $user, array $attributes = []): TaskComment
{
    return TaskComment::factory()->create(array_merge(['employee_id' => $user->employee_id], $attributes));
}

function otherComment(array $attributes = []): TaskComment
{
    return TaskComment::factory()->create($attributes);
}

// viewAny

test('viewAny without a task is allowed with any task view permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'tasks.view.responsible');

    expect($user->can('viewAny', TaskComment::class))->toBeTrue();
});

test('viewAny without a task is denied without any task view permission', function () {
    $user = User::factory()->create();

    expect($user->can('viewAny', TaskComment::class))->toBeFalse();
});

test('viewAny with a task defers to viewing that task', function () {
    $user = User::factory()->create();
    grantPermission($user, 'tasks.view.responsible');
    $task = Task::factory()->create(['employee_id' => $user->employee_id]);

    expect($user->can('viewAny', [TaskComment::class, $task]))->toBeTrue();
});

// view

test('view is allowed when the user can view the underlying task', function () {
    $user = User::factory()->create();
    grantPermission($user, 'tasks.view.responsible');
    $task = Task::factory()->create(['employee_id' => $user->employee_id]);
    $comment = TaskComment::factory()->create(['task_id' => $task->id]);

    expect($user->can('view', $comment))->toBeTrue();
});

test('view is denied when the user cannot view the underlying task', function () {
    $user = User::factory()->create();
    $task = Task::factory()->create();
    $comment = TaskComment::factory()->create(['task_id' => $task->id]);

    expect($user->can('view', $comment))->toBeFalse();
});

// create

test('create is allowed with view on the task and comments.create permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'tasks.view.responsible');
    grantPermission($user, 'tasks.comments.create');
    $task = Task::factory()->create(['employee_id' => $user->employee_id]);

    expect($user->can('create', [TaskComment::class, $task]))->toBeTrue();
});

test('create is denied without comments.create permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'tasks.view.responsible');
    $task = Task::factory()->create(['employee_id' => $user->employee_id]);

    expect($user->can('create', [TaskComment::class, $task]))->toBeFalse();
});

test('create is denied without view on the task', function () {
    $user = User::factory()->create();
    grantPermission($user, 'tasks.comments.create');
    $task = Task::factory()->create();

    expect($user->can('create', [TaskComment::class, $task]))->toBeFalse();
});

// update

test('update is allowed for an own comment with comments.update.own permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'tasks.view.responsible');
    grantPermission($user, 'tasks.comments.update.own');
    $task = Task::factory()->create(['employee_id' => $user->employee_id]);

    expect($user->can('update', ownComment($user, ['task_id' => $task->id])))->toBeTrue();
});

test('update is denied for an own comment with only comments.update.other permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'tasks.view.responsible');
    grantPermission($user, 'tasks.comments.update.other');
    $task = Task::factory()->create(['employee_id' => $user->employee_id]);

    expect($user->can('update', ownComment($user, ['task_id' => $task->id])))->toBeFalse();
});

test('update is allowed for another employee\'s comment with comments.update.other permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'tasks.view.responsible');
    grantPermission($user, 'tasks.comments.update.other');
    $task = Task::factory()->create(['employee_id' => $user->employee_id]);

    expect($user->can('update', otherComment(['task_id' => $task->id])))->toBeTrue();
});

test('update is denied without view on the underlying task', function () {
    $user = User::factory()->create();
    grantPermission($user, 'tasks.comments.update.own');

    expect($user->can('update', ownComment($user)))->toBeFalse();
});

// delete

test('delete is allowed for an own comment with comments.delete.own permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'tasks.view.responsible');
    grantPermission($user, 'tasks.comments.delete.own');
    $task = Task::factory()->create(['employee_id' => $user->employee_id]);

    expect($user->can('delete', ownComment($user, ['task_id' => $task->id])))->toBeTrue();
});

test('delete is denied for an own comment with only comments.delete.other permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'tasks.view.responsible');
    grantPermission($user, 'tasks.comments.delete.other');
    $task = Task::factory()->create(['employee_id' => $user->employee_id]);

    expect($user->can('delete', ownComment($user, ['task_id' => $task->id])))->toBeFalse();
});

test('delete is allowed for another employee\'s comment with comments.delete.other permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'tasks.view.responsible');
    grantPermission($user, 'tasks.comments.delete.other');
    $task = Task::factory()->create(['employee_id' => $user->employee_id]);

    expect($user->can('delete', otherComment(['task_id' => $task->id])))->toBeTrue();
});

test('delete is denied without view on the underlying task', function () {
    $user = User::factory()->create();
    grantPermission($user, 'tasks.comments.delete.own');

    expect($user->can('delete', ownComment($user)))->toBeFalse();
});
