<?php

namespace App\Support\GlobalSearch;

use Illuminate\Support\Collection;

interface FiltersGlobalSearch
{
    const SEARCH_METHOD = 'filterGlobalSearch';
    const RESOLVE_METHOD = 'resolveGlobalSearchResult';

    public static function filterGlobalSearch(string $query, ?int $latestQuantity): Collection;
    public static function resolveGlobalSearchResult(int|string $id): ?GlobalSearchResult;
}
