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
            default => 'Tag',
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

}
