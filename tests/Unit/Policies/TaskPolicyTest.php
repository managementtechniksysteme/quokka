<?php

namespace Tests\Unit\Policies;

use App\Models\Task;
use App\Models\User;

function responsibleTask(User $user, array $attributes = []): Task
{
    return Task::factory()->create(array_merge(['employee_id' => $user->employee_id], $attributes));
}

function involvedTask(User $user, array $attributes = []): Task
{
    $task = Task::factory()->create($attributes);
    $task->involvedEmployees()->attach($user->employee_id, ['employee_type' => 'involved']);

    return $task->fresh();
}

function otherTask(array $attributes = []): Task
{
    return Task::factory()->create($attributes);
}

// viewAny

test('viewAny is allowed with any one of the view permission tiers', function () {
    foreach (['tasks.view.responsible', 'tasks.view.involved', 'tasks.view.other', 'tasks.view.private.responsible', 'tasks.view.private.involved', 'tasks.view.private.other'] as $permission) {
        $user = User::factory()->create();
        grantPermission($user, $permission);

        expect($user->can('viewAny', Task::class))->toBeTrue();
    }
});

test('viewAny is denied without any view permission', function () {
    $user = User::factory()->create();

    expect($user->can('viewAny', Task::class))->toBeFalse();
});

// view - public

test('view is allowed for a responsible public task with view.responsible permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'tasks.view.responsible');

    expect($user->can('view', responsibleTask($user)))->toBeTrue();
});

test('view is denied for a responsible public task without view.responsible permission', function () {
    $user = User::factory()->create();

    expect($user->can('view', responsibleTask($user)))->toBeFalse();
});

test('view is allowed for an involved public task with view.involved permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'tasks.view.involved');

    expect($user->can('view', involvedTask($user)))->toBeTrue();
});

test('view is allowed for an other public task with view.other permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'tasks.view.other');

    expect($user->can('view', otherTask()))->toBeTrue();
});

test('view is denied for an other public task without view.other permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'tasks.view.responsible');

    expect($user->can('view', otherTask()))->toBeFalse();
});

// view - private (separate permission tier entirely, public permissions don't carry over)

test('view is denied for a private task with only the public-tier permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'tasks.view.responsible');

    expect($user->can('view', responsibleTask($user, ['private' => true])))->toBeFalse();
});

test('view is allowed for a responsible private task with view.private.responsible permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'tasks.view.private.responsible');

    expect($user->can('view', responsibleTask($user, ['private' => true])))->toBeTrue();
});

test('view is allowed for an involved private task with view.private.involved permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'tasks.view.private.involved');

    expect($user->can('view', involvedTask($user, ['private' => true])))->toBeTrue();
});

test('view is allowed for an other private task with view.private.other permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'tasks.view.private.other');

    expect($user->can('view', otherTask(['private' => true])))->toBeTrue();
});

// create

test('create is allowed with create permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'tasks.create');

    expect($user->can('create', Task::class))->toBeTrue();
});

test('create is allowed with create.private permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'tasks.create.private');

    expect($user->can('create', Task::class))->toBeTrue();
});

test('create is denied without any create permission', function () {
    $user = User::factory()->create();

    expect($user->can('create', Task::class))->toBeFalse();
});

// update

test('update is allowed for a responsible public task with update.responsible permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'tasks.update.responsible');

    expect($user->can('update', responsibleTask($user)))->toBeTrue();
});

test('update is allowed for an involved public task with update.involved permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'tasks.update.involved');

    expect($user->can('update', involvedTask($user)))->toBeTrue();
});

test('update is allowed for an other public task with update.other permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'tasks.update.other');

    expect($user->can('update', otherTask()))->toBeTrue();
});

test('update is allowed for a responsible private task with update.private.responsible permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'tasks.update.private.responsible');

    expect($user->can('update', responsibleTask($user, ['private' => true])))->toBeTrue();
});

test('update is denied for a private task with only the public-tier permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'tasks.update.responsible');

    expect($user->can('update', responsibleTask($user, ['private' => true])))->toBeFalse();
});

// delete

test('delete is allowed for a responsible public task with delete.responsible permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'tasks.delete.responsible');

    expect($user->can('delete', responsibleTask($user)))->toBeTrue();
});

test('delete is allowed for an involved public task with delete.involved permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'tasks.delete.involved');

    expect($user->can('delete', involvedTask($user)))->toBeTrue();
});

test('delete is allowed for an other public task with delete.other permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'tasks.delete.other');

    expect($user->can('delete', otherTask()))->toBeTrue();
});

test('delete is allowed for an other private task with delete.private.other permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'tasks.delete.private.other');

    expect($user->can('delete', otherTask(['private' => true])))->toBeTrue();
});

test('delete is denied without matching permission', function () {
    $user = User::factory()->create();

    expect($user->can('delete', responsibleTask($user)))->toBeFalse();
});

// email

test('email is allowed for a responsible task with email.responsible permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'tasks.email.responsible');

    expect($user->can('email', responsibleTask($user)))->toBeTrue();
});

test('email is allowed for a private involved task with email.private.involved permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'tasks.email.private.involved');

    expect($user->can('email', involvedTask($user, ['private' => true])))->toBeTrue();
});

test('email is denied without matching permission', function () {
    $user = User::factory()->create();

    expect($user->can('email', otherTask()))->toBeFalse();
});

// createPdf

test('createPdf is allowed for a responsible task with createpdf.responsible permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'tasks.createpdf.responsible');

    expect($user->can('createPdf', responsibleTask($user)))->toBeTrue();
});

test('createPdf is allowed for an other task with createpdf.other permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'tasks.createpdf.other');

    expect($user->can('createPdf', otherTask()))->toBeTrue();
});

test('createPdf is denied without matching permission', function () {
    $user = User::factory()->create();

    expect($user->can('createPdf', otherTask()))->toBeFalse();
});

// downloadList

test('downloadList is allowed with any one of the view permission tiers', function () {
    $user = User::factory()->create();
    grantPermission($user, 'tasks.view.private.other');

    expect($user->can('downloadList', Task::class))->toBeTrue();
});

test('downloadList is denied without any view permission', function () {
    $user = User::factory()->create();

    expect($user->can('downloadList', Task::class))->toBeFalse();
});
