<?php

namespace Tests\Unit\Policies;

use App\Models\User;
use App\Models\WageService;

test('viewAny is allowed with view permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'wage-services.view');

    expect($user->can('viewAny', WageService::class))->toBeTrue();
});

test('viewAny is denied without view permission', function () {
    $user = User::factory()->create();

    expect($user->can('viewAny', WageService::class))->toBeFalse();
});

test('view is allowed with view permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'wage-services.view');

    expect($user->can('view', WageService::factory()->create()))->toBeTrue();
});

test('view is denied without view permission', function () {
    $user = User::factory()->create();

    expect($user->can('view', WageService::factory()->create()))->toBeFalse();
});

test('create is allowed with create permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'wage-services.create');

    expect($user->can('create', WageService::class))->toBeTrue();
});

test('create is denied without create permission', function () {
    $user = User::factory()->create();

    expect($user->can('create', WageService::class))->toBeFalse();
});

test('update is allowed with update permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'wage-services.update');

    expect($user->can('update', WageService::factory()->create()))->toBeTrue();
});

test('update is denied without update permission', function () {
    $user = User::factory()->create();

    expect($user->can('update', WageService::factory()->create()))->toBeFalse();
});

test('delete is allowed with delete permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'wage-services.delete');

    expect($user->can('delete', WageService::factory()->create()))->toBeTrue();
});

test('delete is denied without delete permission', function () {
    $user = User::factory()->create();

    expect($user->can('delete', WageService::factory()->create()))->toBeFalse();
});

test('email is allowed with email permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'wage-services.email');

    expect($user->can('email', WageService::factory()->create()))->toBeTrue();
});

test('email is denied without email permission', function () {
    $user = User::factory()->create();

    expect($user->can('email', WageService::factory()->create()))->toBeFalse();
});

test('createPdf is allowed with createpdf permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'wage-services.createpdf');

    expect($user->can('createPdf', WageService::factory()->create()))->toBeTrue();
});

test('createPdf is denied without createpdf permission', function () {
    $user = User::factory()->create();

    expect($user->can('createPdf', WageService::factory()->create()))->toBeFalse();
});
