<?php

namespace Tests\Browser;

use App\Models\Logbook;
use App\Models\User;

// Same root cause as AccountingInlineEditTest: the desktop table's
// origin/destination cell combines .q-dtable__truncate (overflow: hidden)
// with an inline v-select, whose dropdown-menu isn't appended to <body> by
// default — so it rendered clipped almost entirely invisible inside that
// cell. Fix: the cell only carries .q-dtable__truncate while showing the
// static text, not while either sub-field's v-select is open (2026-07-30).
test('a saved logbook row\'s origin can be changed inline on desktop', function () {
    $user = User::factory()->create();
    grantPermission($user, 'logbook.view.own');
    grantPermission($user, 'logbook.update.own');

    Logbook::factory()->create([
        'employee_id' => $user->employee_id,
        'origin' => 'Ausgangsort',
        'destination' => 'Zielort',
        'driven_on' => now()->toDateString(),
    ]);
    // A second, unrelated row just to give the origin/destination
    // autocomplete ("places") another option besides the one being edited.
    Logbook::factory()->create([
        'origin' => 'Anderer Ort',
        'destination' => 'Sonstiges Ziel',
        'driven_on' => now()->toDateString(),
    ]);

    $this->actingAs($user);

    $page = visit(route('logbook.index'))->on()->desktop();

    $page->assertSee('Ausgangsort')
        ->click('span.q-dtable__truncate:has-text("Ausgangsort")')
        ->assertVisible('.vs__dropdown-menu')
        // See AccountingInlineEditTest — Playwright's click-through still
        // succeeds even when the ancestor cell clips the dropdown, so
        // assert the actual root cause directly.
        ->assertScript(
            "getComputedStyle(document.querySelector('.vs__dropdown-menu').closest('.q-dtable__cell')).overflow !== 'hidden'",
            true
        )
        ->click('.vs__dropdown-option:has-text("Anderer Ort")')
        ->assertSee('Anderer Ort');
});
