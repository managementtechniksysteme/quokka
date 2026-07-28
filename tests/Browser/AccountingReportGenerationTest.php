<?php

namespace Tests\Browser;

use App\Models\Accounting;
use App\Models\Logbook;
use App\Models\User;
use App\Models\Vehicle;

// Regression for 812f46a: a self-referencing `const params = new
// URLSearchParams(params)` inside AccountingSelector.vue's createPdf() threw
// a ReferenceError as the very first line, before the request ever left the
// browser -- zero server-side symptom, so completely invisible to Feature/Unit
// tests. window.open is stubbed rather than actually followed into a new tab
// (pest-plugin-browser has no popup/new-tab inspection API), which is enough
// to prove createPdf() runs to completion and builds the right URL.
test('accounting report generation opens a download URL without a JavaScript error', function () {
    $user = User::factory()->create();
    grantPermission($user, 'accounting.view.own');
    grantPermission($user, 'accounting.createpdf');
    Accounting::factory()->create(['employee_id' => $user->employee_id, 'service_provided_on' => now()->toDateString()]);

    $this->actingAs($user);

    $page = visit(route('accounting.index'))->on()->desktop();

    $page->assertScript(
        "(function () { window.__reportUrl = null; window.open = function (u) { window.__reportUrl = String(u); return { focus: function () {} }; }; return true; })()",
        true
    )
        ->click('.q-page-head button:has-text("Auswertung")')
        ->assertNoJavascriptErrors()
        ->assertScript("window.__reportUrl !== null && window.__reportUrl.includes('/accounting/download')", true);
});

test('logbook report generation opens a download URL without a JavaScript error', function () {
    $user = User::factory()->create();
    grantPermission($user, 'logbook.view.own');
    grantPermission($user, 'logbook.createpdf');
    Logbook::factory()->create(['employee_id' => $user->employee_id, 'vehicle_id' => Vehicle::factory(), 'driven_on' => now()->toDateString()]);

    $this->actingAs($user);

    $page = visit(route('logbook.index'))->on()->desktop();

    $page->assertScript(
        "(function () { window.__reportUrl = null; window.open = function (u) { window.__reportUrl = String(u); return { focus: function () {} }; }; return true; })()",
        true
    )
        ->click('.q-page-head button:has-text("Auswertung")')
        ->assertNoJavascriptErrors()
        ->assertScript("window.__reportUrl !== null && window.__reportUrl.includes('/logbook/download')", true);
});
