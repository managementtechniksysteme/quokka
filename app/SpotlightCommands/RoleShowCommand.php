<?php

namespace App\SpotlightCommands;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;
use LivewireUI\Spotlight\SpotlightCommandDependencies;
use LivewireUI\Spotlight\SpotlightCommandDependency;
use LivewireUI\Spotlight\SpotlightSearchResult;
use Spatie\Permission\Models\Role;

class RoleShowCommand extends SpotlightCommand
{
    protected string $name = 'Rolle anzeigen';

    protected string $description = 'Eine spezifische Berechtigungsrolle anzeigen';

    public function dependencies(): ?SpotlightCommandDependencies
    {
        return SpotlightCommandDependencies::collection()
            ->add(
                SpotlightCommandDependency::make('role')
                    ->setPlaceholder('Welche Rolle möchtest du anzeigen?')
                    ->setType(SpotlightCommandDependency::SEARCH)
            );
    }

    public function searchRole(string $query): Collection
    {
        return Role::where('name', 'LIKE', "%$query%")
            ->orderBy('name')
            ->get()
            ->map(function (Role $role) {
                return new SpotlightSearchResult(
                    $role->id,
                    $role->name,
                    ''
                );
            });
    }

    public function execute(Spotlight $spotlight, Role $role): void
    {
        $spotlight->redirect(route('roles.show', $role));
    }

    public function shouldBeShown(Request $request): bool
    {
        return $request->user()->can('viewAny', Role::class);
    }
}
