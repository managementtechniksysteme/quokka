<?php

namespace App\SpotlightCommands;

use Illuminate\Http\Request;
use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;

class HelpIndexCommand extends SpotlightCommand
{
    protected string $name = 'Hilfe anzeigen';

    protected string $description = '';

    public function __construct()
    {
        $this->description = 'Die ' . config('app.name') . ' Hilfe anzeigen';
    }

    public function execute(Spotlight $spotlight): void
    {
        $spotlight->redirect(route('help.index'));
    }

    public function shouldBeShown(Request $request): bool
    {
        return $request->user()->can('help-view');
    }
}
