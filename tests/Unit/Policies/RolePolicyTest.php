<?php

namespace Tests\Unit\Policies;

use App\Models\User;
use Spatie\Permission\Models\Role;

function otherRole(array $attributes = []): Role
{
    return Role::create(array_merge(['name' => fake()->unique()->word], $attributes));
}

test('viewAny is allowed with view permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'roles.view');

    expect($user->can('viewAny', Role::class))->toBeTrue();
});

test('viewAny is denied without view permission', function () {
    $user = User::factory()->create();

    expect($user->can('viewAny', Role::class))->toBeFalse();
});

test('view is allowed with view permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'roles.view');

    expect($user->can('view', otherRole()))->toBeTrue();
});

test('view is denied without view permission', function () {
    $user = User::factory()->create();

    expect($user->can('view', otherRole()))->toBeFalse();
});

test('create is allowed with create permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'roles.create');

    expect($user->can('create', Role::class))->toBeTrue();
});

test('create is denied without create permission', function () {
    $user = User::factory()->create();

    expect($user->can('create', Role::class))->toBeFalse();
});

test('update is allowed with update permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'roles.update');

    expect($user->can('update', otherRole()))->toBeTrue();
});

test('update is denied without update permission', function () {
    $user = User::factory()->create();

    expect($user->can('update', otherRole()))->toBeFalse();
});

test('delete is allowed with delete permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'roles.delete');

    expect($user->can('delete', otherRole()))->toBeTrue();
});

test('delete is denied without delete permission', function () {
    $user = User::factory()->create();

    expect($user->can('delete', otherRole()))->toBeFalse();
});
