<?php

namespace App\SpotlightCommands;

use App\Models\Vehicle;
use Illuminate\Http\Request;
use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;

class VehicleCreateCommand extends SpotlightCommand
{
    protected string $name = 'Fahrzeug anlegen';

    protected string $description = 'Ein neues Fahrzeug anlegen';

    protected array $synonyms = [
        'Auto',
        'erstellen',
        'hinzufügen',
    ];

    public function execute(Spotlight $spotlight): void
    {
        $spotlight->redirect(route('vehicles.create'));
    }

    public function shouldBeShown(Request $request): bool
    {
        return $request->user()->can('create', Vehicle::class);
    }
}
