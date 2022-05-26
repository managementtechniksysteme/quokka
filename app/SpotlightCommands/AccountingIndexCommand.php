<?php

namespace App\SpotlightCommands;

use App\Models\Accounting;
use Illuminate\Http\Request;
use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;

class AccountingIndexCommand extends SpotlightCommand
{
    protected string $name = 'Leistungen abrechnen';

    protected string $description = 'Leistungen abrechnen oder anzeigen';

    protected array $synonyms = [
        'Liste',
    ];

    public function execute(Spotlight $spotlight): void
    {
        $spotlight->redirect(route('accounting.index'));
    }

    public function shouldBeShown(Request $request): bool
    {
        return $request->user()->can('viewAny', Accounting::class);
    }
}
