<?php

namespace App\SpotlightCommands;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;
use LivewireUI\Spotlight\SpotlightCommandDependencies;
use LivewireUI\Spotlight\SpotlightCommandDependency;
use LivewireUI\Spotlight\SpotlightSearchResult;

class CompanyShowCommand extends SpotlightCommand
{
    protected string $name = 'Firma anzeigen';

    protected string $description = 'Eine spezifische Firma anzeigen';

    public function dependencies(): ?SpotlightCommandDependencies
    {
        return SpotlightCommandDependencies::collection()
            ->add(
                SpotlightCommandDependency::make('company')
                    ->setPlaceholder('Welche Firma möchtest du anzeigen?')
                    ->setType(SpotlightCommandDependency::SEARCH)
            );
    }

    public function searchCompany(string $query): Collection
    {
        return Company::filterSearch($query)
            ->order()
            ->get()
            ->map(function (Company $company) {
                return new SpotlightSearchResult(
                    $company->id,
                    $company->name,
                    ''
                );
            });
    }

    public function execute(Spotlight $spotlight, Company $company): void
    {
        $spotlight->redirect(route('companies.show', $company));
    }

    public function shouldBeShown(Request $request): bool
    {
        return $request->user()->can('viewAny', Company::class);
    }
}
