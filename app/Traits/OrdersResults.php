<?php

namespace App\Traits;

trait OrdersResults
{
    public function scopeOrder($query, $params)
    {
        if (isset($params['sort']) && isset($this->orderKeys[$params['sort']])) {
            $orderArray = $this->orderKeys[$params['sort']];
        } else {
            if (isset($this->orderKeys['default'])) {
                $orderArray = $this->orderKeys['default'];
            } else {
                return $query;
            }
        }

        foreach ($orderArray as $order) {
            if (is_array($order)) {
                switch (sizeof($order)) {
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
