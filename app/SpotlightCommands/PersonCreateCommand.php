<?php

namespace App\SpotlightCommands;

use App\Models\Person;
use Illuminate\Http\Request;
use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;

class PersonCreateCommand extends SpotlightCommand
{
    protected string $name = 'Person anlegen';

    protected string $description = 'Eine neue Person anlegen';

    protected array $synonyms = [
        'erstellen',
        'hinzufügen',
    ];

    public function execute(Spotlight $spotlight): void
    {
        $spotlight->redirect(route('people.create'));
    }

    public function shouldBeShown(Request $request): bool
    {
        return $request->user()->can('create', Person::class);
    }
}
