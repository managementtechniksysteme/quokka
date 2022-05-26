<?php

namespace App\SpotlightCommands;

use App\Models\Project;
use Illuminate\Http\Request;
use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;

class ProjectIndexCommand extends SpotlightCommand
{
    protected string $name = 'Projekteliste anzeigen';

    protected string $description = 'Alle Projekte anzeigen';

    public function execute(Spotlight $spotlight): void
    {
        $spotlight->redirect(route('projects.index'));
    }

    public function shouldBeShown(Request $request): bool
    {
        return $request->user()->can('viewAny', Project::class);
    }
}
