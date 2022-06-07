<?php

namespace App\Models;

use App\Support\GlobalSearch\FiltersGlobalSearch;
use App\Support\GlobalSearch\GlobalSearchResult;
use App\Traits\FiltersLatestChanges;
use App\Traits\FiltersSearch;
use App\Traits\OrdersResults;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class Address extends Model implements FiltersGlobalSearch
{
    use FiltersLatestChanges;
    use FiltersSearch;
    use HasFactory;
    use OrdersResults;

    protected $appends = [
        'address_line',
    ];

    protected $fillable = [
        'name', 'street_number', 'postcode', 'city',
    ];

    protected $filterFields = [
        'name', 'street_number', 'postcode', 'city', 'companies.name',
    ];

    protected $filterKeys = [];

    protected $orderKeys = [
        'default' => ['street_number', 'postcode', 'city'],
        'street-number-asc' => ['street_number', 'postcode', 'city'],
        'street-number-desc' => [['street_number', 'desc'], ['postcode', 'desc'], ['city', 'desc']],
        'postcode-asc' => ['postcode', 'city', 'street_number'],
        'postcode-desc' => [['postcode', 'desc'], ['city', 'desc'], ['street_number', 'desc']],
    ];

    public static function filterGlobalSearch(string $query, ?int $latestQuantity = null) : Collection
    {
        return Address::filterSearch($query)
            ->when($latestQuantity && $latestQuantity > 0, function ($query) use ($latestQuantity) {
                return $query->latest('updated_at')->limit($latestQuantity);
            })
            ->get()
            ->map(function(Address $address) {
                return new GlobalSearchResult(
                    Address::class,
                    'Adresse',
                    $address->id,
                    "$address->name ($address->address_line)",
                    route('addresses.show', $address),
                    $address->created_at,
                    $address->updated_at,
                );
            });
    }

    public function companies()
    {
        return $this->morphedByMany(Company::class, 'addressable');
    }

    public function people()
    {
        return $this->morphedByMany(Person::class, 'addressable');
    }

    public function getAddressLineAttribute()
    {
        return "{$this->street_number}, {$this->postcode} {$this->city}";
    }
}
