<?php

namespace App\SpotlightCommands;

use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;

class NotificationIndexCommand extends SpotlightCommand
{
    protected string $name = 'Benachrichtigungen anzeigen';

    protected string $description = 'Deine Benachrichtigungen anzeigen';

    public function execute(Spotlight $spotlight): void
    {
        $spotlight->redirect(route('notifications.index'));
    }
}
