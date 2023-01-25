<?php

namespace App\SpotlightCommands;

use App\Models\Memo;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;
use LivewireUI\Spotlight\SpotlightCommandDependencies;
use LivewireUI\Spotlight\SpotlightCommandDependency;
use LivewireUI\Spotlight\SpotlightSearchResult;

class MemoShowCommand extends SpotlightCommand
{
    protected string $name = 'Aktenvermerk anzeigen';

    protected string $description = 'Einen spezifischen Aktenvermerk anzeigen';

    public function dependencies(): ?SpotlightCommandDependencies
    {
        return SpotlightCommandDependencies::collection()
            ->add(
                SpotlightCommandDependency::make('memo')
                    ->setPlaceholder('Welchen Aktenvermerk möchtest du anzeigen?')
                    ->setType(SpotlightCommandDependency::SEARCH)
            );
    }

    public function searchMemo(string $query): Collection
    {
        return Memo::filterPermissions()
            ->filterSearch($query)
            ->order()
            ->with('project')
            ->get()
            ->map(function (Memo $memo) {
                return new SpotlightSearchResult(
                    $memo->id,
                    $memo->title,
                    "{$memo->project->name} #$memo->number"
                );
            });
    }

    public function execute(Spotlight $spotlight, Memo $memo): void
    {
        $spotlight->redirect(route('memos.show', $memo));
    }

    public function shouldBeShown(Request $request): bool
    {
        return $request->user()->can('viewAny', Memo::class);
    }
}
