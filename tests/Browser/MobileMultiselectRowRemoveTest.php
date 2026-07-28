<?php

namespace Tests\Browser;

use App\Models\User;

// Regression for 3754c3c: PeopleSelector's (and its siblings') mobile
// row-remove button hides its "Entfernen" text label (d-none d-md-inline),
// leaving an icon-only button that used to stretch into a narrow, off-centre
// sliver (min-height:44px from the shared mobile rule, width left
// content-sized at ~26px) instead of a real square button. Fixed size is a
// deliberate 32px (secondary inline row action, not a standalone 44px
// toolbar button).
test('the mobile row-remove button on a multiselect is a square touch target', function () {
    $user = User::factory()->create();
    grantPermission($user, 'tasks.create');

    $this->actingAs($user);

    $page = visit(route('tasks.create'))->on()->mobile();

    $page->click('label:has-text("Weitere beteiligte Mitarbeiter") + div .v-select')
        ->click('label:has-text("Weitere beteiligte Mitarbeiter") + div .vs__dropdown-option')
        ->assertVisible('.hover-highlight .btn-outline-danger')
        ->assertScript(<<<'JS'
            (function () {
                var rect = document.querySelector('.hover-highlight .btn-outline-danger').getBoundingClientRect();
                return Math.abs(rect.width - 32) <= 1 && Math.abs(rect.height - 32) <= 1;
            })()
            JS,
            true
        );
});
