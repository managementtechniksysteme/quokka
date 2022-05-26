<?php

namespace App\SpotlightCommands;

use App\Models\Company;
use Illuminate\Http\Request;
use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;

class CompanyCreateCommand extends SpotlightCommand
{
    protected string $name = 'Firma anlegen';

    protected string $description = 'Eine neue Firma anlegen';

    protected array $synonyms = [
        'erstellen',
        'hinzufügen',
    ];

    public function execute(Spotlight $spotlight): void
    {
        $spotlight->redirect(route('companies.create'));
    }

    public function shouldBeShown(Request $request): bool
    {
        return $request->user()->can('create', Company::class);
    }
}
