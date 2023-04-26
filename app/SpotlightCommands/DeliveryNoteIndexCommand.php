<?php

namespace App\SpotlightCommands;

use App\Models\DeliveryNote;
use Illuminate\Http\Request;
use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;

class DeliveryNoteIndexCommand extends SpotlightCommand
{
    protected string $name = 'Lieferscheineliste anzeigen';

    protected string $description = 'Alle Lieferscheine anzeigen';

    public function execute(Spotlight $spotlight): void
    {
        $spotlight->redirect(route('delivery-notes.index'));
    }

    public function shouldBeShown(Request $request): bool
    {
        return $request->user()->can('viewAny', DeliveryNote::class);
    }
}
