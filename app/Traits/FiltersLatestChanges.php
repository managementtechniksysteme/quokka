<?php

namespace App\Traits;

use Illuminate\Support\Collection;

trait FiltersLatestChanges
{
    public static string $FILTERS_LATEST_CHANGES_METHOD = 'filterLatestChanges';

    public static function filterLatestChanges(int $quantity) : Collection {
        return static::filterGlobalSearch('', $quantity);
    }
}
