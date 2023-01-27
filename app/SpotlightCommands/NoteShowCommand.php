<?php

namespace App\SpotlightCommands;

use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;
use LivewireUI\Spotlight\SpotlightCommandDependencies;
use LivewireUI\Spotlight\SpotlightCommandDependency;
use LivewireUI\Spotlight\SpotlightSearchResult;

class NoteShowCommand extends SpotlightCommand
{
    protected string $name = 'Notiz anzeigen';

    protected string $description = 'Eine spezifische Notiz anzeigen';

    public function dependencies(): ?SpotlightCommandDependencies
    {
        return SpotlightCommandDependencies::collection()
            ->add(
                SpotlightCommandDependency::make('note')
                    ->setPlaceholder('Welche Notiz möchtest du anzeigen?')
                    ->setType(SpotlightCommandDependency::SEARCH)
            );
    }

    public function searchNote(string $query): Collection
    {
        return \Auth::user()->employee->notes()
            ->filterSearch($query)
            ->order()
            ->get()
            ->map(function (Note $note) {
                return new SpotlightSearchResult(
                    $note->id,
                    $note->title ?? $note->truncated_comment,
                    $note->title ? $note->truncated_comment : ''
                );
            });
    }

    public function execute(Spotlight $spotlight, Note $note): void
    {
        $spotlight->redirect(route('notes.show', $note));
    }

    public function shouldBeShown(Request $request): bool
    {
        return $request->user()->can('viewAny', Note::class);
    }
}
