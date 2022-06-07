<?php

namespace App\Support\GlobalSearch;

use Illuminate\Support\Collection;

interface FiltersGlobalSearch
{
    const SEARCH_METHOD = 'filterGlobalSearch';
    public static function filterGlobalSearch(string $query, ?int $latestQuantity): Collection;
}
