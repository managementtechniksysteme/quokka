<?php

namespace App\SpotlightCommands;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;
use LivewireUI\Spotlight\SpotlightCommandDependencies;
use LivewireUI\Spotlight\SpotlightCommandDependency;
use LivewireUI\Spotlight\SpotlightSearchResult;

class EmployeeStartImpersonationCommand extends SpotlightCommand
{
    protected string $name = 'Als Benutzer anmelden';

    protected string $description = '';

    public function __construct()
    {
        $this->description = 'Als anderer ' . config('app.name') . ' Benutzer anmelden';
    }

    public function dependencies(): ?SpotlightCommandDependencies
    {
        return SpotlightCommandDependencies::collection()
            ->add(
                SpotlightCommandDependency::make('employee')
                    ->setPlaceholder('Welcher Mitarbeiter?')
                    ->setType(SpotlightCommandDependency::SEARCH)
            );
    }

    public function searchEmployee(string $query): Collection
    {
        $exludedIds = [
            Session::has('impersonatorId') ? Session::get('impersonatorId') : Auth::id(),
        ];

        if(Session::has('impersonatorId')) {
            $exludedIds[] = Auth::id();
        }

        return Employee::filterSearch($query)
            ->whereNotIn('person_id', $exludedIds)
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
        $spotlight->redirect(route('employees.impersonate', $employee));
    }

    public function shouldBeShown(Request $request): bool
    {
        return $request->user()->can('employees.impersonate');
    }
}
