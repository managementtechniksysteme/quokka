<?php

namespace Tests\Feature;

use App\Models\InterimInvoice;
use App\Models\Project;
use App\Models\User;

function interimInvoiceUser(array $permissions = []): User
{
    $user = User::factory()->create();

    foreach ($permissions as $permission) {
        grantPermission($user, $permission);
    }

    return $user;
}

// create / store

test('create form is shown for a user who can view the project and has create permission', function () {
    $user = interimInvoiceUser(['projects.view', 'interim-invoices.create']);
    $project = Project::factory()->create();

    $response = $this->actingAs($user)->get(route('interim-invoices.create', $project));

    $response->assertSuccessful();
    $response->assertViewIs('interim_invoice.create');
});

test('create form is forbidden without interim-invoices.create permission', function () {
    $user = interimInvoiceUser(['projects.view']);
    $project = Project::factory()->create();

    $response = $this->actingAs($user)->get(route('interim-invoices.create', $project));

    $response->assertForbidden();
});

test('create form is forbidden without view on the project', function () {
    $user = interimInvoiceUser(['interim-invoices.create']);
    $project = Project::factory()->create();

    $response = $this->actingAs($user)->get(route('interim-invoices.create', $project));

    $response->assertForbidden();
});

test('store creates an interim invoice under the project', function () {
    $user = interimInvoiceUser(['projects.view', 'interim-invoices.create']);
    $project = Project::factory()->create();

    $response = $this->actingAs($user)->post(route('interim-invoices.store', $project), [
        'title' => 'Partial billing 1',
        'billed_on' => '2026-01-01',
        'amount' => 5000,
    ]);

    $interimInvoice = InterimInvoice::sole();

    $response->assertRedirect(route('projects.show', [$project, 'tab' => 'interim_invoices']));
    expect($interimInvoice->project_id)->toBe($project->id);
});

test('store is forbidden without interim-invoices.create permission', function () {
    $user = interimInvoiceUser(['projects.view']);
    $project = Project::factory()->create();

    $response = $this->actingAs($user)->post(route('interim-invoices.store', $project), [
        'title' => 'Partial billing 1',
        'billed_on' => '2026-01-01',
        'amount' => 5000,
    ]);

    $response->assertForbidden();
    expect(InterimInvoice::count())->toBe(0);
});

// show

test('show is allowed with view on the project and interim-invoices.view permission', function () {
    $user = interimInvoiceUser(['projects.view', 'interim-invoices.view']);
    $project = Project::factory()->create();
    $interimInvoice = InterimInvoice::factory()->create(['project_id' => $project->id]);

    $response = $this->actingAs($user)->get(route('interim-invoices.show', ['project' => $project, 'interim_invoice' => $interimInvoice]));

    $response->assertSuccessful();
    $response->assertViewIs('interim_invoice.show');
});

test('show is forbidden without interim-invoices.view permission', function () {
    $user = interimInvoiceUser(['projects.view']);
    $project = Project::factory()->create();
    $interimInvoice = InterimInvoice::factory()->create(['project_id' => $project->id]);

    $response = $this->actingAs($user)->get(route('interim-invoices.show', ['project' => $project, 'interim_invoice' => $interimInvoice]));

    $response->assertForbidden();
});

// update

test('update persists changes', function () {
    $user = interimInvoiceUser(['projects.view', 'interim-invoices.update']);
    $project = Project::factory()->create();
    $interimInvoice = InterimInvoice::factory()->create(['project_id' => $project->id]);

    $response = $this->actingAs($user)->put(route('interim-invoices.update', ['project' => $project, 'interim_invoice' => $interimInvoice]), [
        'title' => 'Updated title',
        'billed_on' => '2026-02-01',
        'amount' => 100,
    ]);

    $response->assertRedirect(route('projects.show', [$project, 'tab' => 'interim_invoices']));
    expect($interimInvoice->fresh()->title)->toBe('Updated title');
});

test('update is forbidden without interim-invoices.update permission', function () {
    $user = interimInvoiceUser(['projects.view']);
    $project = Project::factory()->create();
    $interimInvoice = InterimInvoice::factory()->create(['project_id' => $project->id]);

    $response = $this->actingAs($user)->put(route('interim-invoices.update', ['project' => $project, 'interim_invoice' => $interimInvoice]), [
        'title' => 'Updated title',
        'billed_on' => '2026-02-01',
        'amount' => 100,
    ]);

    $response->assertForbidden();
});

// destroy

test('destroy removes an interim invoice', function () {
    $user = interimInvoiceUser(['projects.view', 'interim-invoices.delete']);
    $project = Project::factory()->create();
    $interimInvoice = InterimInvoice::factory()->create(['project_id' => $project->id]);

    $response = $this->actingAs($user)->delete(route('interim-invoices.destroy', ['project' => $project, 'interim_invoice' => $interimInvoice]));

    $response->assertRedirect(route('projects.show', [$project, 'tab' => 'interim_invoices']));
    expect(InterimInvoice::find($interimInvoice->id))->toBeNull();
});

test('destroy is forbidden without interim-invoices.delete permission', function () {
    $user = interimInvoiceUser(['projects.view']);
    $project = Project::factory()->create();
    $interimInvoice = InterimInvoice::factory()->create(['project_id' => $project->id]);

    $response = $this->actingAs($user)->delete(route('interim-invoices.destroy', ['project' => $project, 'interim_invoice' => $interimInvoice]));

    $response->assertForbidden();
    expect(InterimInvoice::find($interimInvoice->id))->not->toBeNull();
});
