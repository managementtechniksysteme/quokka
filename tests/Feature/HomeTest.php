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
