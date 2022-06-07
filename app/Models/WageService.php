<?php

namespace App\Models;

use App\Support\GlobalSearch\FiltersGlobalSearch;
use App\Support\GlobalSearch\GlobalSearchResult;
use App\Traits\FiltersLatestChanges;
use App\Traits\FiltersSearch;
use App\Traits\OrdersResults;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use function PHPUnit\Framework\matches;

class WageService extends Model implements FiltersGlobalSearch
{
    use FiltersLatestChanges;
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

    public static function filterGlobalSearch(string $query, ?int $latestQuantity = null) : Collection
    {
        return WageService::filterSearch($query)
            ->when($latestQuantity && $latestQuantity > 0, function ($query) use ($latestQuantity) {
                return $query->latest('updated_at')->limit($latestQuantity);
            })
            ->get()
            ->map(function(WageService $wageService) {
                return new GlobalSearchResult(
                    WageService::class,
                    'Lohndienstleistung',
                    $wageService->id,
                    $wageService->name_with_unit,
                    route('wage-services.show', $wageService),
                    $wageService->created_at,
                    $wageService->updated_at,
                );
            });
    }

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
