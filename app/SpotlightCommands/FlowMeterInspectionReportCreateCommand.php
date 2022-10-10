<?php

namespace App\SpotlightCommands;

use App\Models\FlowMeterInspectionReport;
use Illuminate\Http\Request;
use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;

class FlowMeterInspectionReportCreateCommand extends SpotlightCommand
{
    protected string $name = 'Prüfbericht für Durchflussmessungen anlegen';

    protected string $description = 'Einen neuen Prüfbericht für Durchflussmesungen anlegen';

    protected array $synonyms = [
        'erstellen',
        'hinzufügen',
    ];

    public function execute(Spotlight $spotlight): void
    {
        $spotlight->redirect(route('flow-meter-inspection-reports.create'));
    }

    public function shouldBeShown(Request $request): bool
    {
        return $request->user()->can('create', FlowMeterInspectionReport::class);
    }
}
