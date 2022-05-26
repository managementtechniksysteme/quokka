<?php

namespace App\SpotlightCommands;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;
use LivewireUI\Spotlight\SpotlightCommandDependencies;
use LivewireUI\Spotlight\SpotlightCommandDependency;
use LivewireUI\Spotlight\SpotlightSearchResult;

class HelpShowCommand extends SpotlightCommand
{
    protected string $name = 'Hilfethema anzeigen';

    protected string $description = 'Ein spezifisches Hilfethema anzeigen';

    public function dependencies(): ?SpotlightCommandDependencies
    {
        return SpotlightCommandDependencies::collection()
            ->add(
                SpotlightCommandDependency::make('topic')
                    ->setPlaceholder('Welches Thema?')
                    ->setType(SpotlightCommandDependency::SEARCH)
            );
    }

    public function searchTopic(string $query): Collection
    {
        $files = File::allFiles(resource_path('views/help/topic'));

        $names = array_map(function ($element) {
            return $element->getBasename('.blade.php');
        }, $files);

        $index = array_search('index', $names);

        if ($index !== false) {
            unset($names[$index]);
        }

        return collect(
            array_map(function ($name) {
                return new SpotlightSearchResult(
                    $name,
                    Str::title(trans($name)),
                    ''
                );
            }, $names)
        );
    }

    public function execute(Spotlight $spotlight, string $topic): void
    {
        $spotlight->redirect(route('help.show', $topic));
    }

    public function shouldBeShown(Request $request): bool
    {
        return $request->user()->can('help-view');
    }
}
