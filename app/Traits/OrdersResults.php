<?php

namespace App\Traits;

trait OrdersResults
{
    public function scopeOrder($query, $sort = null)
    {
        if (isset($sort) && isset($this->orderKeys[$sort])) {
            $orderArray = $this->orderKeys[$sort];
        } elseif (isset($this->orderKeys['default'])) {
            $orderArray = $this->orderKeys['default'];
        } else {
            return $query;
        }

        if (isset($orderArray['raw'])) {
            return $query->orderByRaw($orderArray['raw']);
        }

        foreach ($orderArray as $order) {
            if (is_array($order)) {
                switch (count($order)) {
                    case 1:
                        $query->orderBy($order[0]);
                        break;
                    case 2:
                        $query->orderBy($order[0], $order[1]);
                        break;
                    default:
                        break;
                }
            } else {
                $query->orderBy($order);
            }
        }

        return $query;
    }
}
