<?php

namespace App\SpotlightCommands;

use App\Models\FinanceGroup;
use Illuminate\Http\Request;
use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;

class FinanceGroupIndexCommand extends SpotlightCommand
{
    protected string $name = 'Finanzgruppenliste anzeigen';

    protected string $description = 'Alle Finanzgruppen anzeigen';

    public function execute(Spotlight $spotlight): void
    {
        $spotlight->redirect(route('finance-groups.index'));
    }

    public function shouldBeShown(Request $request): bool
    {
        return $request->user()->can('viewAny', FinanceGroup::class);
    }
}
