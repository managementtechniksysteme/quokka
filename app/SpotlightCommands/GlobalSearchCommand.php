<?php

namespace App\SpotlightCommands;

use App\Support\GlobalSearch\GlobalSearch;
use App\Support\GlobalSearch\GlobalSearchResult;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;
use LivewireUI\Spotlight\SpotlightCommandDependencies;
use LivewireUI\Spotlight\SpotlightCommandDependency;
use LivewireUI\Spotlight\SpotlightSearchResult;

class GlobalSearchCommand extends SpotlightCommand
{
    protected string $name = 'Global suchen';

    protected string $description = '';

    protected array $synonyms = [
        'Suche',
        'finden',
    ];

    public function __construct()
    {
        $this->description = 'Global in ' . config('app.name') . ' suchen';
    }

    public function dependencies(): ?SpotlightCommandDependencies
    {
        return SpotlightCommandDependencies::collection()
            ->add(
                SpotlightCommandDependency::make('globalSearchResult')
                    ->setPlaceholder('Wonach möchtest du suchen?')
                    ->setType(SpotlightCommandDependency::SEARCH)
            );
    }

    public function searchGlobalSearchResult(string $query): Collection
    {
        return GlobalSearch::search($query)
            ->map(function (GlobalSearchResult $globalSearchResult) {
                return new SpotlightSearchResult(
                    $globalSearchResult->route,
                    $globalSearchResult->name,
                    $globalSearchResult->type
                );
            });
    }

    public function execute(Spotlight $spotlight, string $globalSearchResult): void
    {
        $spotlight->redirect($globalSearchResult);
    }

    public function shouldBeShown(Request $request): bool
    {
        return $request->user()->can('search');
    }
}
