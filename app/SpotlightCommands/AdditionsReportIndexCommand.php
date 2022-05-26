<?php

namespace App\SpotlightCommands;

use App\Models\AdditionsReport;
use Illuminate\Http\Request;
use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;

class AdditionsReportIndexCommand extends SpotlightCommand
{
    protected string $name = 'Regieberichteliste anzeigen';

    protected string $description = 'Alle Regieberichte anzeigen';

    public function execute(Spotlight $spotlight): void
    {
        $spotlight->redirect(route('additions-reports.index'));
    }

    public function shouldBeShown(Request $request): bool
    {
        return $request->user()->can('viewAny', AdditionsReport::class);
    }
}
