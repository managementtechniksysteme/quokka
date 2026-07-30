<?php

namespace Tests\Browser;

use App\Models\Accounting;
use App\Models\User;
use App\Models\WageService;

// Regression: the desktop table's project/service cells combine
// .q-dtable__truncate (overflow: hidden, for the ellipsized static text)
// with an inline v-select in edit mode. vue-select doesn't append its
// dropdown-menu to <body> by default, so it rendered as a child of that
// same overflow: hidden cell and was clipped almost entirely invisible —
// editing looked broken (nothing appeared to happen on click) even though
// the click handler and v-select were both working correctly. Mobile uses a
// separate offcanvas edit sheet with no truncate wrapper, so it was
// unaffected — which is what made this look "desktop-only" (2026-07-30,
// reported by a user). Fix: the cell only carries .q-dtable__truncate while
// showing the static text, not while its v-select is open.
test('a saved accounting row\'s service can be changed inline on desktop', function () {
    $user = User::factory()->create();
    grantPermission($user, 'accounting.view.own');
    grantPermission($user, 'accounting.update.own');

    $serviceA = WageService::factory()->create(['name' => 'Service A', 'unit' => 'h']);
    $serviceB = WageService::factory()->create(['name' => 'Service B', 'unit' => 'h']);

    Accounting::factory()->create([
        'employee_id' => $user->employee_id,
        'service_id' => $serviceA->id,
        'service_provided_on' => now()->toDateString(),
    ]);

    $this->actingAs($user);

    $page = visit(route('accounting.index'))->on()->desktop();

    $page->assertSee('Service A (h)')
        ->click('.q-dtable__cell:has-text("Service A")')
        ->assertVisible('.vs__dropdown-menu')
        // The functional click-through below still succeeds even with the
        // bug present — Playwright's actionability checks don't account for
        // an ancestor clipping the element via overflow: hidden. Assert the
        // actual root cause directly: the open dropdown's containing cell
        // must not be clipping it.
        ->assertScript(
            "getComputedStyle(document.querySelector('.vs__dropdown-menu').closest('.q-dtable__cell')).overflow !== 'hidden'",
            true
        )
        ->click('.vs__dropdown-option:has-text("Service B")')
        ->assertSee('Service B (h)');
});
