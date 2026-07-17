<?php

namespace Tests\Unit\Policies;

use App\Models\Employee;
use App\Models\User;

test('viewAny is allowed with view permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'employees.view');

    expect($user->can('viewAny', Employee::class))->toBeTrue();
});

test('viewAny is denied without view permission', function () {
    $user = User::factory()->create();

    expect($user->can('viewAny', Employee::class))->toBeFalse();
});

test('view is allowed with view permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'employees.view');

    expect($user->can('view', Employee::factory()->create()))->toBeTrue();
});

test('view is denied without view permission', function () {
    $user = User::factory()->create();

    expect($user->can('view', Employee::factory()->create()))->toBeFalse();
});

test('create is allowed with create permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'employees.create');

    expect($user->can('create', Employee::class))->toBeTrue();
});

test('create is denied without create permission', function () {
    $user = User::factory()->create();

    expect($user->can('create', Employee::class))->toBeFalse();
});

test('update is allowed with update permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'employees.update');

    expect($user->can('update', Employee::factory()->create()))->toBeTrue();
});

test('update is denied without update permission', function () {
    $user = User::factory()->create();

    expect($user->can('update', Employee::factory()->create()))->toBeFalse();
});

test('delete is allowed with delete permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'employees.delete');

    expect($user->can('delete', Employee::factory()->create()))->toBeTrue();
});

test('delete is denied without delete permission', function () {
    $user = User::factory()->create();

    expect($user->can('delete', Employee::factory()->create()))->toBeFalse();
});

// impersonate

test('impersonate is allowed for another employee with impersonate permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'employees.impersonate');
    $target = Employee::factory()->create();

    expect($user->can('impersonate', $target))->toBeTrue();
});

test('impersonate is denied for another employee without impersonate permission', function () {
    $user = User::factory()->create();
    $target = Employee::factory()->create();

    expect($user->can('impersonate', $target))->toBeFalse();
});

test('impersonate is denied for yourself even with impersonate permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'employees.impersonate');

    expect($user->can('impersonate', $user->employee))->toBeFalse();
});
