<?php

namespace App\SpotlightCommands;

use App\Models\Task;
use Illuminate\Http\Request;
use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;

class TaskCreateCommand extends SpotlightCommand
{
    protected string $name = 'Aufgabe anlegen';

    protected string $description = 'Eine neue Aufgabe anlegen';

    protected array $synonyms = [
        'erstellen',
        'hinzufügen',
    ];

    public function execute(Spotlight $spotlight): void
    {
        $spotlight->redirect(route('tasks.create'));
    }

    public function shouldBeShown(Request $request): bool
    {
        return $request->user()->can('create', Task::class);
    }
}
