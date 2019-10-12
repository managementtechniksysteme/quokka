<?php

namespace App;

use App\Traits\OrdersResults;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use OrdersResults;

    protected $orderKeys = [
        'default' => ['due_on'],
        'due-asc' => ['due_on'],
        'due-desc' => [['due_on', 'desc']],
        'name-asc' => ['name'],
        'name-desc' => [['name', 'desc']],
        'status-asc' => ['status'],
        'status-desc' => [['status', 'desc']],
    ];
}
