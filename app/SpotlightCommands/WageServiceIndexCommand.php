<?php

namespace App\SpotlightCommands;

use App\Models\WageService;
use Illuminate\Http\Request;
use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;

class WageServiceIndexCommand extends SpotlightCommand
{
    protected string $name = 'Lohndienstleistungenliste anzeigen';

    protected string $description = 'Alle Lohndienstleistungen anzeigen';

    public function execute(Spotlight $spotlight): void
    {
        $spotlight->redirect(route('wage-services.index'));
    }

    public function shouldBeShown(Request $request): bool
    {
        return $request->user()->can('viewAny', WageService::class);
    }
}
