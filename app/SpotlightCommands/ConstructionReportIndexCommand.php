<?php

namespace App\SpotlightCommands;

use App\Models\ConstructionReport;
use Illuminate\Http\Request;
use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;

class ConstructionReportIndexCommand extends SpotlightCommand
{
    protected string $name = 'Bautagesberichteliste anzeigen';

    protected string $description = 'Alle Bautagesberichte anzeigen';

    public function execute(Spotlight $spotlight): void
    {
        $spotlight->redirect(route('construction-reports.index'));
    }

    public function shouldBeShown(Request $request): bool
    {
        return $request->user()->can('viewAny', ConstructionReport::class);
    }
}
