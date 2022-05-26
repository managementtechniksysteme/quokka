<?php

namespace App\SpotlightCommands;

use App\Models\WageService;
use Illuminate\Http\Request;
use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;

class WageServiceCreateCommand extends SpotlightCommand
{
    protected string $name = 'Lohndienstleistung anlegen';

    protected string $description = 'Eine neue Lohndienstleistung anlegen';

    protected array $synonyms = [
        'erstellen',
        'hinzufügen',
    ];

    public function execute(Spotlight $spotlight): void
    {
        $spotlight->redirect(route('wage-services.create'));
    }

    public function shouldBeShown(Request $request): bool
    {
        return $request->user()->can('create', WageService::class);
    }
}
