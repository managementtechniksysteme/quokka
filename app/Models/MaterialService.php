<?php

namespace App\Models;

use App\Traits\FiltersSearch;
use App\Traits\OrdersResults;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class MaterialService extends Model
{
    use FiltersSearch;
    use OrdersResults;

    protected $table = 'services';

    protected $attributes = [
        'type' => 'material',
    ];

    protected $fillable = [
        'name', 'description',
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

    protected static function booted()
    {
        static::addGlobalScope('type', function (Builder $builder) {
            $builder->where('type', 'material');
        });
    }
}
