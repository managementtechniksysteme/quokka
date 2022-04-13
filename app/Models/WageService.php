<?php

namespace App\Models;

use App\Traits\FiltersResults;
use App\Traits\OrdersResults;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class WageService extends Model
{
    use FiltersResults;
    use OrdersResults;

    protected $table = 'services';

    protected $attributes = [
        'type' => 'wage',
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

    protected static function booted()
    {
        static::addGlobalScope('type', function (Builder $builder) {
            $builder->where('type', 'wage');
        });
    }
}
