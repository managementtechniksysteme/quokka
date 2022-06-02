<?php

namespace App\SpotlightCommands;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;
use LivewireUI\Spotlight\SpotlightCommandDependencies;
use LivewireUI\Spotlight\SpotlightCommandDependency;
use LivewireUI\Spotlight\SpotlightSearchResult;

class EmployeeShowCommand extends SpotlightCommand
{
    protected string $name = 'Mitarbeiter anzeigen';

    protected string $description = 'Einen spezifischen Mitarbeiter anzeigen';

    public function dependencies(): ?SpotlightCommandDependencies
    {
        return SpotlightCommandDependencies::collection()
            ->add(
                SpotlightCommandDependency::make('employee')
                    ->setPlaceholder('Welchen Mitarbeiter möchtest du anzeigen?')
                    ->setType(SpotlightCommandDependency::SEARCH)
            );
    }

    public function searchEmployee(string $query): Collection
    {
        return Employee::filterSearch($query)
            ->with('person')
            ->get()
            ->map(function (Employee $employee) {
                return new SpotlightSearchResult(
                    $employee->person_id,
                    $employee->person->name,
                    ''
                );
            });
    }

    public function execute(Spotlight $spotlight, Employee $employee): void
    {
        $spotlight->redirect(route('employees.show', $employee));
    }

    public function shouldBeShown(Request $request): bool
    {
        return $request->user()->can('viewAny', Employee::class);
    }
}
