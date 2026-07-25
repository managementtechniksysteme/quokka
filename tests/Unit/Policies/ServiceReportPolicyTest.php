<?php

namespace Tests\Unit\Policies;

use App\Models\ServiceReport;
use App\Models\User;

function ownServiceReport(User $user, array $attributes = [])
{
    return ServiceReport::factory()->create(array_merge(['employee_id' => $user->employee_id], $attributes));
}

function otherServiceReport(array $attributes = [])
{
    return ServiceReport::factory()->create($attributes);
}

// viewAny

test('viewAny is allowed with view.own permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'service-reports.view.own');

    expect($user->can('viewAny', ServiceReport::class))->toBeTrue();
});

test('viewAny is allowed with view.other permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'service-reports.view.other');

    expect($user->can('viewAny', ServiceReport::class))->toBeTrue();
});

test('viewAny is denied without view permissions', function () {
    $user = User::factory()->create();

    expect($user->can('viewAny', ServiceReport::class))->toBeFalse();
});

// view

test('view is allowed for own report with view.own permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'service-reports.view.own');
    $serviceReport = ownServiceReport($user);

    expect($user->can('view', $serviceReport))->toBeTrue();
});

test('view is denied for own report without view.own permission', function () {
    $user = User::factory()->create();
    $serviceReport = ownServiceReport($user);

    expect($user->can('view', $serviceReport))->toBeFalse();
});

test('view is allowed for other report with view.other permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'service-reports.view.other');
    $serviceReport = otherServiceReport();

    expect($user->can('view', $serviceReport))->toBeTrue();
});

test('view is denied for other report without view.other permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'service-reports.view.own');
    $serviceReport = otherServiceReport();

    expect($user->can('view', $serviceReport))->toBeFalse();
});

// create

test('create is allowed with create permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'service-reports.create');

    expect($user->can('create', ServiceReport::class))->toBeTrue();
});

test('create is denied without create permission', function () {
    $user = User::factory()->create();

    expect($user->can('create', ServiceReport::class))->toBeFalse();
});

// update

test('update is denied when report is finished, regardless of permissions', function () {
    $user = User::factory()->create();
    grantPermission($user, 'service-reports.update.own');
    $serviceReport = ownServiceReport($user, ['status' => 'finished']);

    expect($user->can('update', $serviceReport))->toBeFalse();
});

test('update is allowed for own non-finished report with update.own permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'service-reports.update.own');
    $serviceReport = ownServiceReport($user, ['status' => 'new']);

    expect($user->can('update', $serviceReport))->toBeTrue();
});

test('update is allowed for other non-finished report with update.other permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'service-reports.update.other');
    $serviceReport = otherServiceReport(['status' => 'signed']);

    expect($user->can('update', $serviceReport))->toBeTrue();
});

test('update is denied for other non-finished report without update.other permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'service-reports.update.own');
    $serviceReport = otherServiceReport(['status' => 'new']);

    expect($user->can('update', $serviceReport))->toBeFalse();
});

// delete

test('delete is denied when report is finished, regardless of permissions', function () {
    $user = User::factory()->create();
    grantPermission($user, 'service-reports.approve');
    grantPermission($user, 'service-reports.delete.own');
    $serviceReport = ownServiceReport($user, ['status' => 'finished']);

    expect($user->can('delete', $serviceReport))->toBeFalse();
});

test('delete of a signed report requires approve permission, not delete.own', function () {
    $user = User::factory()->create();
    grantPermission($user, 'service-reports.delete.own');
    $serviceReport = ownServiceReport($user, ['status' => 'signed']);

    expect($user->can('delete', $serviceReport))->toBeFalse();
});

test('delete of a signed report is allowed with approve permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'service-reports.approve');
    $serviceReport = ownServiceReport($user, ['status' => 'signed']);

    expect($user->can('delete', $serviceReport))->toBeTrue();
});

test('delete is allowed for own new report with delete.own permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'service-reports.delete.own');
    $serviceReport = ownServiceReport($user, ['status' => 'new']);

    expect($user->can('delete', $serviceReport))->toBeTrue();
});

test('delete is allowed for other new report with delete.other permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'service-reports.delete.other');
    $serviceReport = otherServiceReport(['status' => 'new']);

    expect($user->can('delete', $serviceReport))->toBeTrue();
});

// email

test('email is allowed for own report with email.own permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'service-reports.email.own');
    $serviceReport = ownServiceReport($user);

    expect($user->can('email', $serviceReport))->toBeTrue();
});

