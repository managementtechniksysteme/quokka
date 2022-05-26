<?php

namespace App\SpotlightCommands;

use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;

class UserSettingsEditSecurityCommand extends SpotlightCommand
{
    protected string $name = 'Sicherheitseinstellungen bearbeiten';

    protected string $description = 'Deine persönlichen Sicherheitseinstellungen bearbeiten';

    protected array $synonyms = [
        'Passwort',
        'Zwei Faktor Authentisierung',
    ];

    public function execute(Spotlight $spotlight): void
    {
        $spotlight->redirect(route('user-settings.edit', ['tab' => 'security']));
    }
}
