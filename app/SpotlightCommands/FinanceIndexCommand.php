<?php

namespace App\SpotlightCommands;

use Illuminate\Http\Request;
use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;

class FinanceIndexCommand extends SpotlightCommand
{
    protected string $name = 'Finanzübersicht anzeigen';

    protected string $description = 'Die Finanzübersicht anzeigen';

    public function execute(Spotlight $spotlight): void
    {
        $spotlight->redirect(route('finances.index'));
    }

    public function shouldBeShown(Request $request): bool
    {
        return $request->user()->can('finances-view');
    }
}
