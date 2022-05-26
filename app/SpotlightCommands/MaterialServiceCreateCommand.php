<?php

namespace App\SpotlightCommands;

use App\Models\MaterialService;
use Illuminate\Http\Request;
use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;

class MaterialServiceCreateCommand extends SpotlightCommand
{
    protected string $name = 'Materialleistung anlegen';

    protected string $description = 'Eine neue Materialleistung anlegen';

    protected array $synonyms = [
        'erstellen',
        'hinzufügen',
    ];

    public function execute(Spotlight $spotlight): void
    {
        $spotlight->redirect(route('material-services.create'));
    }

    public function shouldBeShown(Request $request): bool
    {
        return $request->user()->can('create', MaterialService::class);
    }
}
