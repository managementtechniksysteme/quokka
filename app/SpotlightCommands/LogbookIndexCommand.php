<?php

namespace App\SpotlightCommands;

use App\Models\Logbook;
use Illuminate\Http\Request;
use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;

class LogbookIndexCommand extends SpotlightCommand
{
    protected string $name = 'Fahrten eintragen';

    protected string $description = 'Fahrten eintragen oder anzeigen';

    protected array $synonyms = [
        'Liste',
    ];

    public function execute(Spotlight $spotlight): void
    {
        $spotlight->redirect(route('logbook.index'));
    }

    public function shouldBeShown(Request $request): bool
    {
        return $request->user()->can('viewAny', Logbook::class);
    }
}
