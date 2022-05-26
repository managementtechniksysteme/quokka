<?php

namespace App\SpotlightCommands;

use App\Models\Project;
use Illuminate\Http\Request;
use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;

class ProjectCreateCommand extends SpotlightCommand
{
    protected string $name = 'Projekt anlegen';

    protected string $description = 'Ein neues Projekt anlegen';

    protected array $synonyms = [
        'erstellen',
        'hinzufügen',
    ];

    public function execute(Spotlight $spotlight): void
    {
        $spotlight->redirect(route('projects.create'));
    }

    public function shouldBeShown(Request $request): bool
    {
        return $request->user()->can('create', Project::class);
    }
}
