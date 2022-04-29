<?php

namespace App\Models;

use App\Traits\FiltersSearch;
use App\Traits\OrdersResults;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class WageService extends Model
{
    use FiltersSearch;
    use OrdersResults;

    protected $table = 'services';

    protected $attributes = [
        'type' => 'wage',
    ];

    protected $appends = [
        'name_with_unit',
    ];

    protected $fillable = [
        'name', 'description', 'unit', 'costs',
    ];

    protected $filterFields = [
        'name', 'description',
    ];

    protected $filterKeys = [];

    protected $orderKeys = [
        'default' => ['name'],
        'name-asc' => ['name'],
        'name-desc' => [['name', 'desc']],
    ];

    public function accounting()
    {
        return $this->hasMany(Accounting::class);
    }

    public function getNameWithUnitAttribute()
    {
        return "{$this->name} ({$this->unit})";
    }

    protected static function booted()
    {
        static::addGlobalScope('type', function (Builder $builder) {
            $builder->where('type', 'wage');
        });
    }
}
