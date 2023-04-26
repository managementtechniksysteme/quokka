<?php

namespace App\SpotlightCommands;

use App\Models\DeliveryNote;
use Illuminate\Http\Request;
use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;

class DeliveryNoteCreateCommand extends SpotlightCommand
{
    protected string $name = 'Lieferschein anlegen';

    protected string $description = 'Einen neuen Lieferschein anlegen';

    protected array $synonyms = [
        'erstellen',
        'hinzufügen',
    ];

    public function execute(Spotlight $spotlight): void
    {
        $spotlight->redirect(route('delivery-notes.create'));
    }

    public function shouldBeShown(Request $request): bool
    {
        return $request->user()->can('create', DeliveryNote::class);
    }
}
