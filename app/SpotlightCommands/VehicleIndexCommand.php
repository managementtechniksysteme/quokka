<?php

namespace App\SpotlightCommands;

use App\Models\Vehicle;
use Illuminate\Http\Request;
use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;

class VehicleIndexCommand extends SpotlightCommand
{
    protected string $name = 'Fahrzeugeliste anzeigen';

    protected string $description = 'Alle Fahrzeuge anzeigen';

    protected array $synonyms = [
        'Autos',
    ];

    public function execute(Spotlight $spotlight): void
    {
        $spotlight->redirect(route('vehicles.index'));
    }

    public function shouldBeShown(Request $request): bool
    {
        return $request->user()->can('viewAny', Vehicle::class);
    }
}
