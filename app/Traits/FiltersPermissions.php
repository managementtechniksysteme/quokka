<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;

trait FiltersPermissions
{
    public function scopeFilterPermissions($query)
    {
        $query = $query->where(function ($query) {
            foreach ($this->permissionFilters as $permission => $filters) {
                if(Auth::user()->can($permission)) {
                    if(!is_array($filters[0])) {
                        $filters = array($filters);
                    }

                    $query = $query->orWhere(function ($query) use ($filters) {
                        return $this->handlePermissionFilterKey($query, $filters);
                    });
                }
            }

            return $query;
        });
    }

    private function handlePermissionFilterKey($query, $filters)
    {
        foreach ($filters as $filter) {

            $query = $query->where(function($query) use ($filter) {

                $raw = isset($filter['raw']);
                $hasraw = isset($filter['hasraw']);

                $values = $filter['raw'] ?? $filter['hasraw'] ?? $filter;

                foreach ($values as $index => $value) {
                    if (str_contains($value, '{user}')) {
                        $values = array_replace($values, [$index => str_replace('{user}', Auth::user()->employee_id, $value)]);
                    }
                }

                if ($raw) {
                    $query = $query->whereRaw($values[0]);
                }

                if ($hasraw) {
                    return $query->whereHas($values[0], function ($query) use ($values) {
                        return $query->whereRaw($values[1]);
                    });
                }

                if (str_contains($values[0], '.')) {
                    $doesntHave = false;

                    $entity = substr($values[0], 0, strrpos($values[0], '.'));
                    if(str_starts_with($values[0], '!')) {
                        $doesntHave = true;
                        $entity = substr($entity, 1);
                    }
                    $parameter = substr($values[0], strrpos($values[0], '.') + 1);
                    $value = $values[1];

                    if($doesntHave) {
                        return $query->whereDoesntHave($entity, function ($query) use ($parameter, $value) {
                            return $query->where($parameter, $values[2] ?? '=', $value);
                        });
                    }
                    else {
                        return $query->whereHas($entity, function ($query) use ($parameter, $value) {
                            return $query->where($parameter, $values[2] ?? '=', $value);
                        });
                    }
                }

                return $query->where($values[0], $values[2] ?? '=', $values[1]);

            });

        }

        return $query;
    }
}
