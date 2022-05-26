<?php

namespace App\SpotlightCommands;

use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;

class HomeShowCommand extends SpotlightCommand
{
    protected string $name = 'Übersicht anzeigen';

    protected string $description = 'Deine persönliche Übersicht anzeigen';

    protected array $synonyms = [
        'dashboard',
    ];

    public function execute(Spotlight $spotlight): void
    {
        $spotlight->redirect(route('home'));
    }
}
