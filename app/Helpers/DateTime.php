<?php

namespace App\Helpers;

use Carbon\Carbon;

class DateTime
{
    public static function greetingForTimeOfDay(Carbon $timestamp)
    {
        $hourOfDay = $timestamp->format('H');

        return match (true) {
            $hourOfDay >= 17 => 'Guten Abend',
            $hourOfDay >= 13 => 'Schönen Nachmittag',
            $hourOfDay >= 11 => 'Mahlzeit',
            $hourOfDay >= 0 => 'Guten Morgen',
            default => 'Guten Tag',
        };
    }

    public static function iconStringForTimeOfDay(Carbon $timestamp)
    {
        $hourOfDay = $timestamp->format('H');

        return match (true) {
            $hourOfDay >= 17 => 'moon-stars',
            $hourOfDay >= 13 => 'sun',
            $hourOfDay >= 11 => 'cup-straw',
            $hourOfDay >= 0 => 'sunrise',
            default => 'activity',
        };
    }

    /**
     * Whether the given day lies in the month-end reminder window: from the
     * fifth-last working day (Mon-Fri) of its month through the last calendar day.
     * Public holidays are not taken into account.
     */
    public static function isInMonthEndReminderPeriod(Carbon $date): bool
    {
        $lastDayOfMonth = $date->copy()->endOfMonth()->startOfDay();

        $firstDayOfPeriod = $lastDayOfMonth->copy();
        $workingDaysCounted = $lastDayOfMonth->isWeekday() ? 1 : 0;

        while ($workingDaysCounted < 5) {
            $firstDayOfPeriod->subDay();

            if ($firstDayOfPeriod->isWeekday()) {
                $workingDaysCounted++;
            }
        }

        return $date->copy()->startOfDay()->betweenIncluded($firstDayOfPeriod, $lastDayOfMonth);
    }

}
