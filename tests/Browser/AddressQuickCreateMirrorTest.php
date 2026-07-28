<?php

namespace Tests\Browser;

use App\Models\User;

function addressQuickCreateMirrorUser(): User
{
    $user = User::factory()->create();

    grantPermission($user, 'people.create');

    return $user;
}

test('filling the mobile sheet mirrors into the real fields and updates the summary chip', function () {
    $this->actingAs(addressQuickCreateMirrorUser());

    $page = visit(route('people.create'))->on()->mobile();

    $page->click('[data-bs-target="#newAddressSheet"]')
        ->assertVisible('#newAddressSheet')
        ->type('#address_name_mobile', 'Musterhof 12')
        ->click('[data-bs-dismiss="offcanvas"]')
        ->wait(0.5)
        ->assertMissing('#newAddressSheet')
        ->assertValue('#address_name', 'Musterhof 12')
        ->assertVisible('[data-quick-create-summary-for="newAddressSheet"]')
        ->assertSeeIn('[data-quick-create-summary-for="newAddressSheet"] .q-quick-create-summary__text', 'Musterhof 12')
        // Chained onto the same $page rather than split into a second statement --
        // resuming interactions on $page from a fresh statement hangs the Playwright
        // driver (pestphp/pest-plugin-browser v4.3.0 quirk, reproduced in isolation).
        ->click('[data-quick-create-summary-for="newAddressSheet"] .q-quick-create-summary__clear')
        ->assertValue('#address_name', '')
        ->assertMissing('[data-quick-create-summary-for="newAddressSheet"]');
});
