<?php

namespace App\SpotlightCommands;

use App\Models\FlowMeterInspectionReport;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;
use LivewireUI\Spotlight\SpotlightCommandDependencies;
use LivewireUI\Spotlight\SpotlightCommandDependency;
use LivewireUI\Spotlight\SpotlightSearchResult;

class FlowMeterInspectionReportShowCommand extends SpotlightCommand
{
    protected string $name = 'Prüfbericht für Durchflussmesseinrichtungen anzeigen';

    protected string $description = 'Einen spezifisches Prüfbericht für Durchflussmesseinrichtungen anzeigen';

    public function dependencies(): ?SpotlightCommandDependencies
    {
        return SpotlightCommandDependencies::collection()
            ->add(
                SpotlightCommandDependency::make('flowMeterInspectionReport')
                    ->setPlaceholder('Welchen Prüfbericht für Durchflussmesseinrichtungen möchtest du anzeigen?')
                    ->setType(SpotlightCommandDependency::SEARCH)
            );
    }

    public function searchFlowMeterInspectionReport(string $query): Collection
    {
        return FlowMeterInspectionReport::filterPermissions()
            ->filterSearch($query)
            ->order()
            ->with('project')
            ->get()
            ->map(function (FlowMeterInspectionReport $flowMeterInspectionReport) {
                return new SpotlightSearchResult(
                    $flowMeterInspectionReport->id,
                    "$flowMeterInspectionReport->equipment_identifier vom $inspectionReport->inspected_on",
                    $flowMeterInspectionReport->project->name
                );
            });
    }

    public function execute(Spotlight $spotlight, FlowMeterInspectionReport $flowMeterInspectionReport): void
    {
        $spotlight->redirect(route('flow-meter-inspection-reports.show', $flowMeterInspectionReport));
    }

    public function shouldBeShown(Request $request): bool
    {
        return $request->user()->can('viewAny', FlowMeterInspectionReport::class);
    }
}
