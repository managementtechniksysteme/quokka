<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Contracts\Database\Eloquent\ComparesCastableAttributes;
use Illuminate\Contracts\Database\Eloquent\SerializesCastableAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Date;

/**
 * A calendar day without a time of day.
 *
 * Reads and writes exactly like the built-in "date" cast, but serializes to a
 * plain "Y-m-d" string. The built-in cast serializes local midnight as a UTC
 * timestamp ("2026-10-18T22:00:00.000000Z" for 19 October), which browsers
 * can only map back to the right day by knowing the server's UTC offset on
 * that particular date. The frontend got that wrong across DST changes.
 * ("date:Y-m-d" isn't used because Eloquent then stores the raw input
 * without normalizing it.)
 */
class CalendarDate implements CastsAttributes, ComparesCastableAttributes, SerializesCastableAttributes
{
    /**
     * Objects aren't cached, so mutating a returned Carbon doesn't write back to the model.
     */
    public bool $withoutObjectCaching = true;

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): ?Carbon
    {
        return $value === null ? null : Date::parse($value)->startOfDay();
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): ?string
    {
        return $value === null ? null : $model->fromDateTime($value);
    }

    /**
     * Eloquent hands a date returned by a class cast over already serialized
     * as that UTC timestamp, so it's moved back into the app timezone first.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function serialize(Model $model, string $key, mixed $value, array $attributes): ?string
    {
        return $value === null ? null : Date::parse($value)->setTimezone(config('app.timezone'))->format('Y-m-d');
    }

    public function compare(Model $model, string $key, mixed $firstValue, mixed $secondValue): bool
    {
        return $model->fromDateTime($firstValue) === $model->fromDateTime($secondValue);
    }
}
