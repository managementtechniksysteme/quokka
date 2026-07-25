<?php

namespace Tests\Unit\Policies;

use App\Models\Company;
use App\Models\User;

test('viewAny is allowed with view permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'companies.view');

    expect($user->can('viewAny', Company::class))->toBeTrue();
});

test('viewAny is denied without view permission', function () {
    $user = User::factory()->create();

    expect($user->can('viewAny', Company::class))->toBeFalse();
});

test('view is allowed with view permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'companies.view');

    expect($user->can('view', Company::factory()->create()))->toBeTrue();
});

test('view is denied without view permission', function () {
    $user = User::factory()->create();

    expect($user->can('view', Company::factory()->create()))->toBeFalse();
});

test('create is allowed with create permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'companies.create');

    expect($user->can('create', Company::class))->toBeTrue();
});

test('create is denied without create permission', function () {
    $user = User::factory()->create();

    expect($user->can('create', Company::class))->toBeFalse();
});

test('update is allowed with update permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'companies.update');

    expect($user->can('update', Company::factory()->create()))->toBeTrue();
});

test('update is denied without update permission', function () {
    $user = User::factory()->create();

    expect($user->can('update', Company::factory()->create()))->toBeFalse();
});

test('delete is allowed with delete permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'companies.delete');

    expect($user->can('delete', Company::factory()->create()))->toBeTrue();
});

test('delete is denied without delete permission', function () {
    $user = User::factory()->create();

    expect($user->can('delete', Company::factory()->create()))->toBeFalse();
});
