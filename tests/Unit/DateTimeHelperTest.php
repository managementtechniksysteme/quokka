<?php

namespace Tests\Unit;

use App\Helpers\DateTime;
use Carbon\Carbon;

dataset('month end reminder days', [
    // October 2026 ends on a Saturday: the fifth-last working day is Mon 26.10.
    'saturday month end, day before the period' => ['2026-10-25', false],
    'saturday month end, first day' => ['2026-10-26', true],
    'saturday month end, last working day' => ['2026-10-30', true],
    'saturday month end, last day' => ['2026-10-31', true],
    // November 2026 ends on a Monday: the period reaches back into the previous week (Tue 24.11.).
    'monday month end, day before the period' => ['2026-11-23', false],
    'monday month end, first day' => ['2026-11-24', true],
    'monday month end, last day' => ['2026-11-30', true],
    // May 2026 ends on a Sunday: Mon 25.5. through Fri 29.5. plus the weekend.
    'sunday month end, day before the period' => ['2026-05-24', false],
    'sunday month end, first day' => ['2026-05-25', true],
    'sunday month end, last day' => ['2026-05-31', true],
    // July 2026 ends on a Friday: Mon 27.7. through Fri 31.7.
    'friday month end, day before the period' => ['2026-07-26', false],
    'friday month end, first day' => ['2026-07-27', true],
    // February in a non-leap and a leap year.
    'february 2026, day before the period' => ['2026-02-20', false],
    'february 2026, first day' => ['2026-02-23', true],
    'february 2026, last day' => ['2026-02-28', true],
    'february 2028, day before the period' => ['2028-02-22', false],
    'february 2028, first day' => ['2028-02-23', true],
    'february 2028, leap day' => ['2028-02-29', true],
    'middle of the month' => ['2026-10-15', false],
    'first day of the next month' => ['2026-11-01', false],
]);

test('month end reminder period spans the last five working days through the last calendar day', function (string $date, bool $expected) {
    expect(DateTime::isInMonthEndReminderPeriod(Carbon::parse($date)))->toBe($expected);
})->with('month end reminder days');

test('month end reminder period ignores the time of day', function () {
    expect(DateTime::isInMonthEndReminderPeriod(Carbon::parse('2026-10-31 23:59:59')))->toBeTrue();
    expect(DateTime::isInMonthEndReminderPeriod(Carbon::parse('2026-10-26 00:00:00')))->toBeTrue();
    expect(DateTime::isInMonthEndReminderPeriod(Carbon::parse('2026-10-25 23:59:59')))->toBeFalse();
});

test('month end reminder period does not mutate the given date', function () {
    $date = Carbon::parse('2026-10-28 10:30:00');

    DateTime::isInMonthEndReminderPeriod($date);

    expect($date->toDateTimeString())->toBe('2026-10-28 10:30:00');
});
