<?php

namespace Tests\Browser;

use App\Models\AdditionsReport;
use App\Models\User;

// On mobile the stat strip is a two-column grid below a full-width first cell.
// Only the right-hand column's cells may carry a divider (the one in the
// center). The desktop rule `.q-statbar__cell + .q-statbar__cell` once
// out-specified the mobile reset, so left-column cells kept a divider too.
test('stat strip shows its divider only in the center on mobile', function () {
    $user = User::factory()->create();
    grantPermission($user, 'additions-reports.view.own');
    $report = AdditionsReport::factory()->withInvolvedEmployees()->create(['employee_id' => $user->employee_id]);

    $this->actingAs($user);

    $page = visit(route('additions-reports.show', $report))->on()->mobile();

    $dividerWidths = $page->script(<<<'JS'
        Array.from(document.querySelectorAll('.q-statbar > .q-statbar__cell')).map(function (cell) {
            return {
                left: cell.offsetLeft,
                divider: parseFloat(getComputedStyle(cell).borderLeftWidth),
            };
        })
    JS);

    $cells = collect($dividerWidths);
    $rightColumnStart = $cells->skip(1)->max('left');

    expect($cells)->toHaveCount(5);
    // first cell spans the full width: no divider
    expect($cells->first()['divider'])->toEqual(0);
    // left-column cells: no divider; right-column cells: the center divider
    $cells->skip(1)->each(function (array $cell) use ($rightColumnStart) {
        expect($cell['divider'])->toEqual($cell['left'] === $rightColumnStart ? 1 : 0);
    });
});
