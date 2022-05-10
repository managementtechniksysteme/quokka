<?php

namespace App\Models;

use App\Traits\FiltersSearch;
use App\Traits\OrdersResults;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use function PHPUnit\Framework\matches;

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

    public function getUnitStringAttribute()
    {
        if(!$this->unit) {
            return '';
        }

        return match (true) {
            strlen($this->unit) === 1 => "{$this->unit}",
            strlen($this->unit) > 1 => " {$this->unit}",
            default => " {$this->unit}",
        };
    }

    protected static function booted()
    {
        static::addGlobalScope('type', function (Builder $builder) {
            $builder->where('type', 'wage');
        });
    }
}
