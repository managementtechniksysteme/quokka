<?php

namespace App\SpotlightCommands;

use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;

class UserSettingsEditCommand extends SpotlightCommand
{
    protected string $name = 'Benutzereinstellungen bearbeiten';

    protected string $description = 'Deine persönliche Einstellungen bearbeiten';

    public function execute(Spotlight $spotlight): void
    {
        $spotlight->redirect(route('user-settings.edit'));
    }
}
