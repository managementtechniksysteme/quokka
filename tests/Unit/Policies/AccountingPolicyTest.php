<?php

namespace Tests\Unit\Policies;

use App\Models\Accounting;
use App\Models\User;

function ownAccounting(User $user, array $attributes = []): Accounting
{
    return Accounting::factory()->create(array_merge(['employee_id' => $user->employee_id], $attributes));
}

function otherAccounting(array $attributes = []): Accounting
{
    return Accounting::factory()->create($attributes);
}

// viewAny

test('viewAny is allowed with either view permission', function () {
    foreach (['accounting.view.own', 'accounting.view.other'] as $permission) {
        $user = User::factory()->create();
        grantPermission($user, $permission);

        expect($user->can('viewAny', Accounting::class))->toBeTrue();
    }
});

test('viewAny is denied without any view permission', function () {
    $user = User::factory()->create();

    expect($user->can('viewAny', Accounting::class))->toBeFalse();
});

// create

test('create is allowed with create permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'accounting.create');

    expect($user->can('create', Accounting::class))->toBeTrue();
});

test('create is denied without create permission', function () {
    $user = User::factory()->create();

    expect($user->can('create', Accounting::class))->toBeFalse();
});

// view

test('view is allowed for an own entry with view.own permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'accounting.view.own');

    expect($user->can('view', ownAccounting($user)))->toBeTrue();
});

test('view is denied for an own entry with only view.other permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'accounting.view.other');

    expect($user->can('view', ownAccounting($user)))->toBeFalse();
});

test('view is allowed for another employee\'s entry with view.other permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'accounting.view.other');

    expect($user->can('view', otherAccounting()))->toBeTrue();
});

// update

test('update is allowed for an own entry with update.own permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'accounting.update.own');

    expect($user->can('update', ownAccounting($user)))->toBeTrue();
});

test('update is allowed for another employee\'s entry with update.other permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'accounting.update.other');

    expect($user->can('update', otherAccounting()))->toBeTrue();
});

test('update is denied without matching permission', function () {
    $user = User::factory()->create();

    expect($user->can('update', ownAccounting($user)))->toBeFalse();
});

// delete

test('delete is allowed for an own entry with delete.own permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'accounting.delete.own');

    expect($user->can('delete', ownAccounting($user)))->toBeTrue();
});

test('delete is allowed for another employee\'s entry with delete.other permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'accounting.delete.other');

    expect($user->can('delete', otherAccounting()))->toBeTrue();
});

test('delete is denied without matching permission', function () {
    $user = User::factory()->create();

    expect($user->can('delete', ownAccounting($user)))->toBeFalse();
});

// email

test('email is allowed with email permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'accounting.email');

    expect($user->can('email', otherAccounting()))->toBeTrue();
});

test('email is denied without email permission', function () {
    $user = User::factory()->create();

    expect($user->can('email', otherAccounting()))->toBeFalse();
});

// createPdf

test('createPdf is allowed with createpdf permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'accounting.createpdf');

    expect($user->can('createPdf', Accounting::class))->toBeTrue();
});

test('createPdf is denied without createpdf permission', function () {
    $user = User::factory()->create();

    expect($user->can('createPdf', Accounting::class))->toBeFalse();
});
