<?php

namespace App\SpotlightCommands;

use App\Models\AdditionsReport;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;
use LivewireUI\Spotlight\SpotlightCommandDependencies;
use LivewireUI\Spotlight\SpotlightCommandDependency;
use LivewireUI\Spotlight\SpotlightSearchResult;

class AdditionsReportShowCommand extends SpotlightCommand
{
    protected string $name = 'Regiebericht anzeigen';

    protected string $description = 'Einen spezifisches Regiebericht anzeigen';

    public function dependencies(): ?SpotlightCommandDependencies
    {
        return SpotlightCommandDependencies::collection()
            ->add(
                SpotlightCommandDependency::make('additionsReport')
                    ->setPlaceholder('Welchen Regiebericht möchtest du anzeigen?')
                    ->setType(SpotlightCommandDependency::SEARCH)
            );
    }

    public function searchAdditionsReport(string $query): Collection
    {
        return AdditionsReport::filterPermissions()
            ->filterSearch($query)
            ->order()
            ->with('project')
            ->get()
            ->map(function (AdditionsReport $additionsReport) {
                return new SpotlightSearchResult(
                    $additionsReport->id,
                    "{$additionsReport->project->name} #$additionsReport->number",
                    "vom $additionsReport->services_provided_on"
                );
            });
    }

    public function execute(Spotlight $spotlight, AdditionsReport $additionsReport): void
    {
        $spotlight->redirect(route('additions-reports.show', $additionsReport));
    }

    public function shouldBeShown(Request $request): bool
    {
        return $request->user()->can('viewAny', AdditionsReport::class);
    }
}
