<?php

namespace Tests\Browser;

use App\Models\Accounting;
use App\Models\Logbook;
use App\Models\MaterialService;
use App\Models\User;

// Regression (reported 2026-11): after the October DST change, editing any
// field of an accounting or logbook row dated before the change saved it a
// day early — a Monday turned into a Sunday. The dates came in as UTC
// timestamps of local midnight and were shifted back with the browser's
// *current* UTC offset, which is an hour short for summer-time rows during
// winter time. The bug needs a European browser timezone and a "now" in
// winter time, so the page clock is pinned to November before filtering
// (rows are only (re)built from the fetched JSON at that point).
dataset('browser timezones', [
    'Vienna' => 'Europe/Vienna',
    // behind UTC, where a UTC-midnight date displays as the previous day
    'New York' => 'America/New_York',
    // southern hemisphere: July is winter time there, November summer time
    'Auckland' => 'Pacific/Auckland',
]);

function pinBrowserClockToWinterTime($page): void
{
    $page->script(<<<'JS'
        () => {
            const RealDate = Date;
            const now = new RealDate('2026-11-12T12:00:00+01:00').getTime();
            window.Date = class extends RealDate {
                constructor(...args) { args.length ? super(...args) : super(now); }
                static now() { return now; }
            };
        }
        JS);
}

test('editing a summer-time accounting row in winter time keeps its date', function (string $timezone) {
    $user = User::factory()->create();
    grantPermission($user, 'accounting.view.own');
    grantPermission($user, 'accounting.update.own');

    $accounting = Accounting::factory()->create([
        'employee_id' => $user->employee_id,
        'service_id' => MaterialService::factory()->create()->id,
        'service_provided_on' => '2026-07-13',
        'service_provided_started_at' => null,
        'service_provided_ended_at' => null,
        'amount' => 7.5,
    ]);

    $this->actingAs($user);

    $page = visit(route('accounting.index'))->on()->desktop()->withTimezone($timezone);
    pinBrowserClockToWinterTime($page);

    $page->fill('#filter_start', '2026-07-01')
        ->fill('#filter_end', '2026-07-31')
        ->click('.q-filterbar__submit')
        ->assertSee('13.07.2026')
        ->click('.q-dtable__cell:has-text("13.07.2026")')
        ->assertValue('#table_service_provided_on', '2026-07-13')
        ->keys('#table_service_provided_on', 'Enter')
        ->click('.q-dtable__num:has-text("7.5")')
        ->fill('#table_amount', '8')
        ->keys('#table_amount', 'Enter')
        ->click('.q-savebar .btn-primary')
        ->wait(1)
        ->assertNoJavaScriptErrors();

    $accounting->refresh();
    expect($accounting->amount)->toEqual(8)
        ->and($accounting->service_provided_on->format('Y-m-d'))->toBe('2026-07-13');
})->with('browser timezones');

test('editing a summer-time logbook row in winter time keeps its date', function (string $timezone) {
    $user = User::factory()->create();
    grantPermission($user, 'logbook.view.own');
    grantPermission($user, 'logbook.update.own');

    $logbook = Logbook::factory()->create([
        'employee_id' => $user->employee_id,
        'driven_on' => '2026-07-13',
        'start_kilometres' => 1000,
        'end_kilometres' => 1120,
        'driven_kilometres' => 120,
        'litres_refuelled' => 37,
    ]);

    $this->actingAs($user);

    $page = visit(route('logbook.index'))->on()->desktop()->withTimezone($timezone);
    pinBrowserClockToWinterTime($page);

    $page->fill('#filter_start', '2026-07-01')
        ->fill('#filter_end', '2026-07-31')
        ->click('.q-filterbar__submit')
        ->assertSee('13.07.2026')
        ->click('.q-dtable__cell:has-text("13.07.2026")')
        ->assertValue('#table_driven_on', '2026-07-13')
        ->keys('#table_driven_on', 'Enter')
        ->click('.q-dtable__num:has-text("37")')
        ->fill('#table_litres_refuelled', '40')
        ->keys('#table_litres_refuelled', 'Enter')
        ->click('.q-savebar .btn-primary')
        ->wait(1)
        ->assertNoJavaScriptErrors();

    $logbook->refresh();
    expect($logbook->litres_refuelled)->toBe(40)
        ->and($logbook->driven_on->format('Y-m-d'))->toBe('2026-07-13');
})->with('browser timezones');

// The service report form mounts its services straight from the page, so the
// clock can't be pinned before it builds them; this case only reproduces the
// old bug while the real "now" is in winter time. It guards the hidden inputs
// the form posts back, which used to shift every summer-time service date a
// day early on a plain re-save.
test('a service report form posts its summer-time service dates unchanged', function () {
    $user = User::factory()->create();
    grantPermission($user, 'service-reports.update.own');

    $report = \App\Models\ServiceReport::factory()->create(['employee_id' => $user->employee_id]);
    $report->services()->create(['provided_on' => '2026-07-13', 'hours' => 2, 'kilometres' => 0]);

    $this->actingAs($user);

    $page = visit(route('service-reports.edit', $report))->on()->desktop()->withTimezone('Europe/Vienna');

    $page->assertSee('13.07.2026')
        ->assertScript("document.querySelector('input[name=\"services[0][provided_on]\"]').value", '2026-07-13')
        ->assertNoJavaScriptErrors();
});
