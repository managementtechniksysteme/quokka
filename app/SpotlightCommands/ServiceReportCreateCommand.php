<?php

namespace App\SpotlightCommands;

use App\Models\ServiceReport;
use Illuminate\Http\Request;
use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;

class ServiceReportCreateCommand extends SpotlightCommand
{
    protected string $name = 'Servicebericht anlegen';

    protected string $description = 'Einen neuen Servicebericht anlegen';

    protected array $synonyms = [
        'erstellen',
        'hinzufügen',
    ];

    public function execute(Spotlight $spotlight): void
    {
        $spotlight->redirect(route('service-reports.create'));
    }

    public function shouldBeShown(Request $request): bool
    {
        return $request->user()->can('create', ServiceReport::class);
    }
}
