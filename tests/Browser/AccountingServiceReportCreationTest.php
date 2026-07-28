<?php

namespace Tests\Browser;

use App\Models\Accounting;
use App\Models\ApplicationSettings;
use App\Models\Project;
use App\Models\User;
use App\Models\WageService;

test('selecting a single hour-based accounting row reveals a service-report creation button', function () {
    $user = User::factory()->create();
    grantPermission($user, 'accounting.view.own');
    grantPermission($user, 'service-reports.create');

    ApplicationSettings::get()->update(['services_hour_unit' => 'h']);
    ApplicationSettings::refreshCache();

    $project = Project::factory()->create();
    $hourService = WageService::factory()->create(['unit' => 'h']);
    $accounting = Accounting::factory()->create([
        'employee_id' => $user->employee_id,
        'project_id' => $project->id,
        'service_id' => $hourService->id,
        'service_provided_on' => now()->toDateString(),
    ]);

    $this->actingAs($user);

    $page = visit(route('accounting.index'))->on()->desktop();

    $page->assertMissing('.q-page-head button:has-text("Servicebericht erstellen")')
        ->click('.q-trow .q-dtable__actions button:last-of-type')
        ->assertVisible('.q-page-head button:has-text("Servicebericht erstellen")')
        ->assertScript(
            "(function () { window.__reportUrl = null; window.open = function (u) { window.__reportUrl = String(u); return { focus: function () {} }; }; return true; })()",
            true
        )
        ->click('.q-page-head button:has-text("Servicebericht erstellen")')
        ->assertNoJavascriptErrors()
        ->assertScript(
            "window.__reportUrl !== null && window.__reportUrl.includes('/service-reports/create') && window.__reportUrl.includes('accounting')",
            true
        );
});

test('selecting a non-hour-based accounting row does not show the service-report creation button', function () {
    $user = User::factory()->create();
    grantPermission($user, 'accounting.view.own');
    grantPermission($user, 'service-reports.create');

    ApplicationSettings::get()->update(['services_hour_unit' => 'h']);
    ApplicationSettings::refreshCache();

    $project = Project::factory()->create();
    $pieceService = WageService::factory()->create(['unit' => 'Stk.']);
    Accounting::factory()->create([
        'employee_id' => $user->employee_id,
        'project_id' => $project->id,
        'service_id' => $pieceService->id,
        'service_provided_on' => now()->toDateString(),
    ]);

    $this->actingAs($user);

    $page = visit(route('accounting.index'))->on()->desktop();

    $page->click('.q-trow .q-dtable__actions button:last-of-type')
        ->assertMissing('.q-page-head button:has-text("Servicebericht erstellen")');
});
