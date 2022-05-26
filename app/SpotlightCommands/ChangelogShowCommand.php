<?php

namespace App\SpotlightCommands;

use Illuminate\Http\Request;
use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;

class ChangelogShowCommand extends SpotlightCommand
{
    protected string $name = 'Versionshinweise';

    protected string $description = '';

    protected array $synonyms = [
        'changelog',
        'Änderungen',
    ];

    public function __construct()
    {
        $this->description = 'Die ' . config('app.name') . ' Versionshinweise anzeigen';
    }

    public function execute(Spotlight $spotlight): void
    {
        $spotlight->redirect(route('changelog.show'));
    }

    public function shouldBeShown(Request $request): bool
    {
        return $request->user()->can('help-view');
    }
}
