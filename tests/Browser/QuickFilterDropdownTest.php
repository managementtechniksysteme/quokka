<?php

namespace Tests\Browser;

use App\Models\User;

// The desktop quick-filter toggle is the same funnel icon as on mobile (it used
// to be a bare caret at the end of the input group that was easy to overlook).
test('desktop quick filter funnel keeps the input group rounded and applies a filter', function () {
    $user = User::factory()->create();
    grantPermission($user, 'tasks.view.responsible');

    $this->actingAs($user);

    $toggle = 'button[data-bs-toggle="dropdown"][aria-label="Schnellfilter"]';

    $page = visit(route('tasks.index'))->on()->desktop();

    $page->assertVisible($toggle . ' svg');

    // Bootstrap squares off every input-group child but the last unless it is a
    // .dropdown-toggle; the funnel is the visible end of the group.
    $radius = $page->script("parseFloat(getComputedStyle(document.querySelector('{$toggle}')).borderTopRightRadius)");
    expect($radius)->toBeGreaterThan(0);

    // ...and keeping that class must not bring Bootstrap's caret back next to the icon.
    $caret = $page->script("getComputedStyle(document.querySelector('{$toggle}'), '::after').display");
    expect($caret)->toBe('none');

    $page->click($toggle)
        ->assertVisible('.dropdown-menu.show')
        ->click('.dropdown-menu.show .dropdown-item:has-text("Meine überfälligen Aufgaben")')
        ->wait(0.8)
        ->assertQueryStringHas('search', 'v:' . $user->username . ' ist:überfällig')
        ->assertValue('input.q-filter-input:not(.ps-5)', 'v:' . $user->username . ' ist:überfällig')
        ->assertNoJavaScriptErrors();
});
