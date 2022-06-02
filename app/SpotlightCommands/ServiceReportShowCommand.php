<?php

namespace App\SpotlightCommands;

use App\Models\ServiceReport;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;
use LivewireUI\Spotlight\SpotlightCommandDependencies;
use LivewireUI\Spotlight\SpotlightCommandDependency;
use LivewireUI\Spotlight\SpotlightSearchResult;

class ServiceReportShowCommand extends SpotlightCommand
{
    protected string $name = 'Servicebericht anzeigen';

    protected string $description = 'Einen spezifisches Servicebericht anzeigen';

    public function dependencies(): ?SpotlightCommandDependencies
    {
        return SpotlightCommandDependencies::collection()
            ->add(
                SpotlightCommandDependency::make('serviceReport')
                    ->setPlaceholder('Welchen Servicebericht möchtest du anzeigen?')
                    ->setType(SpotlightCommandDependency::SEARCH)
            );
    }

    public function searchServiceReport(string $query): Collection
    {
        return ServiceReport::filterPermissions()
            ->filterSearch($query)
            ->order()
            ->with('project')
            ->withMin('services', 'provided_on')
            ->withMax('services', 'provided_on')
            ->get()
            ->map(function (ServiceReport $serviceReport) {
                $start = Carbon::parse($serviceReport->services_min_provided_on);
                $end = Carbon::parse($serviceReport->services_max_provided_on);

                if($start->eq($end)) {
                    $description = "vom $start";
                } else {
                    $description = "von $start bis $end";
                }

                return new SpotlightSearchResult(
                    $serviceReport->id,
                    "{$serviceReport->project->name} #$serviceReport->number",
                    $description
                );
            });
    }

    public function execute(Spotlight $spotlight, ServiceReport $serviceReport): void
    {
        $spotlight->redirect(route('service-reports.show', $serviceReport));
    }

    public function shouldBeShown(Request $request): bool
    {
        return $request->user()->can('viewAny', ServiceReport::class);
    }
}
