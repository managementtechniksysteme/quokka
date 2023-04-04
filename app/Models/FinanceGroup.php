<?php

namespace App\Models;

use App\Support\GlobalSearch\FiltersGlobalSearch;
use App\Support\GlobalSearch\GlobalSearchResult;
use App\Traits\FiltersLatestChanges;
use App\Traits\FiltersSearch;
use App\Traits\OrdersResults;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class FinanceGroup extends Model implements FiltersGlobalSearch
{
    use FiltersLatestChanges;
    use FiltersSearch;
    use OrdersResults;

    protected $fillable = [
        'title', 'comment', 'project_id',
    ];

    protected $filterFields = [
        'title', 'financeRecords.title'
    ];

    protected $filterKeys = [];

    protected $orderKeys = [
        'default' => ['title'],
        'title-asc' => ['title'],
        'title-desc' => [['title', 'desc']],
    ];

    public static function filterGlobalSearch(string $query, ?int $latestQuantity = null) : Collection
    {
        return FinanceGroup::filterSearch($query)
            ->when($latestQuantity && $latestQuantity > 0, function ($query) use ($latestQuantity) {
                return $query->latest('updated_at')->limit($latestQuantity);
            })
            ->get()
            ->map(function(FinanceGroup $financeGroup) {
                return new GlobalSearchResult(
                    FinanceGroup::class,
                    'Finanzgruppe',
                    $financeGroup->id,
                    $financeGroup->title,
                    route('finance-groups.show', $financeGroup),
                    $financeGroup->created_at,
                    $financeGroup->updated_at,
                );
            });
    }

    public function financeRecords()
    {
        return $this->hasMany(FinanceRecord::class);
    }

    public function getRecordsSumAttribute()
    {
        return $this->financeRecords()->sum('amount');
    }
}
