<?php

namespace App\SpotlightCommands;

use App\Models\MaterialService;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;
use LivewireUI\Spotlight\SpotlightCommandDependencies;
use LivewireUI\Spotlight\SpotlightCommandDependency;
use LivewireUI\Spotlight\SpotlightSearchResult;

class MaterialServiceShowCommand extends SpotlightCommand
{
    protected string $name = 'Materialleistung anzeigen';

    protected string $description = 'Eine spezifische Materialleistung anzeigen';

    public function dependencies(): ?SpotlightCommandDependencies
    {
        return SpotlightCommandDependencies::collection()
            ->add(
                SpotlightCommandDependency::make('materialService')
                    ->setPlaceholder('Welche Materialleistung?')
                    ->setType(SpotlightCommandDependency::SEARCH)
            );
    }

    public function searchMaterialService(string $query): Collection
    {
        return MaterialService::filterSearch($query)
            ->order()
            ->get()
            ->map(function (MaterialService $materialService) {
                return new SpotlightSearchResult(
                    $materialService->id,
                    $materialService->name,
                    ''
                );
            });
    }

    public function execute(Spotlight $spotlight, MaterialService $materialService): void
    {
        $spotlight->redirect(route('material-services.show', $materialService));
    }

    public function shouldBeShown(Request $request): bool
    {
        return $request->user()->can('viewAny', MaterialService::class);
    }
}
