<?php

namespace App\Helpers;

class Email
{
    public static function concatEmails(array $values) : string
    {
        if(empty($values)) {
            return '';
        }

        return rtrim(
            array_reduce(array_keys($values), function ($string, $key) use ($values) {
                return $string . (
                    isset($values[$key]) ? (
                        $values[$key] .
                        ($key !== $values[$key] ? ' <' . $key . '>' : '')
                    ) .
                    ', ' :
                    ($key . ', ')
                );
            }),
            ', ');
    }

}
