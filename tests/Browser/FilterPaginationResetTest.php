<?php

namespace Tests\Browser;

use App\Models\Accounting;
use App\Models\User;

// Regression for b635872: JwPagination's setPage() (driven by clicking
// through pages) only ever updated its own internal currentPage, never
// writing back to the parent's initialPage -- so filterData()'s
// `this.initialPage = 1` reset became a silent no-op once the user had
// already paged past 1, since Vue's watcher never saw an actual value change.
test('changing a filter while on page 2+ resets pagination back to page 1', function () {
    $user = User::factory()->create();
    grantPermission($user, 'accounting.view.own');

    Accounting::factory()->count(16)->create([
        'employee_id' => $user->employee_id,
        'service_provided_on' => now()->toDateString(),
    ]);

    $this->actingAs($user);

    $page = visit(route('accounting.index'))->on()->desktop();

    $page->assertVisible('.pagination.d-none.d-lg-flex')
        ->click('.pagination.d-none.d-lg-flex .page-link:has-text("2")')
        ->assertSeeIn('.pagination.d-none.d-lg-flex .page-item.active .page-link', '2')
        ->fill('#filter_start', now()->subDay()->toDateString())
        ->click('.q-filterbar__submit')
        ->wait(0.5)
        ->assertSeeIn('.pagination.d-none.d-lg-flex .page-item.active .page-link', '1');
});
