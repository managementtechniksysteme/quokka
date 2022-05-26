<?php

namespace App\SpotlightCommands;

use App\Models\WageService;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;
use LivewireUI\Spotlight\SpotlightCommandDependencies;
use LivewireUI\Spotlight\SpotlightCommandDependency;
use LivewireUI\Spotlight\SpotlightSearchResult;

class WageServiceShowCommand extends SpotlightCommand
{
    protected string $name = 'Lohndienstleistung anzeigen';

    protected string $description = 'Eine spezifische Lohndienstleistung anzeigen';

    public function dependencies(): ?SpotlightCommandDependencies
    {
        return SpotlightCommandDependencies::collection()
            ->add(
                SpotlightCommandDependency::make('wageService')
                    ->setPlaceholder('Welche Lohndienstleistung?')
                    ->setType(SpotlightCommandDependency::SEARCH)
            );
    }

    public function searchWageService(string $query): Collection
    {
        return WageService::filterSearch($query)
            ->order()
            ->get()
            ->map(function (WageService $wageService) {
                return new SpotlightSearchResult(
                    $wageService->id,
                    $wageService->name_with_unit,
                    ''
                );
            });
    }

    public function execute(Spotlight $spotlight, WageService $wageService): void
    {
        $spotlight->redirect(route('wage-services.show', $wageService));
    }

    public function shouldBeShown(Request $request): bool
    {
        return $request->user()->can('viewAny', WageService::class);
    }
}
