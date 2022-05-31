<?php

namespace App\SpotlightCommands;

use App\Models\ConstructionReport;
use Illuminate\Http\Request;
use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;

class ConstructionReportCreateCommand extends SpotlightCommand
{
    protected string $name = 'Bautagesbericht anlegen';

    protected string $description = 'Einen neuen Bautagesbericht anlegen';

    protected array $synonyms = [
        'erstellen',
        'hinzufügen',
    ];

    public function execute(Spotlight $spotlight): void
    {
        $spotlight->redirect(route('construction-reports.create'));
    }

    public function shouldBeShown(Request $request): bool
    {
        return $request->user()->can('create', ConstructionReport::class);
    }
}
