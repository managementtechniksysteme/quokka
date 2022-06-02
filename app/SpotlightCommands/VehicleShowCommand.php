<?php

namespace App\SpotlightCommands;

use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;
use LivewireUI\Spotlight\SpotlightCommandDependencies;
use LivewireUI\Spotlight\SpotlightCommandDependency;
use LivewireUI\Spotlight\SpotlightSearchResult;

class VehicleShowCommand extends SpotlightCommand
{
    protected string $name = 'Fahrzeug anzeigen';

    protected string $description = 'Ein spezifisches Fahrzeug anzeigen';

    protected array $synonyms = [
        'Auto',
    ];

    public function dependencies(): ?SpotlightCommandDependencies
    {
        return SpotlightCommandDependencies::collection()
            ->add(
                SpotlightCommandDependency::make('vehicle')
                    ->setPlaceholder('Welches Fahrzeug möchtest du anzeigen?')
                    ->setType(SpotlightCommandDependency::SEARCH)
            );
    }

    public function searchVehicle(string $query): Collection
    {
        return Vehicle::filterSearch($query)
            ->order()
            ->get()
            ->map(function (Vehicle $vehicle) {
                return new SpotlightSearchResult(
                    $vehicle->id,
                    $vehicle->registration_identifier,
                    $vehicle->make_model
                );
            });
    }

    public function execute(Spotlight $spotlight, Vehicle $vehicle): void
    {
        $spotlight->redirect(route('vehicles.show', $vehicle));
    }

    public function shouldBeShown(Request $request): bool
    {
        return $request->user()->can('viewAny', Vehicle::class);
    }
}
