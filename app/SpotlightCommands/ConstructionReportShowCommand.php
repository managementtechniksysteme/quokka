<?php

namespace App\SpotlightCommands;

use App\Models\ConstructionReport;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;
use LivewireUI\Spotlight\SpotlightCommandDependencies;
use LivewireUI\Spotlight\SpotlightCommandDependency;
use LivewireUI\Spotlight\SpotlightSearchResult;

class ConstructionReportShowCommand extends SpotlightCommand
{
    protected string $name = 'Bautagesbericht anzeigen';

    protected string $description = 'Einen spezifisches Bautagesbericht anzeigen';

    public function dependencies(): ?SpotlightCommandDependencies
    {
        return SpotlightCommandDependencies::collection()
            ->add(
                SpotlightCommandDependency::make('constructionReport')
                    ->setPlaceholder('Welchen Bautagesbericht möchtest du anzeigen?')
                    ->setType(SpotlightCommandDependency::SEARCH)
            );
    }

    public function searchConstructionReport(string $query): Collection
    {
        return ConstructionReport::filterPermissions()
            ->filterSearch($query)
            ->order()
            ->with('project')
            ->get()
            ->map(function (ConstructionReport $constructionReport) {
                return new SpotlightSearchResult(
                    $constructionReport->id,
                    "{$constructionReport->project->name} #$constructionReport->number",
                    "vom $constructionReport->services_provided_on"
                );
            });
    }

    public function execute(Spotlight $spotlight, ConstructionReport $constructionReport): void
    {
        $spotlight->redirect(route('construction-reports.show', $constructionReport));
    }

    public function shouldBeShown(Request $request): bool
    {
        return $request->user()->can('viewAny', ConstructionReport::class);
    }
}
