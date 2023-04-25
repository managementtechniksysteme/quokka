<?php

namespace App\SpotlightCommands;

use App\Models\FinanceGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;
use LivewireUI\Spotlight\SpotlightCommandDependencies;
use LivewireUI\Spotlight\SpotlightCommandDependency;
use LivewireUI\Spotlight\SpotlightSearchResult;

class FinanceGroupShowCommand extends SpotlightCommand
{
    protected string $name = 'Finanzgruppe anzeigen';

    protected string $description = 'Eine spezifische Finanzgruppe anzeigen';

    public function dependencies(): ?SpotlightCommandDependencies
    {
        return SpotlightCommandDependencies::collection()
            ->add(
                SpotlightCommandDependency::make('financeGroup')
                    ->setPlaceholder('Welche Finanzgruppe möchtest du anzeigen?')
                    ->setType(SpotlightCommandDependency::SEARCH)
            );
    }

    public function searchFinanceGroup(string $query): Collection
    {
        return FinanceGroup::filterSearch($query)
            ->order()
            ->get()
            ->map(function (FinanceGroup $financeGroup) {
                return new SpotlightSearchResult(
                    $financeGroup->id,
                    $financeGroup->title,
                    ''
                );
            });
    }

    public function execute(Spotlight $spotlight, FinanceGroup $financeGroup): void
    {
        $spotlight->redirect(route('finance-groups.show', $financeGroup));
    }

    public function shouldBeShown(Request $request): bool
    {
        return $request->user()->can('viewAny', FinanceGroup::class);
    }
}
