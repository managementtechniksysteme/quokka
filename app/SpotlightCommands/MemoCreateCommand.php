<?php

namespace App\SpotlightCommands;

use App\Models\Memo;
use Illuminate\Http\Request;
use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;

class MemoCreateCommand extends SpotlightCommand
{
    protected string $name = 'Aktenvermerk anlegen';

    protected string $description = 'Einen neuen Aktenvermerk anlegen';

    protected array $synonyms = [
        'erstellen',
        'hinzufügen',
    ];

    public function execute(Spotlight $spotlight): void
    {
        $spotlight->redirect(route('memos.create'));
    }

    public function shouldBeShown(Request $request): bool
    {
        return $request->user()->can('create', Memo::class);
    }
}
