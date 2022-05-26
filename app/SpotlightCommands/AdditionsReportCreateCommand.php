<?php

namespace App\SpotlightCommands;

use App\Models\AdditionsReport;
use Illuminate\Http\Request;
use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;

class AdditionsReportCreateCommand extends SpotlightCommand
{
    protected string $name = 'Regiebericht anlegen';

    protected string $description = 'Einen neuen Regiebericht anlegen';

    protected array $synonyms = [
        'erstellen',
        'hinzufügen',
    ];

    public function execute(Spotlight $spotlight): void
    {
        $spotlight->redirect(route('additions-reports.create'));
    }

    public function shouldBeShown(Request $request): bool
    {
        return $request->user()->can('create', AdditionsReport::class);
    }
}
