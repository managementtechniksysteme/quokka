<?php

namespace App\Models;

use App\Support\GlobalSearch\FiltersGlobalSearch;
use App\Support\GlobalSearch\GlobalSearchResult;
use App\Traits\FiltersSearch;
use App\Traits\OrdersResults;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class MaterialService extends Model implements FiltersGlobalSearch
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

    public static function filterGlobalSearch(string $query) : Collection
    {
        return MaterialService::filterSearch($query)
            ->get()
            ->map(function(MaterialService $materialService) {
                return new GlobalSearchResult(
                    MaterialService::class,
                    'Materialleistung',
                    $materialService->id,
                    $materialService->name,
                    route('material-services.show', $materialService)
                );
            });
    }

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
