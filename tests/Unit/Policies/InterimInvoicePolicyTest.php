<?php

namespace Tests\Unit\Policies;

use App\Models\InterimInvoice;
use App\Models\Project;
use App\Models\User;

// viewAny/create are exercised only via feature tests (InterimInvoiceTest), not here:
// InterimInvoicePolicy::viewAny()/create() read Illuminate\Support\Facades\Request::route()->project
// directly instead of taking a $project argument, which is null outside of a real HTTP
// request/route dispatch and throws a fatal error when called via $user->can(...) in a unit test.

function interimInvoiceForViewableProject(User $user, array $attributes = []): InterimInvoice
{
    $project = Project::factory()->create();

    return InterimInvoice::factory()->create(array_merge(['project_id' => $project->id], $attributes));
}

test('view is allowed with view on the project and interim-invoices.view permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'projects.view');
    grantPermission($user, 'interim-invoices.view');

    expect($user->can('view', interimInvoiceForViewableProject($user)))->toBeTrue();
});

test('view is denied without view on the project', function () {
    $user = User::factory()->create();
    grantPermission($user, 'interim-invoices.view');

    expect($user->can('view', interimInvoiceForViewableProject($user)))->toBeFalse();
});

test('view is denied without interim-invoices.view permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'projects.view');

    expect($user->can('view', interimInvoiceForViewableProject($user)))->toBeFalse();
});

test('update is allowed with view on the project and interim-invoices.update permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'projects.view');
    grantPermission($user, 'interim-invoices.update');

    expect($user->can('update', interimInvoiceForViewableProject($user)))->toBeTrue();
});

test('update is denied without interim-invoices.update permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'projects.view');

    expect($user->can('update', interimInvoiceForViewableProject($user)))->toBeFalse();
});

test('delete is allowed with view on the project and interim-invoices.delete permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'projects.view');
    grantPermission($user, 'interim-invoices.delete');

    expect($user->can('delete', interimInvoiceForViewableProject($user)))->toBeTrue();
});

test('delete is denied without interim-invoices.delete permission', function () {
    $user = User::factory()->create();
    grantPermission($user, 'projects.view');

    expect($user->can('delete', interimInvoiceForViewableProject($user)))->toBeFalse();
});
