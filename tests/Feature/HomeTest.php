<?php

namespace Tests\Feature;

use App\Models\Accounting;
use App\Models\ApplicationSettings;
use App\Models\User;

test('index is shown for an authenticated user', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('home'));

    $response->assertSuccessful();
    $response->assertViewIs('home');
});

test('index shows the holiday page when the employee is currently on holiday', function () {
    $holidayService = \App\Models\WageService::factory()->create();
    ApplicationSettings::get()->update(['holiday_service_id' => $holidayService->id]);
    ApplicationSettings::refreshCache();

    $user = User::factory()->create();
    Accounting::factory()->create([
        'employee_id' => $user->employee_id,
        'service_id' => $holidayService->id,
        'service_provided_on' => now()->toDateString(),
    ]);

    $response = $this->actingAs($user)->get(route('home'));

    $response->assertSuccessful();
    $response->assertViewIs('holiday');
});

test('skip-holiday bypasses the holiday page', function () {
    $holidayService = \App\Models\WageService::factory()->create();
    ApplicationSettings::get()->update(['holiday_service_id' => $holidayService->id]);
    ApplicationSettings::refreshCache();

    $user = User::factory()->create();
    Accounting::factory()->create([
        'employee_id' => $user->employee_id,
        'service_id' => $holidayService->id,
        'service_provided_on' => now()->toDateString(),
    ]);

    $response = $this->actingAs($user)->get(route('home', ['skip-holiday' => 1]));

    $response->assertSuccessful();
    $response->assertViewIs('home');
});

test('delivery notes report row is hidden without viewAny permission', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('home'));

    $response->assertSuccessful();
    $response->assertViewHas('reportRows', function ($rows) {
        return collect($rows)->doesntContain(fn ($row) => $row['name'] === 'Lieferscheine');
    });
});

/**
 * The accounting tiles on the dashboard need the wage services from the application
 * settings, so a user who may view their own accounting needs them configured.
 */
function createUserWithAccountingDashboard(): User
{
    ApplicationSettings::get()->update([
        'allowances_service_id' => \App\Models\WageService::factory()->create()->id,
        'overtime_50_service_id' => \App\Models\WageService::factory()->create()->id,
        'overtime_100_service_id' => \App\Models\WageService::factory()->create()->id,
    ]);
    ApplicationSettings::refreshCache();

    $user = User::factory()->create();
    grantPermission($user, 'accounting.view.own');

    return $user;
}

test('month end reminder is shown inside the period for a user who may view their accounting', function () {
    $this->travelTo('2026-10-29 09:00:00');
    $user = createUserWithAccountingDashboard();

    $response = $this->actingAs($user)->get(route('home'));

    $response->assertSuccessful();
    $response->assertViewHas('showMonthEndReminder', true);
    $response->assertSee('Das Monatsende ist bald erreicht.');
    $response->assertSee(route('accounting.index'));
});

test('month end reminder is hidden outside the period', function () {
    $this->travelTo('2026-10-15 09:00:00');
    $user = createUserWithAccountingDashboard();

    $response = $this->actingAs($user)->get(route('home'));

    $response->assertViewHas('showMonthEndReminder', false);
    $response->assertDontSee('Das Monatsende ist bald erreicht.');
});

test('month end reminder is hidden without the permission to view own accounting', function () {
    $this->travelTo('2026-10-29 09:00:00');
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('home'));

    $response->assertViewHas('showMonthEndReminder', false);
});

test('dismissing the month end reminder hides it for the rest of the day', function () {
    $this->travelTo('2026-10-29 09:00:00');
    $user = createUserWithAccountingDashboard();

    $this->actingAs($user)->post(route('home.month-end-reminder.dismiss'))->assertNoContent();

    $this->get(route('home'))->assertViewHas('showMonthEndReminder', false);

    $this->travelTo('2026-10-29 23:30:00');
    $this->get(route('home'))->assertViewHas('showMonthEndReminder', false);
});

test('a dismissed month end reminder returns on the next day within the same session', function () {
    $this->travelTo('2026-10-29 09:00:00');
    $user = createUserWithAccountingDashboard();

    $this->actingAs($user)->post(route('home.month-end-reminder.dismiss'));

    $this->travelTo('2026-10-30 09:00:00');
    $this->get(route('home'))->assertViewHas('showMonthEndReminder', true);
});

test('dismissing the month end reminder requires authentication', function () {
    $this->post(route('home.month-end-reminder.dismiss'))->assertRedirect();
});
