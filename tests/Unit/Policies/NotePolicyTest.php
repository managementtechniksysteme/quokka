<?php

namespace Tests\Unit\Policies;

use App\Models\Note;
use App\Models\User;

function noteFor(User $user, array $attributes = []): Note
{
    return Note::factory()->create(array_merge(['employee_id' => $user->employee_id], $attributes));
}

function othersNote(array $attributes = []): Note
{
    return Note::factory()->create($attributes);
}

// viewAny

test('viewAny is allowed with view permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'notes.view');

    expect($user->can('viewAny', Note::class))->toBeTrue();
});

test('viewAny is denied without view permission', function () {
    $user = User::factory()->create();

    expect($user->can('viewAny', Note::class))->toBeFalse();
});

// view

test('view is allowed for your own note with view permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'notes.view');

    expect($user->can('view', noteFor($user)))->toBeTrue();
});

test('view is denied for someone else\'s note even with view permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'notes.view');

    expect($user->can('view', othersNote()))->toBeFalse();
});

test('view is denied for your own note without view permission', function () {
    $user = User::factory()->create();

    expect($user->can('view', noteFor($user)))->toBeFalse();
});

// create

test('create is allowed with create permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'notes.create');

    expect($user->can('create', Note::class))->toBeTrue();
});

test('create is denied without create permission', function () {
    $user = User::factory()->create();

    expect($user->can('create', Note::class))->toBeFalse();
});

// update

test('update is allowed for your own note with update permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'notes.update');

    expect($user->can('update', noteFor($user)))->toBeTrue();
});

test('update is denied for someone else\'s note even with update permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'notes.update');

    expect($user->can('update', othersNote()))->toBeFalse();
});

// delete

test('delete is allowed for your own note with delete permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'notes.delete');

    expect($user->can('delete', noteFor($user)))->toBeTrue();
});

test('delete is denied for someone else\'s note even with delete permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'notes.delete');

    expect($user->can('delete', othersNote()))->toBeFalse();
});

// email

test('email is allowed for your own note with email permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'notes.email');

    expect($user->can('email', noteFor($user)))->toBeTrue();
});

test('email is denied for someone else\'s note even with email permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'notes.email');

    expect($user->can('email', othersNote()))->toBeFalse();
});

// createPdf

test('createPdf is allowed for your own note with createpdf permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'notes.createpdf');

    expect($user->can('createPdf', noteFor($user)))->toBeTrue();
});

test('createPdf is denied for someone else\'s note even with createpdf permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'notes.createpdf');

    expect($user->can('createPdf', othersNote()))->toBeFalse();
});

// downloadList

test('downloadList is allowed with view permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'notes.view');

    expect($user->can('downloadList', Note::class))->toBeTrue();
});

test('downloadList is denied without view permission', function () {
    $user = User::factory()->create();

    expect($user->can('downloadList', Note::class))->toBeFalse();
});
