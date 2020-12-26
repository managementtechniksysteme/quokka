<?php

namespace App\Traits;

use Illuminate\Support\Arr;

trait FiltersResults
{
    public function scopeFilter($query, $params = null)
    {
        if (! (isset($params['search']) && isset($this->filterKeys))) {
            return $query;
        }

        preg_match_all('/"(?:\\\\.|[^\\\\"])*"|\S+/', $params['search'], $terms);

        $searchTerms = [];

        $terms = Arr::flatten($terms);

        foreach ($terms as $term) {
            $termHandled = false;

            $term = trim($term, '\'"');

            $key = $term;

            if (substr($term, 0, 1) === '!') {
                $key = substr($key, 1);
            }

            foreach (array_keys($this->filterKeys) as $filterKey) {
                preg_match('/'.$filterKey.'/', $key, $keyMatch);

                if ($filterKey === $key) {
                    $query = $this->handleFilterKey($query, $filterKey, $term);
                    $termHandled = true;
                    break;
                }

                if (! empty($keyMatch)) {
                    $query = $this->handleFilterKey($query, $filterKey, $term, $keyMatch[1]);
                    $termHandled = true;
                    break;
                }
            }

            if (! $termHandled) {
                array_push($searchTerms, $term);
            }

            $termHandled = false;
        }

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
        $raw = isset($this->filterKeys[$key]['raw']) ? true : false;

        $values = $this->filterKeys[$key]['raw'] ?? $this->filterKeys[$key];

        foreach ($values as $index => $value) {
            if (str_contains($value, '{value}') && $parameter !== null) {
                $values = array_replace($values, [$index => str_replace('{value}', $parameter, $value)]);
            }
        }

        if ($raw) {
            return str_starts_with($match, '!') ? $query->whereRaw($values[1]) : $query->whereRaw($values[0]);
        }

        if (str_contains($values[0], '.')) {
            $entity = substr($values[0], 0, strrpos($values[0], '.'));
            $parameter = substr($values[0], strrpos($values[0], '.') + 1);
            $value = $values[1];

            if (str_starts_with($match, '!')) {
                $query = $query->whereHas($entity, function ($query) use ($parameter, $value) {
                    return $query->where($parameter, $values[3] ?? '!=', $value);
                });
            } else {
                $query = $query->whereHas($entity, function ($query) use ($parameter, $value) {
                    return $query->where($parameter, $values[2] ?? '=', $value);
                });
            }
        } else {
            $query = str_starts_with($match, '!') ? $query->where($values[0], $values[3] ?? '!=', $values[1]) : $query->where($values[0], $values[2] ?? '=', $values[1]);
        }

        return $query;
    }
}
