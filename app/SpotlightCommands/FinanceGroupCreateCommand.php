<?php

namespace App\SpotlightCommands;

use App\Models\FinanceGroup;
use Illuminate\Http\Request;
use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;

class FinanceGroupCreateCommand extends SpotlightCommand
{
    protected string $name = 'Finanzgruppe anlegen';

    protected string $description = 'Ein neue Finanzgruppe anlegen';

    protected array $synonyms = [
        'erstellen',
        'hinzufügen',
    ];

    public function execute(Spotlight $spotlight): void
    {
        $spotlight->redirect(route('finance-groups.create'));
    }

    public function shouldBeShown(Request $request): bool
    {
        return $request->user()->can('create', FinanceGroup::class);
    }
}
