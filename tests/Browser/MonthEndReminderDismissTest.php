<?php

namespace Tests\Browser;

use App\Models\ApplicationSettings;
use App\Models\User;
use App\Models\WageService;

test('dismissing the month end reminder hides it and keeps it hidden after a reload', function () {
    $this->travelTo('2026-10-29 09:00:00');

    ApplicationSettings::get()->update([
        'allowances_service_id' => WageService::factory()->create()->id,
        'overtime_50_service_id' => WageService::factory()->create()->id,
        'overtime_100_service_id' => WageService::factory()->create()->id,
    ]);
    ApplicationSettings::refreshCache();

    $user = User::factory()->create();
    grantPermission($user, 'accounting.view.own');

    $this->actingAs($user);

    $page = visit(route('home'))->on()->desktop();

    $page->assertVisible('#month-end-reminder')
        ->assertNoJavaScriptErrors()
        ->click('#month-end-reminder-dismiss')
        ->wait(0.5)
        ->assertNotPresent('#month-end-reminder')
        ->navigate(route('home'))
        ->assertNotPresent('#month-end-reminder');
});
