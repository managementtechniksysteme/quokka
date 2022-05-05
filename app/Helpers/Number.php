<?php

namespace App\Helpers;

class Number
{
    public static function toLocal(string $value)
    {
        $formatter = \NumberFormatter::create('de_DE', \NumberFormatter::DEFAULT_STYLE);

        return $formatter->format($value);
    }

}
