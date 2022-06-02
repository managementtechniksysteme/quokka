<?php

namespace App\SpotlightCommands;

use App\Models\InspectionReport;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;
use LivewireUI\Spotlight\SpotlightCommandDependencies;
use LivewireUI\Spotlight\SpotlightCommandDependency;
use LivewireUI\Spotlight\SpotlightSearchResult;

class InspectionReportShowCommand extends SpotlightCommand
{
    protected string $name = 'Prüfbericht anzeigen';

    protected string $description = 'Einen spezifisches Prüfbericht anzeigen';

    public function dependencies(): ?SpotlightCommandDependencies
    {
        return SpotlightCommandDependencies::collection()
            ->add(
                SpotlightCommandDependency::make('inspectionReport')
                    ->setPlaceholder('Welchen Prüfbericht möchtest du anzeigen?')
                    ->setType(SpotlightCommandDependency::SEARCH)
            );
    }

    public function searchInspectionReport(string $query): Collection
    {
        return InspectionReport::filterPermissions()
            ->filterSearch($query)
            ->order()
            ->with('project')
            ->get()
            ->map(function (InspectionReport $inspectionReport) {
                return new SpotlightSearchResult(
                    $inspectionReport->id,
                    "$inspectionReport->equipment_identifier vom $inspectionReport->inspected_on",
                    $inspectionReport->project->name
                );
            });
    }

    public function execute(Spotlight $spotlight, InspectionReport $inspectionReport): void
    {
        $spotlight->redirect(route('inspection-reports.show', $inspectionReport));
    }

    public function shouldBeShown(Request $request): bool
    {
        return $request->user()->can('viewAny', InspectionReport::class);
    }
}
