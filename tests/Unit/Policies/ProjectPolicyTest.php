<?php

namespace Tests\Unit\Policies;

use App\Models\Project;
use App\Models\User;

function project(array $attributes = []): Project
{
    return Project::factory()->create($attributes);
}

// viewAny

test('viewAny is allowed with view permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'projects.view');

    expect($user->can('viewAny', Project::class))->toBeTrue();
});

test('viewAny is denied without view permission', function () {
    $user = User::factory()->create();

    expect($user->can('viewAny', Project::class))->toBeFalse();
});

// view

test('view is allowed with view permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'projects.view');

    expect($user->can('view', project()))->toBeTrue();
});

test('view is denied without view permission', function () {
    $user = User::factory()->create();

    expect($user->can('view', project()))->toBeFalse();
});

// create

test('create is allowed with create permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'projects.create');

    expect($user->can('create', Project::class))->toBeTrue();
});

test('create is denied without create permission', function () {
    $user = User::factory()->create();

    expect($user->can('create', Project::class))->toBeFalse();
});

// update

test('update is allowed with update permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'projects.update');

    expect($user->can('update', project()))->toBeTrue();
});

test('update is denied without update permission', function () {
    $user = User::factory()->create();

    expect($user->can('update', project()))->toBeFalse();
});

// delete

test('delete is allowed with delete permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'projects.delete');

    expect($user->can('delete', project()))->toBeTrue();
});

test('delete is denied without delete permission', function () {
    $user = User::factory()->create();

    expect($user->can('delete', project()))->toBeFalse();
});

// email

test('email is allowed with email permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'projects.email');

    expect($user->can('email', project()))->toBeTrue();
});

test('email is denied without email permission', function () {
    $user = User::factory()->create();

    expect($user->can('email', project()))->toBeFalse();
});

// createPdf

test('createPdf is allowed with createpdf permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'projects.createpdf');

    expect($user->can('createPdf', project()))->toBeTrue();
});

test('createPdf is denied without createpdf permission', function () {
    $user = User::factory()->create();

    expect($user->can('createPdf', project()))->toBeFalse();
});

// downloadList

test('downloadList is allowed with view permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'projects.view');

    expect($user->can('downloadList', Project::class))->toBeTrue();
});

test('downloadList is denied without view permission', function () {
    $user = User::factory()->create();

    expect($user->can('downloadList', Project::class))->toBeFalse();
});
