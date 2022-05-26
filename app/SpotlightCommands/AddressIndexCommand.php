<?php

namespace App\SpotlightCommands;

use App\Models\Address;
use Illuminate\Http\Request;
use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;

class AddressIndexCommand extends SpotlightCommand
{
    protected string $name = 'Adressenliste anzeigen';

    protected string $description = 'Alle Adressen anzeigen';

    public function execute(Spotlight $spotlight): void
    {
        $spotlight->redirect(route('addresses.index'));
    }

    public function shouldBeShown(Request $request): bool
    {
        return $request->user()->can('viewAny', Address::class);
    }
}
