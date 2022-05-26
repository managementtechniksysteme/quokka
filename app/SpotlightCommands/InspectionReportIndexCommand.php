<?php

namespace App\SpotlightCommands;

use App\Models\InspectionReport;
use Illuminate\Http\Request;
use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;

class InspectionReportIndexCommand extends SpotlightCommand
{
    protected string $name = 'Prüfberichteliste anzeigen';

    protected string $description = 'Alle Prüfberichte anzeigen';

    public function execute(Spotlight $spotlight): void
    {
        $spotlight->redirect(route('inspection-reports.index'));
    }

    public function shouldBeShown(Request $request): bool
    {
        return $request->user()->can('viewAny', InspectionReport::class);
    }
}