test('email is allowed for other report with email.other permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'service-reports.email.other');
    $serviceReport = otherServiceReport();

    expect($user->can('email', $serviceReport))->toBeTrue();
});

test('email is denied without matching permission', function () {
    $user = User::factory()->create();
    $serviceReport = otherServiceReport();

    expect($user->can('email', $serviceReport))->toBeFalse();
});

// createPdf

test('createPdf is allowed for own report with createpdf.own permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'service-reports.createpdf.own');
    $serviceReport = ownServiceReport($user);

    expect($user->can('createPdf', $serviceReport))->toBeTrue();
});

test('createPdf is allowed for other report with createpdf.other permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'service-reports.createpdf.other');
    $serviceReport = otherServiceReport();

    expect($user->can('createPdf', $serviceReport))->toBeTrue();
});

test('createPdf is denied without matching permission', function () {
    $user = User::factory()->create();
    $serviceReport = otherServiceReport();

    expect($user->can('createPdf', $serviceReport))->toBeFalse();
});

// downloadList

test('downloadList is allowed with view.own or view.other permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'service-reports.view.own');

    expect($user->can('downloadList', ServiceReport::class))->toBeTrue();
});

test('downloadList is denied without view permissions', function () {
    $user = User::factory()->create();

    expect($user->can('downloadList', ServiceReport::class))->toBeFalse();
});

// emailSignatureRequest

test('emailSignatureRequest is denied when report is not new', function () {
    $user = User::factory()->create();
    grantPermission($user, 'service-reports.send-signature-request.own');
    $serviceReport = ownServiceReport($user, ['status' => 'signed']);

    expect($user->can('emailSignatureRequest', $serviceReport))->toBeFalse();
});

test('emailSignatureRequest is allowed for own new report with send-signature-request.own permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'service-reports.send-signature-request.own');
    $serviceReport = ownServiceReport($user, ['status' => 'new']);

    expect($user->can('emailSignatureRequest', $serviceReport))->toBeTrue();
});

test('emailSignatureRequest is allowed for other new report with send-signature-request.other permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'service-reports.send-signature-request.other');
    $serviceReport = otherServiceReport(['status' => 'new']);

    expect($user->can('emailSignatureRequest', $serviceReport))->toBeTrue();
});

// emailDownloadRequest

test('emailDownloadRequest is denied when report is new', function () {
    $user = User::factory()->create();
    grantPermission($user, 'service-reports.send-download-request.own');
    $serviceReport = ownServiceReport($user, ['status' => 'new']);

    expect($user->can('emailDownloadRequest', $serviceReport))->toBeFalse();
});

test('emailDownloadRequest is allowed for own signed report with send-download-request.own permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'service-reports.send-download-request.own');
    $serviceReport = ownServiceReport($user, ['status' => 'signed']);

    expect($user->can('emailDownloadRequest', $serviceReport))->toBeTrue();
});

test('emailDownloadRequest is allowed for other finished report with send-download-request.other permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'service-reports.send-download-request.other');
    $serviceReport = otherServiceReport(['status' => 'finished']);

    expect($user->can('emailDownloadRequest', $serviceReport))->toBeTrue();
});

// sign

test('sign is denied when report is not new', function () {
    $user = User::factory()->create();
    grantPermission($user, 'service-reports.get-signature.own');
    $serviceReport = ownServiceReport($user, ['status' => 'signed']);

    expect($user->can('sign', $serviceReport))->toBeFalse();
});

test('sign is allowed for own new report with get-signature.own permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'service-reports.get-signature.own');
    $serviceReport = ownServiceReport($user, ['status' => 'new']);

    expect($user->can('sign', $serviceReport))->toBeTrue();
});

test('sign is allowed for other new report with get-signature.other permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'service-reports.get-signature.other');
    $serviceReport = otherServiceReport(['status' => 'new']);

    expect($user->can('sign', $serviceReport))->toBeTrue();
});

// approve

test('approve is allowed with approve permission regardless of ownership', function () {
    $user = User::factory()->create();
    grantPermission($user, 'service-reports.approve');
    $serviceReport = otherServiceReport();

    expect($user->can('approve', $serviceReport))->toBeTrue();
});

test('approve is denied without approve permission', function () {
    $user = User::factory()->create();
    $serviceReport = ownServiceReport($user);

    expect($user->can('approve', $serviceReport))->toBeFalse();
});
