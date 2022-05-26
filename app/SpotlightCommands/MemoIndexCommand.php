<?php

namespace App\SpotlightCommands;

use App\Models\Memo;
use Illuminate\Http\Request;
use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;

class MemoIndexCommand extends SpotlightCommand
{
    protected string $name = 'Aktenvermerkeliste anzeigen';

    protected string $description = 'Alle Aktenvermerke anzeigen';

    public function execute(Spotlight $spotlight): void
    {
        $spotlight->redirect(route('memos.index'));
    }

    public function shouldBeShown(Request $request): bool
    {
        return $request->user()->can('viewAny', Memo::class);
    }
}
