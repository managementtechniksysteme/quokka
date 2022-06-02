<?php

namespace App\SpotlightCommands;

use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;
use LivewireUI\Spotlight\SpotlightCommandDependencies;
use LivewireUI\Spotlight\SpotlightCommandDependency;
use LivewireUI\Spotlight\SpotlightSearchResult;

class PersonShowCommand extends SpotlightCommand
{
    protected string $name = 'Person anzeigen';

    protected string $description = 'Eine spezifische Person anzeigen';

    public function dependencies(): ?SpotlightCommandDependencies
    {
        return SpotlightCommandDependencies::collection()
            ->add(
                SpotlightCommandDependency::make('person')
                    ->setPlaceholder('Welche Person möchtest du anzeigen?')
                    ->setType(SpotlightCommandDependency::SEARCH)
            );
    }

    public function searchPerson(string $query): Collection
    {
        return Person::filterSearch($query)
            ->order()
            ->with('company')
            ->get()
            ->map(function (Person $person) {
                return new SpotlightSearchResult(
                    $person->id,
                    $person->name,
                    optional($person->company)->name ?? ''
                );
            });
    }

    public function execute(Spotlight $spotlight, Person $person): void
    {
        $spotlight->redirect(route('people.show', $person));
    }

    public function shouldBeShown(Request $request): bool
    {
        return $request->user()->can('viewAny', Person::class);
    }
}
