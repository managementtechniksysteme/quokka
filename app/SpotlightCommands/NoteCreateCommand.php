<?php

namespace App\SpotlightCommands;

use App\Models\Note;
use Illuminate\Http\Request;
use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;

class NoteCreateCommand extends SpotlightCommand
{
    protected string $name = 'Notiz anlegen';

    protected string $description = 'Eine neue Notiz anlegen';

    protected array $synonyms = [
        'erstellen',
        'hinzufügen',
    ];

    public function execute(Spotlight $spotlight): void
    {
        $spotlight->redirect(route('notes.create'));
    }

    public function shouldBeShown(Request $request): bool
    {
        return $request->user()->can('create', Note::class);
    }
}
