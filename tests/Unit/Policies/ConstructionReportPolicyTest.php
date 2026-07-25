<?php

namespace Tests\Unit\Policies;

use App\Models\ConstructionReport;
use App\Models\User;

function ownConstructionReport(User $user, array $attributes = [])
{
    return ConstructionReport::factory()->create(array_merge(['employee_id' => $user->employee_id], $attributes));
}

function involvedConstructionReport(User $user, array $attributes = [])
{
    $report = ConstructionReport::factory()->create($attributes);
    $report->involvedEmployees()->attach($user->employee_id, ['employee_type' => 'involved']);

    return $report->fresh();
}

function otherConstructionReport(array $attributes = [])
{
    return ConstructionReport::factory()->create($attributes);
}

// viewAny

test('viewAny is allowed with view.own, view.involved, or view.other permission', function () {
    $own = User::factory()->create();
    grantPermission($own, 'construction-reports.view.own');
    $involved = User::factory()->create();
    grantPermission($involved, 'construction-reports.view.involved');
    $other = User::factory()->create();
    grantPermission($other, 'construction-reports.view.other');

    expect($own->can('viewAny', ConstructionReport::class))->toBeTrue();
    expect($involved->can('viewAny', ConstructionReport::class))->toBeTrue();
    expect($other->can('viewAny', ConstructionReport::class))->toBeTrue();
});

test('viewAny is denied without any view permission', function () {
    $user = User::factory()->create();

    expect($user->can('viewAny', ConstructionReport::class))->toBeFalse();
});

// view

test('view is allowed for own report with view.own permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'construction-reports.view.own');
    $report = ownConstructionReport($user);

    expect($user->can('view', $report))->toBeTrue();
});

test('view is denied for own report without view.own permission', function () {
    $user = User::factory()->create();
    $report = ownConstructionReport($user);

    expect($user->can('view', $report))->toBeFalse();
});

test('view is allowed for involved report with view.involved permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'construction-reports.view.involved');
    $report = involvedConstructionReport($user);

    expect($user->can('view', $report))->toBeTrue();
});

test('view is denied for involved report without view.involved permission', function () {
    $user = User::factory()->create();
    $report = involvedConstructionReport($user);

    expect($user->can('view', $report))->toBeFalse();
});

test('view is allowed for other report with view.other permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'construction-reports.view.other');
    $report = otherConstructionReport();

    expect($user->can('view', $report))->toBeTrue();
});

test('view is denied for other report without view.other permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'construction-reports.view.own');
    $report = otherConstructionReport();

    expect($user->can('view', $report))->toBeFalse();
});

// create

test('create is allowed with create permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'construction-reports.create');

    expect($user->can('create', ConstructionReport::class))->toBeTrue();
});

test('create is denied without create permission', function () {
    $user = User::factory()->create();

    expect($user->can('create', ConstructionReport::class))->toBeFalse();
});

// update

test('update is denied when report is finished, regardless of permissions', function () {
    $user = User::factory()->create();
    grantPermission($user, 'construction-reports.update.own');
    $report = ownConstructionReport($user, ['status' => 'finished']);

    expect($user->can('update', $report))->toBeFalse();
});

test('update is allowed for own non-finished report with update.own permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'construction-reports.update.own');
    $report = ownConstructionReport($user, ['status' => 'new']);

    expect($user->can('update', $report))->toBeTrue();
});

test('update is allowed for involved non-finished report with update.involved permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'construction-reports.update.involved');
    $report = involvedConstructionReport($user, ['status' => 'signed']);

    expect($user->can('update', $report))->toBeTrue();
});

test('update is allowed for other non-finished report with update.other permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'construction-reports.update.other');
    $report = otherConstructionReport(['status' => 'new']);

    expect($user->can('update', $report))->toBeTrue();
});

// delete

test('delete is denied when report is finished, regardless of permissions', function () {
    $user = User::factory()->create();
    grantPermission($user, 'construction-reports.approve');
    grantPermission($user, 'construction-reports.delete.own');
    $report = ownConstructionReport($user, ['status' => 'finished']);

    expect($user->can('delete', $report))->toBeFalse();
});

test('delete of a signed report requires approve permission, not delete.own', function () {
    $user = User::factory()->create();
    grantPermission($user, 'construction-reports.delete.own');
    $report = ownConstructionReport($user, ['status' => 'signed']);

    expect($user->can('delete', $report))->toBeFalse();
});

test('delete of a signed report is allowed with approve permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'construction-reports.approve');
    $report = ownConstructionReport($user, ['status' => 'signed']);

    expect($user->can('delete', $report))->toBeTrue();
});

test('delete is allowed for own new report with delete.own permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'construction-reports.delete.own');
    $report = ownConstructionReport($user, ['status' => 'new']);

    expect($user->can('delete', $report))->toBeTrue();
});

