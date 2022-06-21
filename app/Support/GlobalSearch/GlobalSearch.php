<?php

namespace App\Support\GlobalSearch;

use App\Models\TaskComment;
use App\Traits\FiltersLatestChanges;
use Fuse\Fuse;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class GlobalSearch
{
    private static $fuseKeys = ['name', 'type'];
    private static $fuseThreshold = 0.3;

    public static function search(string $query) : Collection
    {
        $searchResults = collect();

        foreach (config('search.models') as $model) {
            $searchResults = $searchResults->concat(static::searchModel($model, $query));
        }

        return $searchResults;
    }

    public static function searchFuzzy(string $query) : Collection
    {
        $searchResults = collect();
        $termsMatchingFilterKeys = [];

        foreach (config('search.models') as $model) {
            $searchResults = $searchResults->concat(static::searchModel($model, $query));

            // add query terms that match special filter keys to the exclusion list for fuzzy search
            $termsMatchingFilterKeys = array_merge(
                $termsMatchingFilterKeys,
                array_diff(
                    static::getMatchingFilterKeysForModel($model, $query),
                    $termsMatchingFilterKeys
                )
            );
        }

        // only consider terms that are not matching special filter keys for the fuzzy search
        $fuzzyQuery = static::getQueryWithoutTermsMatchingFilterKeys($query, $termsMatchingFilterKeys);

        return $fuzzyQuery ? static::fuzzySearch($searchResults->toArray(), $fuzzyQuery) : $searchResults;
    }

    public static function getLatestChanges(int $quantity) : Collection
    {
        $searchResults = collect();

        foreach (config('search.models') as $model) {
            $searchResults = $searchResults->concat(static::getLatestModelChanges($model, $quantity));
        }

        return $searchResults
            ->sortByDesc(function ($searchResult) {
                return $searchResult->updated_at;
            })
            ->values()
            ->take($quantity);
    }

    private static function searchModel(string $model, string $query) : Collection
    {
        if(Auth::user()->cannot('viewAny', $model)) {
            return collect();
        }

        if (!class_exists($model) || !in_array(FiltersGlobalSearch::class, class_implements($model))) {
            return collect();
        }

        return forward_static_call([$model, FiltersGlobalSearch::SEARCH_METHOD], $query);
    }

    private static function getMatchingFilterKeysForModel(string $model, string $query) : array
    {
        if (!class_exists($model) || !in_array(FiltersGlobalSearch::class, class_implements($model))) {
            return [];
        }

        $matches = [];

        // split search on white space excluding inside quotes
        preg_match_all('/"(?:\\\\.|[^\\\\"])*"|\S+/', $query, $terms);

        $terms = Arr::flatten($terms);

        foreach ($terms as $term) {
            $term = trim($term, '\'"');

            $key = $term;

            // handle negation
            if (substr($term, 0, 1) === '!') {
                $key = substr($key, 1);
            }

            foreach (array_keys((new $model)->getfilterKeys()) as $filterKey) {
                // every filter key must start at the beginning and end at the end of a search term (^, $)
                preg_match('/^' . $filterKey . '$/', $key, $keyMatch);

                // query matches a filter expression
                if ($filterKey === $key || !empty($keyMatch)) {
                    $matches[] = $term;
                }
            }
        }

        return $matches;
    }

    private static function getQueryWithoutTermsMatchingFilterKeys(string $query, array $filterKeyTerms) : string
    {
        // split search on white space excluding inside quotes
        preg_match_all('/"(?:\\\\.|[^\\\\"])*"|\S+/', $query, $terms);

        $terms = Arr::flatten($terms);

        return join(' ' ,array_diff($terms, $filterKeyTerms));
    }

    private static function fuzzySearch(array $list, string $query) : Collection
    {
        $fuseOptions = [
            'keys' => static::$fuseKeys,
            'threshold' => static::$fuseThreshold
        ];

        return collect(array_map(
            function ($result) {
                return new GlobalSearchResult(
                    $result['item']['model'],
                    $result['item']['type'],
                    $result['item']['id'],
                    $result['item']['name'],
                    $result['item']['route'],
                    $result['item']['created_at'],
                    $result['item']['updated_at']
                );
            },
            (new Fuse($list, $fuseOptions))->search($query)
        ));
    }

    private static function getLatestModelChanges(string $model, int $quantity) : Collection
    {
        if(Auth::user()->cannot('viewAny', $model)) {
            return collect();
        }

        if (!class_exists($model) || !in_array(FiltersLatestChanges::class, class_uses($model))) {
            return collect();
        }

        return forward_static_call([$model, FiltersLatestChanges::$FILTERS_LATEST_CHANGES_METHOD], $quantity);
    }
}
