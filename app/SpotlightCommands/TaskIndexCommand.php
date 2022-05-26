<?php

namespace App\SpotlightCommands;

use App\Models\Task;
use Illuminate\Http\Request;
use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;

class TaskIndexCommand extends SpotlightCommand
{
    protected string $name = 'Aufgabenliste anzeigen';

    protected string $description = 'Alle Aufgaben anzeigen';

    public function execute(Spotlight $spotlight): void
    {
        $spotlight->redirect(route('tasks.index'));
    }

    public function shouldBeShown(Request $request): bool
    {
        return $request->user()->can('viewAny', Task::class);
    }
}
