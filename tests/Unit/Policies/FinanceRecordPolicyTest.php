<?php

namespace Tests\Unit\Policies;

use App\Models\FinanceRecord;
use App\Models\User;

test('viewAny is allowed with view permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'finance-records.view');

    expect($user->can('viewAny', FinanceRecord::class))->toBeTrue();
});

test('viewAny is denied without view permission', function () {
    $user = User::factory()->create();

    expect($user->can('viewAny', FinanceRecord::class))->toBeFalse();
});

test('view is allowed with view permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'finance-records.view');

    expect($user->can('view', FinanceRecord::factory()->create()))->toBeTrue();
});

test('view is denied without view permission', function () {
    $user = User::factory()->create();

    expect($user->can('view', FinanceRecord::factory()->create()))->toBeFalse();
});

test('create is allowed with create permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'finance-records.create');

    expect($user->can('create', FinanceRecord::class))->toBeTrue();
});

test('create is denied without create permission', function () {
    $user = User::factory()->create();

    expect($user->can('create', FinanceRecord::class))->toBeFalse();
});

test('update is allowed with update permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'finance-records.update');

    expect($user->can('update', FinanceRecord::factory()->create()))->toBeTrue();
});

test('update is denied without update permission', function () {
    $user = User::factory()->create();

    expect($user->can('update', FinanceRecord::factory()->create()))->toBeFalse();
});

test('delete is allowed with delete permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'finance-records.delete');

    expect($user->can('delete', FinanceRecord::factory()->create()))->toBeTrue();
});

test('delete is denied without delete permission', function () {
    $user = User::factory()->create();

    expect($user->can('delete', FinanceRecord::factory()->create()))->toBeFalse();
});
