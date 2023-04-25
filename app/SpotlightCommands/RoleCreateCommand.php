<?php

namespace App\SpotlightCommands;

use Illuminate\Http\Request;
use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;
use Spatie\Permission\Models\Role;

class RoleCreateCommand extends SpotlightCommand
{
    protected string $name = 'Rolle anlegen';

    protected string $description = 'Eine neue Berechtigungsrolle anlegen';

    protected array $synonyms = [
        'erstellen',
        'hinzufügen',
    ];

    public function execute(Spotlight $spotlight): void
    {
        $spotlight->redirect(route('roles.create'));
    }

    public function shouldBeShown(Request $request): bool
    {
        return $request->user()->can('create', Role::class);
    }
}