test('delete is allowed for involved new report with delete.involved permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'construction-reports.delete.involved');
    $report = involvedConstructionReport($user, ['status' => 'new']);

    expect($user->can('delete', $report))->toBeTrue();
});

test('delete is allowed for other new report with delete.other permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'construction-reports.delete.other');
    $report = otherConstructionReport(['status' => 'new']);

    expect($user->can('delete', $report))->toBeTrue();
});

// email

test('email is allowed for own report with email.own permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'construction-reports.email.own');
    $report = ownConstructionReport($user);

    expect($user->can('email', $report))->toBeTrue();
});

test('email is allowed for involved report with email.involved permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'construction-reports.email.involved');
    $report = involvedConstructionReport($user);

    expect($user->can('email', $report))->toBeTrue();
});

test('email is allowed for other report with email.other permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'construction-reports.email.other');
    $report = otherConstructionReport();

    expect($user->can('email', $report))->toBeTrue();
});

// createPdf

test('createPdf is allowed for own report with createpdf.own permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'construction-reports.createpdf.own');
    $report = ownConstructionReport($user);

    expect($user->can('createPdf', $report))->toBeTrue();
});

test('createPdf is allowed for involved report with createpdf.involved permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'construction-reports.createpdf.involved');
    $report = involvedConstructionReport($user);

    expect($user->can('createPdf', $report))->toBeTrue();
});

test('createPdf is allowed for other report with createpdf.other permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'construction-reports.createpdf.other');
    $report = otherConstructionReport();

    expect($user->can('createPdf', $report))->toBeTrue();
});

// emailSignatureRequest

test('emailSignatureRequest is denied when report is not new', function () {
    $user = User::factory()->create();
    grantPermission($user, 'construction-reports.send-signature-request.own');
    $report = ownConstructionReport($user, ['status' => 'signed']);

    expect($user->can('emailSignatureRequest', $report))->toBeFalse();
});

test('emailSignatureRequest is allowed for own new report with send-signature-request.own permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'construction-reports.send-signature-request.own');
    $report = ownConstructionReport($user, ['status' => 'new']);

    expect($user->can('emailSignatureRequest', $report))->toBeTrue();
});

test('emailSignatureRequest is allowed for involved new report with send-signature-request.involved permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'construction-reports.send-signature-request.involved');
    $report = involvedConstructionReport($user, ['status' => 'new']);

    expect($user->can('emailSignatureRequest', $report))->toBeTrue();
});

test('emailSignatureRequest is allowed for other new report with send-signature-request.other permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'construction-reports.send-signature-request.other');
    $report = otherConstructionReport(['status' => 'new']);

    expect($user->can('emailSignatureRequest', $report))->toBeTrue();
});

// emailDownloadRequest

test('emailDownloadRequest is denied when report is new', function () {
    $user = User::factory()->create();
    grantPermission($user, 'construction-reports.send-download-request.own');
    $report = ownConstructionReport($user, ['status' => 'new']);

    expect($user->can('emailDownloadRequest', $report))->toBeFalse();
});

test('emailDownloadRequest is allowed for own signed report with send-download-request.own permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'construction-reports.send-download-request.own');
    $report = ownConstructionReport($user, ['status' => 'signed']);

    expect($user->can('emailDownloadRequest', $report))->toBeTrue();
});

test('emailDownloadRequest is allowed for other finished report with send-download-request.other permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'construction-reports.send-download-request.other');
    $report = otherConstructionReport(['status' => 'finished']);

    expect($user->can('emailDownloadRequest', $report))->toBeTrue();
});

// sign

test('sign is denied when report is not new', function () {
    $user = User::factory()->create();
    grantPermission($user, 'construction-reports.get-signature.own');
    $report = ownConstructionReport($user, ['status' => 'signed']);

    expect($user->can('sign', $report))->toBeFalse();
});

test('sign is allowed for own new report with get-signature.own permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'construction-reports.get-signature.own');
    $report = ownConstructionReport($user, ['status' => 'new']);

    expect($user->can('sign', $report))->toBeTrue();
});

test('sign is allowed for involved new report with get-signature.involved permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'construction-reports.get-signature.involved');
    $report = involvedConstructionReport($user, ['status' => 'new']);

    expect($user->can('sign', $report))->toBeTrue();
});

test('sign is allowed for other new report with get-signature.other permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'construction-reports.get-signature.other');
    $report = otherConstructionReport(['status' => 'new']);

    expect($user->can('sign', $report))->toBeTrue();
});

// approve

test('approve is allowed with approve permission regardless of ownership', function () {
    $user = User::factory()->create();
    grantPermission($user, 'construction-reports.approve');
    $report = otherConstructionReport();

    expect($user->can('approve', $report))->toBeTrue();
});

test('approve is denied without approve permission', function () {
    $user = User::factory()->create();
    $report = ownConstructionReport($user);

    expect($user->can('approve', $report))->toBeFalse();
});
