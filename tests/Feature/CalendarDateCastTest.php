<?php

use App\Models\Accounting;

// App\Casts\CalendarDate must read and write exactly like the built-in "date"
// cast it replaced; only the JSON form changes (to a plain "Y-m-d").

test('it serializes to a plain calendar date regardless of daylight saving time', function (string $date) {
    $accounting = Accounting::factory()->create(['service_provided_on' => $date]);

    expect($accounting->fresh()->toArray()['service_provided_on'])->toBe($date);
})->with(['summer time' => '2026-07-13', 'winter time' => '2026-11-02', 'dst change day' => '2026-10-25']);

test('it reads as a carbon instance at local midnight', function () {
    $accounting = Accounting::factory()->create(['service_provided_on' => '2026-07-13']);

    $date = $accounting->fresh()->service_provided_on;

    expect($date)->toBeInstanceOf(\Illuminate\Support\Carbon::class)
        ->and($date->format('Y-m-d H:i:s'))->toBe('2026-07-13 00:00:00')
        ->and($date->getTimezone()->getName())->toBe(config('app.timezone'));
});

test('it normalizes any parsable input like the built-in date cast', function (mixed $input) {
    $accounting = Accounting::factory()->create(['service_provided_on' => $input]);

    expect($accounting->getAttributes()['service_provided_on'])->toBe('2026-07-13 00:00:00')
        ->and($accounting->fresh()->service_provided_on->format('Y-m-d'))->toBe('2026-07-13');
})->with([
    'plain date' => '2026-07-13',
    'written out' => '13 July 2026',
    'carbon' => fn () => \Illuminate\Support\Carbon::parse('2026-07-13'),
]);

test('re-saving the same date does not make the model dirty', function () {
    $accounting = Accounting::factory()->create(['service_provided_on' => '2026-07-13'])->fresh();

    $accounting->service_provided_on = '2026-07-13';
    expect($accounting->isDirty('service_provided_on'))->toBeFalse();

    $accounting->service_provided_on = '2026-07-14';
    expect($accounting->isDirty('service_provided_on'))->toBeTrue();
});

test('mutating the returned carbon instance does not change the stored date', function () {
    $accounting = Accounting::factory()->create(['service_provided_on' => '2026-07-13'])->fresh();

    $accounting->service_provided_on->addDay();
    $accounting->save();

    expect($accounting->fresh()->service_provided_on->format('Y-m-d'))->toBe('2026-07-13');
});

test('a null date stays null', function () {
    $accounting = new Accounting(['service_provided_on' => null]);

    expect($accounting->service_provided_on)->toBeNull()
        ->and($accounting->toArray()['service_provided_on'])->toBeNull();
});
