<?php

namespace App\SpotlightCommands;

use App\Models\Employee;
use Illuminate\Http\Request;
use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;

class EmployeeIndexCommand extends SpotlightCommand
{
    protected string $name = 'Mitarbeiterliste anzeigen';

    protected string $description = 'Alle Mitarbeiter anzeigen';

    public function execute(Spotlight $spotlight): void
    {
        $spotlight->redirect(route('employees.index'));
    }

    public function shouldBeShown(Request $request): bool
    {
        return $request->user()->can('viewAny', Employee::class);
    }
}
