<?php

namespace App\SpotlightCommands;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;
use LivewireUI\Spotlight\SpotlightCommandDependencies;
use LivewireUI\Spotlight\SpotlightCommandDependency;
use LivewireUI\Spotlight\SpotlightSearchResult;

class ExceptionShowCommand extends SpotlightCommand
{
    private const LOGFOLDER = 'exceptions';

    protected string $name = 'Fehlerdatei anzeigen';

    protected string $description = 'Eine spezifische Fehlerdatei anzeigen';

    protected array $synonyms = [
        'exception',
        'Server',
    ];

    public function dependencies(): ?SpotlightCommandDependencies
    {
        return SpotlightCommandDependencies::collection()
            ->add(
                SpotlightCommandDependency::make('exception')
                    ->setPlaceholder('Welche Fehlerdatei möchtest du anzeigen?')
                    ->setType(SpotlightCommandDependency::SEARCH)
            );
    }

    public function searchException(string $query): Collection
    {
        $files = File::files(Storage::path(self::LOGFOLDER));

        return collect(
            array_map(function ($element) {
                $uuid = $element->getBasename('.log');

                return new SpotlightSearchResult(
                    $uuid,
                    $uuid,
                    (new Carbon($element->getMTime()))->setTimezone(config('app.timezone'))->format('d.m.Y H:i:s')
                );
            }, $files)
        );
    }

    public function execute(Spotlight $spotlight, string $exception): void
    {
        $spotlight->redirect(route('exceptions.show', $exception));
    }

    public function shouldBeShown(Request $request): bool
    {
        return $request->user()->can('tools-viewexceptions');
    }
}
