<?php

namespace App\SpotlightCommands;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;
use LivewireUI\Spotlight\SpotlightCommandDependencies;
use LivewireUI\Spotlight\SpotlightCommandDependency;
use LivewireUI\Spotlight\SpotlightSearchResult;

class ProjectShowCommand extends SpotlightCommand
{
    protected string $name = 'Projekt anzeigen';

    protected string $description = 'Ein spezifisches Projekt anzeigen';

    public function dependencies(): ?SpotlightCommandDependencies
    {
        return SpotlightCommandDependencies::collection()
            ->add(
                SpotlightCommandDependency::make('project')
                    ->setPlaceholder('Welches Projekt möchtest du anzeigen?')
                    ->setType(SpotlightCommandDependency::SEARCH)
            );
    }

    public function searchProject(string $query): Collection
    {
        return Project::filterSearch($query)
            ->order()
            ->with('company')
            ->get()
            ->map(function (Project $project) {
                return new SpotlightSearchResult(
                    $project->id,
                    $project->name,
                    $project->company->name
                );
            });
    }

    public function execute(Spotlight $spotlight, Project $project): void
    {
        $spotlight->redirect(route('projects.show', $project));
    }

    public function shouldBeShown(Request $request): bool
    {
        return $request->user()->can('viewAny', Project::class);
    }
}
