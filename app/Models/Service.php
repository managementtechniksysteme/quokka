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
        if ($this->type === 'material') {
            $currency_unit = ApplicationSettings::get()->currency_unit;
            return "$this->name ($currency_unit)";
        }
        else if ($this->unit === null) {
            return "$this->name";
        }
        else {
            return "$this->name ($this->unit)";
        }
    }

    public function getUnitStringAttribute() {
        $unit = $this->type === 'material' ? ApplicationSettings::get()->currency_unit : $this->unit;

        if(!$unit) {
            return null;
        }

        return mb_strlen($unit) > 1 ? " $unit" : $unit;
    }
}
