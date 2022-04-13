<?php

namespace App\Models;

use App\Traits\OrdersResults;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use OrdersResults;

    protected $fillable = [];

    protected $orderKeys = [
        'default' => ['name'],
    ];

    public function accounting()
    {
        return $this->hasMany(Accounting::class);
    }
}
