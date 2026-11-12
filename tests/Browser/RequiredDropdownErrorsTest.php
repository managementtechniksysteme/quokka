<?php

namespace Tests\Browser;

use App\Models\Project;
use App\Models\User;
use App\Models\WageService;

// Regression: v-select based dropdowns submit nothing while empty, so a missing
// value only comes back as a server-side validation error — but the unit
// dropdown's feedback block lacked the "d-block" toggle every other dropdown
// has, so the "required" error stayed invisible (2026-11, reported by a user).
test('a wage service without a unit shows the required error', function () {
    $user = User::factory()->create();
    grantPermission($user, 'wage-services.create');

    $this->actingAs($user);

    $page = visit(route('wage-services.create'))->on()->desktop();

    $page->fill('#name', 'Pflegeurlaub')
        ->fill('#description', 'Pflegefreistellung')
        ->click('button[type="submit"]')
        ->assertVisible('.invalid-feedback:has-text("Einheit muss ausgefüllt sein.")')
        ->assertNoJavaScriptErrors();

    expect(WageService::count())->toBe(0);
});

// Regression: adding an accounting entry without picking a service read
// `this.service.type` before validating, threw, and showed no errors at all.
test('adding an accounting entry without a service shows the required errors', function () {
    $user = User::factory()->create();
    grantPermission($user, 'accounting.view.own');
    grantPermission($user, 'accounting.create');
    Project::factory()->create();

    $this->actingAs($user);

    $page = visit(route('accounting.index'))->on()->desktop();

    $page->click('#addaccounting')
        ->assertVisible('.invalid-feedback:has-text("Leistung muss ausgefüllt sein."):visible')
        ->assertVisible('.invalid-feedback:has-text("Projekt muss ausgefüllt sein."):visible')
        ->assertNoJavaScriptErrors();
});
