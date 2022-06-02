<?php

namespace App\Models;

use App\Support\GlobalSearch\FiltersGlobalSearch;
use App\Support\GlobalSearch\GlobalSearchResult;
use App\Traits\FiltersSearch;
use App\Traits\OrdersResults;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Vehicle extends Model implements FiltersGlobalSearch
{
    use FiltersSearch;
    use OrdersResults;

    protected $appends = [
        'make_model', 'current_kilometres'
    ];

    protected $fillable = [
        'make', 'model', 'registration_identifier', 'comment',
    ];

    protected $filterFields = [
        'make', 'model', 'registration_identifier', 'comment',
    ];

    protected $filterKeys = [];

    protected $orderKeys = [
        'default' => ['registration_identifier'],
        'reg-asc' => ['registration_identifier'],
        'reg-desc' => [['registration_identifier', 'desc']],
        'type-asc' => ['make', 'model'],
        'type-desc' => [['make', 'desc'], ['model', 'desc']],
    ];

    public static function filterGlobalSearch(string $query) : Collection
    {
        return Vehicle::filterSearch($query)
            ->get()
            ->map(function(Vehicle $vehicle) {
                return new GlobalSearchResult(
                    Vehicle::class,
                    'Fahrzeug',
                    $vehicle->id,
                    "$vehicle->registration_identifier ($vehicle->make_model)",
                    route('vehicles.show', $vehicle)
                );
            });
    }

    public function logbook()
    {
        return $this->hasMany(Logbook::class);
    }

    public function getMakeModelAttribute() {
        return "{$this->make} {$this->model}";
    }

    public function getCurrentKilometresAttribute() {
        return $this->logbook()->exists() ? $this->logbook()->max('end_kilometres') : null;
    }

    public function getCurrentKilometresStringAttribute() {
        return $this->current_kilometres !== null ? "{$this->logbook()->max('end_kilometres')} km" : null;
    }
}
