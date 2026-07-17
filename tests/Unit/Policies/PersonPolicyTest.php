<?php

namespace Tests\Unit\Policies;

use App\Models\Person;
use App\Models\User;

test('viewAny is allowed with view permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'people.view');

    expect($user->can('viewAny', Person::class))->toBeTrue();
});

test('viewAny is denied without view permission', function () {
    $user = User::factory()->create();

    expect($user->can('viewAny', Person::class))->toBeFalse();
});

test('view is allowed with view permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'people.view');

    expect($user->can('view', Person::factory()->create()))->toBeTrue();
});

test('view is denied without view permission', function () {
    $user = User::factory()->create();

    expect($user->can('view', Person::factory()->create()))->toBeFalse();
});

test('create is allowed with create permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'people.create');

    expect($user->can('create', Person::class))->toBeTrue();
});

test('create is denied without create permission', function () {
    $user = User::factory()->create();

    expect($user->can('create', Person::class))->toBeFalse();
});

test('update is allowed with update permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'people.update');

    expect($user->can('update', Person::factory()->create()))->toBeTrue();
});

test('update is denied without update permission', function () {
    $user = User::factory()->create();

    expect($user->can('update', Person::factory()->create()))->toBeFalse();
});

test('delete is allowed with delete permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'people.delete');

    expect($user->can('delete', Person::factory()->create()))->toBeTrue();
});

test('delete is denied without delete permission', function () {
    $user = User::factory()->create();

    expect($user->can('delete', Person::factory()->create()))->toBeFalse();
});
