<?php

namespace App\Models;

use App\Support\GlobalSearch\FiltersGlobalSearch;
use App\Support\GlobalSearch\GlobalSearchResult;
use App\Traits\FiltersLatestChanges;
use App\Traits\FiltersSearch;
use App\Traits\OrdersResults;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class FinanceRecord extends Model implements FiltersGlobalSearch
{
    use FiltersLatestChanges;
    use FiltersSearch;
    use OrdersResults;

    protected $casts = [
        'billed_on' => 'date',
        'amount' => 'double',
    ];

    protected $fillable = [
        'billed_on', 'title', 'comment', 'amount', 'finance_group_id',
    ];

    protected $filterFields = [
        'title',
    ];

    protected $filterKeys = [];

    protected $orderKeys = [
        'default' => ['billed_on'],
    ];

    public static function filterGlobalSearch(string $query, ?int $latestQuantity = null) : Collection
    {
        return FinanceRecord::filterSearch($query)
            ->when($latestQuantity && $latestQuantity > 0, function ($query) use ($latestQuantity) {
                return $query->latest('updated_at')->limit($latestQuantity);
            })
            ->get()
            ->map(function(FinanceRecord $financeRecord) {
                $financeGroupTitle = $financeRecord->financeGroup->title_string;

                return new GlobalSearchResult(
                    FinanceRecord::class,
                    'Finanzeintrag',
                    $financeRecord->id,
                    "$financeRecord->title (Gruppe $financeGroupTitle)",
                    route('finance-groups.show', $financeRecord->financeGroup),
                    $financeRecord->created_at,
                    $financeRecord->updated_at,
                );
            });
    }

    public function financeGroup()
    {
        return $this->belongsTo(FinanceGroup::class);
    }
}
