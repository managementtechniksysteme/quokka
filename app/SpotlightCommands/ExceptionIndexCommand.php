<?php

namespace App\SpotlightCommands;

use Illuminate\Http\Request;
use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;

class ExceptionIndexCommand extends SpotlightCommand
{
    protected string $name = 'Fehlerdateien anzeigen';

    protected string $description = 'Alle Fehlerdateien anzeigen';

    protected array $synonyms = [
        'exception',
        'Server',
    ];

    public function execute(Spotlight $spotlight): void
    {
        $spotlight->redirect(route('exceptions.index'));
    }

    public function shouldBeShown(Request $request): bool
    {
        return $request->user()->can('tools-viewexceptions');
    }
}
