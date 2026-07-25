<?php

namespace Tests\Unit\Policies;

use App\Models\Memo;
use App\Models\User;

function senderMemo(User $user, array $attributes = []): Memo
{
    return Memo::factory()->create(array_merge(['employee_id' => $user->employee_id], $attributes));
}

function recipientMemo(User $user, array $attributes = []): Memo
{
    return Memo::factory()->create(array_merge(['person_id' => $user->employee_id], $attributes));
}

function presentMemo(User $user, array $attributes = []): Memo
{
    $memo = Memo::factory()->create($attributes);
    $memo->presentPeople()->attach($user->employee_id, ['person_type' => 'present']);

    return $memo->fresh();
}

function notifiedMemo(User $user, array $attributes = []): Memo
{
    $memo = Memo::factory()->create($attributes);
    $memo->notifiedPeople()->attach($user->employee_id, ['person_type' => 'notified']);

    return $memo->fresh();
}

function otherMemo(array $attributes = []): Memo
{
    return Memo::factory()->create($attributes);
}

// viewAny

test('viewAny is allowed with any one of the view permission tiers', function () {
    foreach (['memos.view.sender', 'memos.view.recipient', 'memos.view.present', 'memos.view.notified', 'memos.view.other'] as $permission) {
        $user = User::factory()->create();
        grantPermission($user, $permission);

        expect($user->can('viewAny', Memo::class))->toBeTrue();
    }
});

test('viewAny is denied without any view permission', function () {
    $user = User::factory()->create();

    expect($user->can('viewAny', Memo::class))->toBeFalse();
});

// view

test('view is allowed for a memo the user sent with view.sender permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'memos.view.sender');

    expect($user->can('view', senderMemo($user)))->toBeTrue();
});

test('view is allowed for a memo addressed to the user with view.recipient permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'memos.view.recipient');

    expect($user->can('view', recipientMemo($user)))->toBeTrue();
});

test('view is allowed for a memo the user was present at with view.present permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'memos.view.present');

    expect($user->can('view', presentMemo($user)))->toBeTrue();
});

test('view is allowed for a memo the user was notified of with view.notified permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'memos.view.notified');

    expect($user->can('view', notifiedMemo($user)))->toBeTrue();
});

test('view is allowed for an unrelated memo with view.other permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'memos.view.other');

    expect($user->can('view', otherMemo()))->toBeTrue();
});

test('view is denied for an unrelated memo without view.other permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'memos.view.sender');

    expect($user->can('view', otherMemo()))->toBeFalse();
});

// create

test('create is allowed with create permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'memos.create');

    expect($user->can('create', Memo::class))->toBeTrue();
});

test('create is denied without create permission', function () {
    $user = User::factory()->create();

    expect($user->can('create', Memo::class))->toBeFalse();
});

// update

test('update is allowed for a sent memo with update.sender permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'memos.update.sender');

    expect($user->can('update', senderMemo($user)))->toBeTrue();
});

test('update is allowed for an unrelated memo with update.other permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'memos.update.other');

    expect($user->can('update', otherMemo()))->toBeTrue();
});

test('update is denied without matching permission', function () {
    $user = User::factory()->create();

    expect($user->can('update', senderMemo($user)))->toBeFalse();
});

// delete

test('delete is allowed for a sent memo with delete.sender permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'memos.delete.sender');

    expect($user->can('delete', senderMemo($user)))->toBeTrue();
});

test('delete is allowed for an unrelated memo with delete.other permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'memos.delete.other');

    expect($user->can('delete', otherMemo()))->toBeTrue();
});

test('delete is denied without matching permission', function () {
    $user = User::factory()->create();

    expect($user->can('delete', senderMemo($user)))->toBeFalse();
});

// email (blocked entirely for drafts, regardless of permission)

test('email is allowed for a published memo with email.sender permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'memos.email.sender');

    expect($user->can('email', senderMemo($user, ['draft' => false])))->toBeTrue();
});

test('email is denied for a draft memo even with email.sender permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'memos.email.sender');

    expect($user->can('email', senderMemo($user, ['draft' => true])))->toBeFalse();
});

test('email is denied without matching permission', function () {
    $user = User::factory()->create();

    expect($user->can('email', otherMemo(['draft' => false])))->toBeFalse();
});

// createPdf (also blocked entirely for drafts)

test('createPdf is allowed for a published memo with createpdf.sender permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'memos.createpdf.sender');

    expect($user->can('createPdf', senderMemo($user, ['draft' => false])))->toBeTrue();
});

test('createPdf is denied for a draft memo even with createpdf.sender permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'memos.createpdf.sender');

    expect($user->can('createPdf', senderMemo($user, ['draft' => true])))->toBeFalse();
});

test('createPdf is denied without matching permission', function () {
    $user = User::factory()->create();

    expect($user->can('createPdf', otherMemo(['draft' => false])))->toBeFalse();
});
