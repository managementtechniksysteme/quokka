<?php

namespace App\SpotlightCommands;

use Illuminate\Http\Request;
use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;

class ApplicationSettingsEditCommand extends SpotlightCommand
{
    protected string $name = 'Applikationseinstellungen bearbeiten';

    protected string $description = '';

    public function __construct()
    {
        $this->description = 'Die ' . config('app.name') . ' Einstellungen bearbeiten';
    }

    public function execute(Spotlight $spotlight): void
    {
        $spotlight->redirect(route('application-settings.edit'));
    }

    public function shouldBeShown(Request $request): bool
    {
        return $request->user()->can('application-settings-update');
    }
}
