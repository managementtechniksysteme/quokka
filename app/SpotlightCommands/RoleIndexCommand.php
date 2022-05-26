<?php

namespace App\SpotlightCommands;

use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;

class RoleIndexCommand extends SpotlightCommand
{
    protected string $name = 'Rollenliste anzeigen';

    protected string $description = 'Alle Berechtigungsrollen anzeigen';

    public function execute(Spotlight $spotlight): void
    {
        $spotlight->redirect(route('roles.index'));
    }

    public function shouldBeShown(Request $request): bool
    {
        return $request->user()->can('viewAny', Role::class);
    }
}
