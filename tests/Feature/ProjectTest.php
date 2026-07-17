<?php

namespace Tests\Feature;

use App\Models\AdditionsReport;
use App\Models\ConstructionReport;
use App\Models\DeliveryNote;
use App\Models\FlowMeterInspectionReport;
use App\Models\InspectionReport;
use App\Models\InterimInvoice;
use App\Models\Memo;
use App\Models\Project;
use App\Models\ServiceReport;
use App\Models\Task;

function projectUser(array $permissions = []): \App\Models\User
{
    $user = \App\Models\User::factory()->create();

    foreach ($permissions as $permission) {
        grantPermission($user, $permission);
    }

    return $user;
}

function validProjectPayload(array $overrides = []): array
{
    return array_merge([
        'name' => 'A new project',
        'company_id' => \App\Models\Company::factory()->create()->id,
        'include_in_finances' => true,
    ], $overrides);
}

// index

test('index is shown for a user with view permission', function () {
    $user = projectUser(['projects.view']);

    $response = $this->actingAs($user)->get(route('projects.index'));

    $response->assertSuccessful();
    $response->assertViewIs('project.index');
});

test('index is forbidden without view permission', function () {
    $user = projectUser();

    $response = $this->actingAs($user)->get(route('projects.index'));

    $response->assertForbidden();
});

// create

test('create form is shown for a user with create permission', function () {
    $user = projectUser(['projects.create']);

    $response = $this->actingAs($user)->get(route('projects.create'));

    $response->assertSuccessful();
    $response->assertViewIs('project.create');
});

test('create is forbidden without create permission', function () {
    $user = projectUser();

    $response = $this->actingAs($user)->get(route('projects.create'));

    $response->assertForbidden();
});

test('create with a company param preloads that company', function () {
    $user = projectUser(['projects.create']);
    $company = \App\Models\Company::factory()->create();

    $response = $this->actingAs($user)->get(route('projects.create', ['company' => $company->id]));

    $response->assertSuccessful();
    $response->assertViewHas('currentCompany', function ($currentCompany) use ($company) {
        return $currentCompany->id === $company->id;
    });
});

// store

test('store creates a project', function () {
    $user = projectUser(['projects.create']);

    $response = $this->actingAs($user)->post(route('projects.store'), validProjectPayload(['name' => 'Brand new project']));

    $project = Project::where('name', 'Brand new project')->sole();
    $response->assertRedirect(route('projects.show', $project));
});

test('store rejects a duplicate name within the same company', function () {
    $user = projectUser(['projects.create']);
    $company = \App\Models\Company::factory()->create();
    Project::factory()->create(['name' => 'Existing Project', 'company_id' => $company->id]);

    $response = $this->actingAs($user)->post(route('projects.store'), validProjectPayload([
        'name' => 'Existing Project',
        'company_id' => $company->id,
    ]));

    $response->assertSessionHasErrors('name');
});

test('store requires billed_financial_costs when not included in finances', function () {
    $user = projectUser(['projects.create']);

    $response = $this->actingAs($user)->post(route('projects.store'), validProjectPayload([
        'include_in_finances' => false,
    ]));

    $response->assertSessionHasErrors('billed_financial_costs');
});

test('store setting ends_on clears is_pre_execution', function () {
    $user = projectUser(['projects.create']);

    $this->actingAs($user)->post(route('projects.store'), validProjectPayload([
        'name' => 'Finished on creation',
        'is_pre_execution' => true,
        'ends_on' => now()->toDateString(),
    ]));

    $project = Project::where('name', 'Finished on creation')->sole();
    expect($project->is_pre_execution)->toBeFalse();
});

// show

test('show overview tab is shown explicitly', function () {
    $user = projectUser(['projects.view']);
    $project = Project::factory()->create();

    $response = $this->actingAs($user)->get(route('projects.show', [$project, 'tab' => 'overview']));

    $response->assertSuccessful();
    $response->assertViewIs('project.show_tab_overview');
});

test('show without a tab redirects to the overview tab', function () {
    $user = projectUser(['projects.view']);
    $project = Project::factory()->create();

    $response = $this->actingAs($user)->get(route('projects.show', $project));

    $response->assertRedirect(route('projects.show', [$project, 'tab' => 'overview']));
});

test('show is forbidden without view permission', function () {
    $user = projectUser();
    $project = Project::factory()->create();

    $response = $this->actingAs($user)->get(route('projects.show', $project));

    $response->assertForbidden();
});

