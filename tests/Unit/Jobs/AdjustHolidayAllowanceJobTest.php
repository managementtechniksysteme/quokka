<?php

namespace Tests\Unit\Jobs;

use App\Events\HolidayAllowanceAdjustedEvent;
use App\Jobs\AdjustHolidayAllowanceJob;
use App\Models\ApplicationSettings;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Support\Facades\Event;

test('increases holidays for employees whose work anniversary is today', function () {
    Event::fake();
    ApplicationSettings::get()->update(['holiday_yearly_allowance' => 25]);
    ApplicationSettings::refreshCache();

    $anniversary = Employee::factory()->create([
        'entered_on' => Carbon::now()->subYears(2),
        'holidays' => 10,
    ]);
    $notToday = Employee::factory()->create([
        'entered_on' => Carbon::now()->subYears(2)->subDay(),
        'holidays' => 10,
    ]);

    (new AdjustHolidayAllowanceJob())->handle();

    expect($anniversary->fresh()->holidays)->toBe(35.0);
    expect($notToday->fresh()->holidays)->toBe(10.0);
    Event::assertDispatched(HolidayAllowanceAdjustedEvent::class, function ($event) use ($anniversary) {
        return $event->employee->person_id === $anniversary->person_id
            && $event->oldHolidayAllowance === 10.0
            && $event->currentHolidayAllowance === 35.0
            && $event->manualAdjustment === false;
    });
});

test('does nothing when no yearly holiday allowance is configured', function () {
    Event::fake();
    ApplicationSettings::get()->update(['holiday_yearly_allowance' => null]);
    ApplicationSettings::refreshCache();

    $employee = Employee::factory()->create([
        'entered_on' => Carbon::now()->subYears(2),
        'holidays' => 10,
    ]);

    (new AdjustHolidayAllowanceJob())->handle();

    expect($employee->fresh()->holidays)->toBe(10.0);
    Event::assertNotDispatched(HolidayAllowanceAdjustedEvent::class);
});
