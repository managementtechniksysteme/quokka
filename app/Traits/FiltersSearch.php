<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;

trait FiltersSearch
{
    public static function defaultFilter() : ?string
    {
        return null;
    }

    public static function handleDefaultFilter(Request $request) {
        $defaultFilter = self::defaultFilter();

        if (! $request->has('search') && $defaultFilter !== null && $defaultFilter !== '') {
            $request->request->add(['search' => $defaultFilter]);
        } elseif ($request->has('search') && $request->search === '') {
            $request->request->remove('search');
        }
    }

    public function scopeFilterSearch($query, $params = null)
    {
        if (! (isset($params['search']) && isset($this->filterKeys))) {
            return $query;
        }

        // split search on white space excluding inside quotes
        preg_match_all('/"(?:\\\\.|[^\\\\"])*"|\S+/', $params['search'], $terms);

        $searchTerms = [];

        $terms = Arr::flatten($terms);

        foreach ($terms as $term) {
            $termHandled = false;

            $term = trim($term, '\'"');

            $key = $term;

            // handle negation
            if (substr($term, 0, 1) === '!') {
                $key = substr($key, 1);
            }

            foreach (array_keys($this->filterKeys) as $filterKey) {
                // every filter key must start at the beginning and end at the end of a search term (^, $)
                preg_match('/^'.$filterKey.'$/', $key, $keyMatch);

                // exact match
                if ($filterKey === $key) {
                    $query = $this->handleFilterKey($query, $filterKey, $term);
                    $termHandled = true;
                    break;
                }

                // match with variable parameter
                if (! empty($keyMatch)) {
                    $query = $this->handleFilterKey($query, $filterKey, $term, $keyMatch[1]);
                    $termHandled = true;
                    break;
                }
            }

            // push the search term onto the general terms list when it is not a special key
            if (! $termHandled) {
                array_push($searchTerms, $term);
            }

            $termHandled = false;
        }

        // append all general terms to the search query (logical and between terms, logical or within terms for all
        // fields)
        if (! empty($searchTerms)) {
            $query = $query->where(function ($query) use ($searchTerms) {
                foreach ($this->filterFields as $filterField) {
                    $likeQuery = '%'.join('%', $searchTerms).'%';
                    $query->orWhere($filterField, 'LIKE', $likeQuery);
                }
            });
        }

        return $query;
    }

    private function handleFilterKey($query, $key, $match, $parameter = null)
    {
        $raw = isset($this->filterKeys[$key]['raw']);
        $hasraw = isset($this->filterKeys[$key]['hasraw']);

        // extract filter key value
        $values = $this->filterKeys[$key]['raw'] ?? $this->filterKeys[$key]['hasraw'] ?? $this->filterKeys[$key];

        foreach ($values as $index => $value) {
            // replace the user provided value in the filter parameter
            if (str_contains($value, '{value}') && $parameter !== null) {
                $values = array_replace($values, [$index => str_replace('{value}', $parameter, $value)]);
            }
        }

        // append a raw query expression
        if ($raw) {
            return str_starts_with($match, '!') ? $query->whereRaw($values[1]) : $query->whereRaw($values[0]);
        }

        // append a raw query expression on a related model
        if ($hasraw) {
            if (str_starts_with($match, '!')) {
                return $query->whereHas($values[0], function ($query) use ($values) {
                    return $query->whereRaw($values[2]);
                });
            } else {
                return $query->whereHas($values[0], function ($query) use ($values) {
                    return $query->whereRaw($values[1]);
                });
            }
        }

        // filter related model attributes
        if (str_contains($values[0], '.')) {
            $entity = substr($values[0], 0, strrpos($values[0], '.'));
            $parameter = substr($values[0], strrpos($values[0], '.') + 1);
            $value = $values[1];

            if (str_starts_with($match, '!')) {
                return $query->whereHas($entity, function ($query) use ($parameter, $value) {
                    return $query->where($parameter, $values[3] ?? '!=', $value);
                });
            } else {
                return $query->whereHas($entity, function ($query) use ($parameter, $value) {
                    return $query->where($parameter, $values[2] ?? '=', $value);
                });
            }
        }

        // filter direct model attributes
        return str_starts_with($match, '!') ? $query->where($values[0], $values[3] ?? '!=', $values[1]) : $query->where($values[0], $values[2] ?? '=', $values[1]);
    }
}
