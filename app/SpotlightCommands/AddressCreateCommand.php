<?php

namespace App\SpotlightCommands;

use App\Models\Address;
use Illuminate\Http\Request;
use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;

class AddressCreateCommand extends SpotlightCommand
{
    protected string $name = 'Adresse anlegen';

    protected string $description = 'Eine neue Adresse anlegen';

    protected array $synonyms = [
        'erstellen',
        'hinzufügen',
    ];

    public function execute(Spotlight $spotlight): void
    {
        $spotlight->redirect(route('addresses.create'));
    }

    public function shouldBeShown(Request $request): bool
    {
        return $request->user()->can('create', Address::class);
    }
}
