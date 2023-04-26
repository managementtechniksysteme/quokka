<?php

namespace App\SpotlightCommands;

use App\Models\DeliveryNote;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;
use LivewireUI\Spotlight\SpotlightCommandDependencies;
use LivewireUI\Spotlight\SpotlightCommandDependency;
use LivewireUI\Spotlight\SpotlightSearchResult;

class DeliveryNoteShowCommand extends SpotlightCommand
{
    protected string $name = 'Lieferschein anzeigen';

    protected string $description = 'Einen spezifischen Lieferschein anzeigen';

    public function dependencies(): ?SpotlightCommandDependencies
    {
        return SpotlightCommandDependencies::collection()
            ->add(
                SpotlightCommandDependency::make('deliveryNote')
                    ->setPlaceholder('Welchen Lieferschein möchtest du anzeigen?')
                    ->setType(SpotlightCommandDependency::SEARCH)
            );
    }

    public function searchDeliveryNote(string $query): Collection
    {
        return DeliveryNote::filterSearch($query)
            ->order()
            ->with('project')
            ->get()
            ->map(function (DeliveryNote $deliveryNote) {
                return new SpotlightSearchResult(
                    $deliveryNote->id,
                    "$deliveryNote->title",
                    "Projekt {$deliveryNote->project->name}"
                );
            });
    }

    public function execute(Spotlight $spotlight, DeliveryNote $deliveryNote): void
    {
        $spotlight->redirect(route('delivery-notes.show', $deliveryNote));
    }

    public function shouldBeShown(Request $request): bool
    {
        return $request->user()->can('viewAny', DeliveryNote::class);
    }
}
