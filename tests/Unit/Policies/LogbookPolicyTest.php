<?php

namespace Tests\Unit\Policies;

use App\Models\Logbook;
use App\Models\User;

function ownLogbookEntry(User $user, array $attributes = []): Logbook
{
    return Logbook::factory()->create(array_merge(['employee_id' => $user->employee_id], $attributes));
}

function otherLogbookEntry(array $attributes = []): Logbook
{
    return Logbook::factory()->create($attributes);
}

// viewAny

test('viewAny is allowed with either view permission', function () {
    foreach (['logbook.view.own', 'logbook.view.other'] as $permission) {
        $user = User::factory()->create();
        grantPermission($user, $permission);

        expect($user->can('viewAny', Logbook::class))->toBeTrue();
    }
});

test('viewAny is denied without any view permission', function () {
    $user = User::factory()->create();

    expect($user->can('viewAny', Logbook::class))->toBeFalse();
});

// create

test('create is allowed with create permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'logbook.create');

    expect($user->can('create', Logbook::class))->toBeTrue();
});

test('create is denied without create permission', function () {
    $user = User::factory()->create();

    expect($user->can('create', Logbook::class))->toBeFalse();
});

// view

test('view is allowed for an own entry with view.own permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'logbook.view.own');

    expect($user->can('view', ownLogbookEntry($user)))->toBeTrue();
});

test('view is denied for an own entry with only view.other permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'logbook.view.other');

    expect($user->can('view', ownLogbookEntry($user)))->toBeFalse();
});

test('view is allowed for another employee\'s entry with view.other permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'logbook.view.other');

    expect($user->can('view', otherLogbookEntry()))->toBeTrue();
});

// update

test('update is allowed for an own entry with update.own permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'logbook.update.own');

    expect($user->can('update', ownLogbookEntry($user)))->toBeTrue();
});

test('update is allowed for another employee\'s entry with update.other permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'logbook.update.other');

    expect($user->can('update', otherLogbookEntry()))->toBeTrue();
});

test('update is denied without matching permission', function () {
    $user = User::factory()->create();

    expect($user->can('update', ownLogbookEntry($user)))->toBeFalse();
});

// delete

test('delete is allowed for an own entry with delete.own permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'logbook.delete.own');

    expect($user->can('delete', ownLogbookEntry($user)))->toBeTrue();
});

test('delete is allowed for another employee\'s entry with delete.other permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'logbook.delete.other');

    expect($user->can('delete', otherLogbookEntry()))->toBeTrue();
});

test('delete is denied without matching permission', function () {
    $user = User::factory()->create();

    expect($user->can('delete', ownLogbookEntry($user)))->toBeFalse();
});

// email

test('email is allowed with email permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'logbook.email');

    expect($user->can('email', otherLogbookEntry()))->toBeTrue();
});

test('email is denied without email permission', function () {
    $user = User::factory()->create();

    expect($user->can('email', otherLogbookEntry()))->toBeFalse();
});

// createPdf

test('createPdf is allowed with createpdf permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'logbook.createpdf');

    expect($user->can('createPdf', otherLogbookEntry()))->toBeTrue();
});

test('createPdf is denied without createpdf permission', function () {
    $user = User::factory()->create();

    expect($user->can('createPdf', otherLogbookEntry()))->toBeFalse();
});
