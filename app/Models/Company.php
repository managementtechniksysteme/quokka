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

class Company extends Model implements FiltersGlobalSearch
{
    use FiltersLatestChanges;
    use FiltersSearch;
    use HasFactory;
    use OrdersResults;

    protected $appends = [
        'full_name',
    ];

    protected $fillable = [
        'name',
        'name_2',
        'phone',
        'fax',
        'email',
        'website',
        'comment',
        'contact_person_id'
    ];

    protected $filterFields = [
        'name', 'name_2',
    ];

    protected $filterKeys = [];

    protected $orderKeys = [
        'default' => ['name'],
        'name-asc' => ['name'],
        'name-desc' => [['name', 'desc']],
    ];

    public static function filterGlobalSearch(string $query, ?int $latestQuantity = null) : Collection
    {
        return Company::filterSearch($query)
            ->when($latestQuantity && $latestQuantity > 0, function ($query) use ($latestQuantity) {
                return $query->latest('updated_at')->limit($latestQuantity);
            })
            ->get()
            ->map(function(Company $company) {
                return new GlobalSearchResult(
                    Company::class,
                    'Firma',
                    $company->id,
                    $company->name,
                    route('companies.show', $company),
                    $company->created_at,
                    $company->updated_at,
                );
            });
    }

    public function address()
    {
        return $this->morphToMany(Address::class, 'addressable')->wherePivot('address_type', 'company');
    }

    public function operatorAddress()
    {
        return $this->morphToMany(Address::class, 'addressable')->wherePivot('address_type', 'operator');
    }

    public function people()
    {
        return $this->hasMany(Person::class);
    }

    public function contactPerson()
    {
        return $this->belongsTo(Person::class);
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    public function getFullNameAttribute()
    {
        return $this->name.($this->name_2 ? (' '.$this->name_2) : '');
    }
}
