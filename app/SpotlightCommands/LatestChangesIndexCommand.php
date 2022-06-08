<?php

namespace App\SpotlightCommands;

use Illuminate\Http\Request;
use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;

class LatestChangesIndexCommand extends SpotlightCommand
{
    protected string $name = 'Letzte Änderungen anzeigen';

    protected string $description = 'Die zuletzt geänderten Elemente anzeigen';

    public function execute(Spotlight $spotlight): void
    {
        $spotlight->redirect(route('latest-changes.index'));
    }

    public function shouldBeShown(Request $request): bool
    {
        return $request->user()->can('tools-viewlatestchanges');
    }
}
