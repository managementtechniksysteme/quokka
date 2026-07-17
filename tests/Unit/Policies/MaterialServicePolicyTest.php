<?php

namespace Tests\Unit\Policies;

use App\Models\MaterialService;
use App\Models\User;

test('viewAny is allowed with view permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'material-services.view');

    expect($user->can('viewAny', MaterialService::class))->toBeTrue();
});

test('viewAny is denied without view permission', function () {
    $user = User::factory()->create();

    expect($user->can('viewAny', MaterialService::class))->toBeFalse();
});

test('view is allowed with view permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'material-services.view');

    expect($user->can('view', MaterialService::factory()->create()))->toBeTrue();
});

test('view is denied without view permission', function () {
    $user = User::factory()->create();

    expect($user->can('view', MaterialService::factory()->create()))->toBeFalse();
});

test('create is allowed with create permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'material-services.create');

    expect($user->can('create', MaterialService::class))->toBeTrue();
});

test('create is denied without create permission', function () {
    $user = User::factory()->create();

    expect($user->can('create', MaterialService::class))->toBeFalse();
});

test('update is allowed with update permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'material-services.update');

    expect($user->can('update', MaterialService::factory()->create()))->toBeTrue();
});

test('update is denied without update permission', function () {
    $user = User::factory()->create();

    expect($user->can('update', MaterialService::factory()->create()))->toBeFalse();
});

test('delete is allowed with delete permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'material-services.delete');

    expect($user->can('delete', MaterialService::factory()->create()))->toBeTrue();
});

test('delete is denied without delete permission', function () {
    $user = User::factory()->create();

    expect($user->can('delete', MaterialService::factory()->create()))->toBeFalse();
});

test('email is allowed with email permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'material-services.email');

    expect($user->can('email', MaterialService::factory()->create()))->toBeTrue();
});

test('email is denied without email permission', function () {
    $user = User::factory()->create();

    expect($user->can('email', MaterialService::factory()->create()))->toBeFalse();
});

test('createPdf is allowed with createpdf permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'material-services.createpdf');

    expect($user->can('createPdf', MaterialService::factory()->create()))->toBeTrue();
});

test('createPdf is denied without createpdf permission', function () {
    $user = User::factory()->create();

    expect($user->can('createPdf', MaterialService::factory()->create()))->toBeFalse();
});
