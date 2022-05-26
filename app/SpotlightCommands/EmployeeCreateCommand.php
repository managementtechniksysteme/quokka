<?php

namespace App\SpotlightCommands;

use App\Models\Employee;
use Illuminate\Http\Request;
use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;

class EmployeeCreateCommand extends SpotlightCommand
{
    protected string $name = 'Mitarbeiter anlegen';

    protected string $description = 'Einen neuen Mitarbeiter anlegen';

    protected array $synonyms = [
        'erstellen',
        'hinzufügen',
    ];

    public function execute(Spotlight $spotlight): void
    {
        $spotlight->redirect(route('employees.create'));
    }

    public function shouldBeShown(Request $request): bool
    {
        return $request->user()->can('create', Employee::class);
    }
}
