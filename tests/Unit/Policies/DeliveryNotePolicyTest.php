<?php

namespace Tests\Unit\Policies;

use App\Models\DeliveryNote;
use App\Models\User;

function deliveryNote(array $attributes = []): DeliveryNote
{
    return DeliveryNote::factory()->create($attributes);
}

// viewAny

test('viewAny is allowed with view permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'delivery-notes.view');

    expect($user->can('viewAny', DeliveryNote::class))->toBeTrue();
});

test('viewAny is denied without view permission', function () {
    $user = User::factory()->create();

    expect($user->can('viewAny', DeliveryNote::class))->toBeFalse();
});

// view

test('view is allowed with view permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'delivery-notes.view');

    expect($user->can('view', deliveryNote()))->toBeTrue();
});

test('view is denied without view permission', function () {
    $user = User::factory()->create();

    expect($user->can('view', deliveryNote()))->toBeFalse();
});

// create

test('create is allowed with create permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'delivery-notes.create');

    expect($user->can('create', DeliveryNote::class))->toBeTrue();
});

test('create is denied without create permission', function () {
    $user = User::factory()->create();

    expect($user->can('create', DeliveryNote::class))->toBeFalse();
});

// update

test('update is denied when note is finished, regardless of permissions', function () {
    $user = User::factory()->create();
    grantPermission($user, 'delivery-notes.update');

    expect($user->can('update', deliveryNote(['status' => 'finished'])))->toBeFalse();
});

test('update is allowed for a non-finished note with update permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'delivery-notes.update');

    expect($user->can('update', deliveryNote(['status' => 'new'])))->toBeTrue();
});

test('update is denied without update permission', function () {
    $user = User::factory()->create();

    expect($user->can('update', deliveryNote(['status' => 'new'])))->toBeFalse();
});

// delete

test('delete is denied when note is finished, regardless of permissions', function () {
    $user = User::factory()->create();
    grantPermission($user, 'delivery-notes.approve');
    grantPermission($user, 'delivery-notes.delete');

    expect($user->can('delete', deliveryNote(['status' => 'finished'])))->toBeFalse();
});

test('delete of a signed note requires approve permission, not delete', function () {
    $user = User::factory()->create();
    grantPermission($user, 'delivery-notes.delete');

    expect($user->can('delete', deliveryNote(['status' => 'signed'])))->toBeFalse();
});

test('delete of a signed note is allowed with approve permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'delivery-notes.approve');

    expect($user->can('delete', deliveryNote(['status' => 'signed'])))->toBeTrue();
});

test('delete is allowed for a new note with delete permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'delivery-notes.delete');

    expect($user->can('delete', deliveryNote(['status' => 'new'])))->toBeTrue();
});

// email

test('email is allowed with email permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'delivery-notes.email');

    expect($user->can('email', deliveryNote()))->toBeTrue();
});

test('email is denied without email permission', function () {
    $user = User::factory()->create();

    expect($user->can('email', deliveryNote()))->toBeFalse();
});

// createPdf

test('createPdf is allowed with createpdf permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'delivery-notes.createpdf');

    expect($user->can('createPdf', deliveryNote()))->toBeTrue();
});

test('createPdf is denied without createpdf permission', function () {
    $user = User::factory()->create();

    expect($user->can('createPdf', deliveryNote()))->toBeFalse();
});

// emailSignatureRequest

test('emailSignatureRequest is denied when note is not new', function () {
    $user = User::factory()->create();
    grantPermission($user, 'delivery-notes.send-signature-request');

    expect($user->can('emailSignatureRequest', deliveryNote(['status' => 'signed'])))->toBeFalse();
});

test('emailSignatureRequest is allowed for a new note with send-signature-request permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'delivery-notes.send-signature-request');

    expect($user->can('emailSignatureRequest', deliveryNote(['status' => 'new'])))->toBeTrue();
});

// emailDownloadRequest

test('emailDownloadRequest is denied when note is new', function () {
    $user = User::factory()->create();
    grantPermission($user, 'delivery-notes.send-download-request');

    expect($user->can('emailDownloadRequest', deliveryNote(['status' => 'new'])))->toBeFalse();
});

test('emailDownloadRequest is allowed for a signed note with send-download-request permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'delivery-notes.send-download-request');

    expect($user->can('emailDownloadRequest', deliveryNote(['status' => 'signed'])))->toBeTrue();
});

test('emailDownloadRequest is allowed for a finished note with send-download-request permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'delivery-notes.send-download-request');

    expect($user->can('emailDownloadRequest', deliveryNote(['status' => 'finished'])))->toBeTrue();
});

// sign

test('sign is denied when note is not new', function () {
    $user = User::factory()->create();
    grantPermission($user, 'delivery-notes.get-signature');

    expect($user->can('sign', deliveryNote(['status' => 'signed'])))->toBeFalse();
});

test('sign is allowed for a new note with get-signature permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'delivery-notes.get-signature');

    expect($user->can('sign', deliveryNote(['status' => 'new'])))->toBeTrue();
});

// approve

test('approve is allowed with approve permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'delivery-notes.approve');

    expect($user->can('approve', deliveryNote()))->toBeTrue();
});

test('approve is denied without approve permission', function () {
    $user = User::factory()->create();

    expect($user->can('approve', deliveryNote()))->toBeFalse();
});
