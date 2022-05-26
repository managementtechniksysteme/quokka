<?php

namespace App\SpotlightCommands;

use App\Models\ServiceReport;
use Illuminate\Http\Request;
use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;

class ServiceReportIndexCommand extends SpotlightCommand
{
    protected string $name = 'Serviceberichteliste anzeigen';

    protected string $description = 'Alle Serviceberichte anzeigen';

    public function execute(Spotlight $spotlight): void
    {
        $spotlight->redirect(route('service-reports.index'));
    }

    public function shouldBeShown(Request $request): bool
    {
        return $request->user()->can('viewAny', ServiceReport::class);
    }
}
