<?php

namespace App\SpotlightCommands;

use App\Models\MaterialService;
use Illuminate\Http\Request;
use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;

class MaterialServiceIndexCommand extends SpotlightCommand
{
    protected string $name = 'Materialleistungenliste anzeigen';

    protected string $description = 'Alle Materialleistungen anzeigen';

    public function execute(Spotlight $spotlight): void
    {
        $spotlight->redirect(route('material-services.index'));
    }

    public function shouldBeShown(Request $request): bool
    {
        return $request->user()->can('viewAny', MaterialService::class);
    }
}
