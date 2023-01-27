<?php

namespace App\SpotlightCommands;

use App\Models\Note;
use Illuminate\Http\Request;
use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;

class NoteIndexCommand extends SpotlightCommand
{
    protected string $name = 'Notizbuch anzeigen';

    protected string $description = 'Alle Notizen anzeigen';

    public function execute(Spotlight $spotlight): void
    {
        $spotlight->redirect(route('notes.index'));
    }

    public function shouldBeShown(Request $request): bool
    {
        return $request->user()->can('viewAny', Note::class);
    }
}