test('show sub-tab is shown with the tab-specific permission', function (string $tab, string $view, array $permissions) {
    $user = projectUser(array_merge(['projects.view'], $permissions));
    $project = Project::factory()->create();

    $response = $this->actingAs($user)->get(route('projects.show', [$project, 'tab' => $tab]));

    $response->assertSuccessful();
    $response->assertViewIs($view);
})->with([
    'interim_invoices' => ['interim_invoices', 'project.show_tab_interim_invoices', ['interim-invoices.view']],
    'delivery_notes' => ['delivery_notes', 'project.show_tab_delivery_notes', ['delivery-notes.view']],
    'tasks' => ['tasks', 'project.show_tab_tasks', ['tasks.view.responsible']],
    'memos' => ['memos', 'project.show_tab_memos', ['memos.view.sender']],
    'service_reports' => ['service_reports', 'project.show_tab_service_reports', ['service-reports.view.own']],
    'additions_reports' => ['additions_reports', 'project.show_tab_additions_reports', ['additions-reports.view.own']],
    'inspection_reports' => ['inspection_reports', 'project.show_tab_inspection_reports', ['inspection-reports.view.own']],
    'flow_meter_inspection_reports' => ['flow_meter_inspection_reports', 'project.show_tab_flow_meter_inspection_reports', ['flow-meter-inspection-reports.view.own']],
    'construction_reports' => ['construction_reports', 'project.show_tab_construction_reports', ['construction-reports.view.own']],
]);

test('show sub-tab redirects to overview without the tab-specific permission', function (string $tab) {
    $user = projectUser(['projects.view']);
    $project = Project::factory()->create();

    $response = $this->actingAs($user)->get(route('projects.show', [$project, 'tab' => $tab]));

    $response->assertRedirect(route('projects.show', [$project, 'tab' => 'overview']));
})->with([
    'interim_invoices', 'delivery_notes', 'tasks', 'memos', 'service_reports',
    'additions_reports', 'inspection_reports', 'flow_meter_inspection_reports', 'construction_reports',
]);

// edit

test('edit is shown for a user with update permission', function () {
    $user = projectUser(['projects.update']);
    $project = Project::factory()->create();

    $response = $this->actingAs($user)->get(route('projects.edit', $project));

    $response->assertSuccessful();
    $response->assertViewIs('project.edit');
});

test('edit is forbidden without update permission', function () {
    $user = projectUser();
    $project = Project::factory()->create();

    $response = $this->actingAs($user)->get(route('projects.edit', $project));

    $response->assertForbidden();
});

// update

test('update persists changes', function () {
    $user = projectUser(['projects.update']);
    $project = Project::factory()->create(['name' => 'Old name']);

    $response = $this->actingAs($user)->put(route('projects.update', $project), validProjectPayload([
        'name' => 'New name',
        'company_id' => $project->company_id,
    ]));

    $response->assertRedirect(route('projects.show', $project));
    expect($project->fresh()->name)->toBe('New name');
});

test('update is forbidden without update permission', function () {
    $user = projectUser();
    $project = Project::factory()->create();

    $response = $this->actingAs($user)->put(route('projects.update', $project), validProjectPayload());

    $response->assertForbidden();
});

// destroy

test('destroy removes the project and its interim invoices', function () {
    $user = projectUser(['projects.delete']);
    $project = Project::factory()->create();
    $invoice = InterimInvoice::factory()->create(['project_id' => $project->id]);

    $response = $this->actingAs($user)->delete(route('projects.destroy', $project));

    $response->assertRedirect(route('projects.index'));
    $this->assertModelMissing($project);
    $this->assertModelMissing($invoice);
});

test('destroy is forbidden without delete permission', function () {
    $user = projectUser();
    $project = Project::factory()->create();

    $response = $this->actingAs($user)->delete(route('projects.destroy', $project));

    $response->assertForbidden();
    $this->assertModelExists($project);
});

// showDownload

test('showDownload is shown for a user with createpdf permission', function () {
    $user = projectUser(['projects.createpdf']);
    $project = Project::factory()->create();

    $response = $this->actingAs($user)->get(route('projects.download', $project));

    $response->assertSuccessful();
    $response->assertViewIs('project.download');
});

test('showDownload is forbidden without createpdf permission', function () {
    $user = projectUser();
    $project = Project::factory()->create();

    $response = $this->actingAs($user)->get(route('projects.download', $project));

    $response->assertForbidden();
});

// download / downloadList (real pdflatex, no mocking)

test('download renders a real pdf for an authorized user', function () {
    $user = projectUser(['projects.createpdf']);
    $project = Project::factory()->create();

    $response = $this->actingAs($user)->post(route('projects.download', $project), []);

    $response->assertSuccessful();
    $response->assertHeader('content-type', 'application/pdf');
})->group('pdflatex');

test('downloadList renders a real pdf for an authorized user', function () {
    $user = projectUser(['projects.view']);
    Project::factory()->create();

    $response = $this->actingAs($user)->get(route('projects.download-list'));

    $response->assertSuccessful();
    $response->assertHeader('content-type', 'application/pdf');
})->group('pdflatex');

test('downloadList is forbidden without view permission', function () {
    $user = projectUser();

    $response = $this->actingAs($user)->get(route('projects.download-list'));

    $response->assertForbidden();
});
