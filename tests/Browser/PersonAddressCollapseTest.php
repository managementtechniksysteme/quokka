<?php

namespace Tests\Browser;

use App\Models\User;

function personAddressCollapseUser(): User
{
    $user = User::factory()->create();

    grantPermission($user, 'people.create');

    return $user;
}

// responsive element swap

test('desktop shows the collapse trigger and hides the offcanvas trigger', function () {
    $this->actingAs(personAddressCollapseUser());

    $page = visit(route('people.create'))->on()->desktop();

    $page->assertVisible('[data-bs-target="#newAddressFields"]')
        ->assertMissing('[data-bs-target="#newAddressSheet"]');
});

test('mobile shows the offcanvas trigger and hides the collapse trigger', function () {
    $this->actingAs(personAddressCollapseUser());

    $page = visit(route('people.create'))->on()->mobile();

    $page->assertVisible('[data-bs-target="#newAddressSheet"]')
        ->assertMissing('[data-bs-target="#newAddressFields"]');
});

// toggle behaviour

test('desktop collapse starts closed and toggles open and closed on click', function () {
    $this->actingAs(personAddressCollapseUser());

    $page = visit(route('people.create'))->on()->desktop();

    $page->assertMissing('#newAddressFields')
        ->click('[data-bs-target="#newAddressFields"]')
        ->assertVisible('#newAddressFields')
        ->wait(0.5)
        ->click('[data-bs-target="#newAddressFields"]')
        ->wait(0.5)
        ->assertMissing('#newAddressFields');
});

test('mobile offcanvas opens on click while the desktop collapse stays hidden', function () {
    $this->actingAs(personAddressCollapseUser());

    $page = visit(route('people.create'))->on()->mobile();

    $page->assertMissing('#newAddressSheet')
        ->click('[data-bs-target="#newAddressSheet"]')
        ->assertVisible('#newAddressSheet')
        ->assertMissing('#newAddressFields');
});
