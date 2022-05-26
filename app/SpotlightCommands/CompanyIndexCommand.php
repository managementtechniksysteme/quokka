<?php

namespace App\SpotlightCommands;

use App\Models\Company;
use Illuminate\Http\Request;
use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;

class CompanyIndexCommand extends SpotlightCommand
{
    protected string $name = 'Firmenliste anzeigen';

    protected string $description = 'Alle Firmen anzeigen';

    public function execute(Spotlight $spotlight): void
    {
        $spotlight->redirect(route('companies.index'));
    }

    public function shouldBeShown(Request $request): bool
    {
        return $request->user()->can('viewAny', Company::class);
    }
}
