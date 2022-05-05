<?php

namespace App\Helpers;

class Number
{
    public static function toLocal(int|float $value, int $maxFractionDigits = null)
    {
        $formatter = \NumberFormatter::create('de_DE', \NumberFormatter::DEFAULT_STYLE, );

        if($maxFractionDigits) {
            $formatter->setAttribute(\NumberFormatter::MAX_FRACTION_DIGITS, $maxFractionDigits);
        }

        return $formatter->format($value);
    }

}
