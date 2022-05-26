<?php

namespace App\SpotlightCommands;

use App\Models\InspectionReport;
use Illuminate\Http\Request;
use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;

class InspectionReportCreateCommand extends SpotlightCommand
{
    protected string $name = 'Prüfbericht anlegen';

    protected string $description = 'Einen neuen Prüfbericht anlegen';

    protected array $synonyms = [
        'erstellen',
        'hinzufügen',
    ];

    public function execute(Spotlight $spotlight): void
    {
        $spotlight->redirect(route('inspection-reports.create'));
    }

    public function shouldBeShown(Request $request): bool
    {
        return $request->user()->can('create', InspectionReport::class);
    }
}
