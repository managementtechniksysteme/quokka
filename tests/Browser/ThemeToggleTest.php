<?php

namespace Tests\Browser;

use App\Models\User;

test('choosing a theme option updates data-bs-theme and survives a reload', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $page = visit(route('home'))->on()->desktop();

    $page->click('#navbarUserDropdown')
        ->click('.dropdown-menu .q-theme-opt--dark')
        ->assertScript("document.documentElement.getAttribute('data-bs-theme') === 'dark'", true)
        ->assertScript("document.documentElement.getAttribute('data-theme-pref') === 'dark'", true)
        ->navigate(route('home'))
        ->assertScript("document.documentElement.getAttribute('data-bs-theme') === 'dark'", true)
        ->click('#navbarUserDropdown')
        ->click('.dropdown-menu .q-theme-opt--light')
        ->assertScript("document.documentElement.getAttribute('data-bs-theme') === 'light'", true)
        ->navigate(route('home'))
        ->assertScript("document.documentElement.getAttribute('data-bs-theme') === 'light'", true)
        ->click('#navbarUserDropdown')
        ->click('.dropdown-menu .q-theme-opt--system')
        ->assertScript("document.documentElement.getAttribute('data-theme-pref') === 'system'", true)
        ->navigate(route('home'))
        ->assertScript("document.documentElement.getAttribute('data-theme-pref') === 'system'", true);
});
