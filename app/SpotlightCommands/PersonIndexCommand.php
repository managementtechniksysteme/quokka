<?php

namespace App\SpotlightCommands;

use App\Models\Person;
use Illuminate\Http\Request;
use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;

class PersonIndexCommand extends SpotlightCommand
{
    protected string $name = 'Personenliste anzeigen';

    protected string $description = 'Alle Personen anzeigen';

    public function execute(Spotlight $spotlight): void
    {
        $spotlight->redirect(route('people.index'));
    }

    public function shouldBeShown(Request $request): bool
    {
        return $request->user()->can('viewAny', Person::class);
    }
}
