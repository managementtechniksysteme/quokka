<?php

namespace App\SpotlightCommands;

use App\Models\FlowMeterInspectionReport;
use Illuminate\Http\Request;
use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;

class FlowMeterInspectionReportIndexCommand extends SpotlightCommand
{
    protected string $name = 'Prüfberichteliste für Durchflussmesseinrichtungen anzeigen';

    protected string $description = 'Alle Prüfberichte für Durchflussmesseinrichtungen anzeigen';

    public function execute(Spotlight $spotlight): void
    {
        $spotlight->redirect(route('flow-meter-inspection-reports.index'));
    }

    public function shouldBeShown(Request $request): bool
    {
        return $request->user()->can('viewAny', FlowMeterInspectionReport::class);
    }
}
