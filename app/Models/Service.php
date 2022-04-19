<?php

namespace App\Models;

use App\Traits\OrdersResults;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use OrdersResults;

    protected $appends = [
        'name_with_unit',
    ];

    protected $fillable = [];

    protected $orderKeys = [
        'default' => ['name'],
    ];

    public function accounting()
    {
        return $this->hasMany(Accounting::class);
    }

    public function getNameWithUnitAttribute()
    {
        if ($this->unit === null) {
            return "{$this->name}";
        }
        else {
            return "{$this->name} ({$this->unit})";
        }
    }
}
