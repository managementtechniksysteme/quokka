<?php

namespace App\SpotlightCommands;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;
use LivewireUI\Spotlight\SpotlightCommandDependencies;
use LivewireUI\Spotlight\SpotlightCommandDependency;
use LivewireUI\Spotlight\SpotlightSearchResult;

class TaskShowCommand extends SpotlightCommand
{
    protected string $name = 'Aufgabe anzeigen';

    protected string $description = 'Eine spezifische Aufgabe anzeigen';

    public function dependencies(): ?SpotlightCommandDependencies
    {
        return SpotlightCommandDependencies::collection()
            ->add(
                SpotlightCommandDependency::make('task')
                    ->setPlaceholder('Welche Aufgabe?')
                    ->setType(SpotlightCommandDependency::SEARCH)
            );
    }

    public function searchTask(string $query): Collection
    {
        return Task::filterPermissions()
            ->filterSearch($query)
            ->order()
            ->with('project')
            ->get()
            ->map(function (Task $task) {
                return new SpotlightSearchResult(
                    $task->id,
                    $task->name,
                    $task->project->name
                );
            });
    }

    public function execute(Spotlight $spotlight, Task $task): void
    {
        $spotlight->redirect(route('tasks.show', $task));
    }

    public function shouldBeShown(Request $request): bool
    {
        return $request->user()->can('viewAny', Task::class);
    }
}
