<?php

namespace App\SpotlightCommands;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;
use LivewireUI\Spotlight\SpotlightCommandDependencies;
use LivewireUI\Spotlight\SpotlightCommandDependency;
use LivewireUI\Spotlight\SpotlightSearchResult;

class AccountingDownloadMonthlyReportCommand extends SpotlightCommand
{
    protected string $name = 'Monatsauswertung erstellen';

    protected string $description = 'Die monatliche Auswertung für die Buchhaltung erstellen';

    protected int $pastMonths = 3;

    protected array $synonyms = [
        'Abrechnung',
        'Buchhaltung',
    ];

    public function dependencies(): ?SpotlightCommandDependencies
    {
        $currentMonth = Carbon::today()->firstOfMonth();
        $startMonth = Carbon::make(Carbon::today()->firstOfMonth()->subMonthsNoOverflow($this->pastMonths-1));

        return SpotlightCommandDependencies::collection()
            ->add(
                SpotlightCommandDependency::make('month')
                    ->setPlaceholder("Welches Monat? (von {$startMonth->translatedFormat('M Y')} bis {$currentMonth->translatedFormat('M Y')})")
                    ->setType(SpotlightCommandDependency::SEARCH)
            );
    }

    public function searchMonth(string $query): Collection
    {
        $months = [];

        $firstOfMonth = Carbon::today()->firstOfMonth();

        for($i=0; $i<$this->pastMonths; $i++) {
            $months[] = Carbon::make($firstOfMonth);
            $firstOfMonth->subMonthNoOverflow();
        }

        return collect(
            array_map(function ($firstOfMonth) {
                return new SpotlightSearchResult(
                    $firstOfMonth->format('Y-m-d'),
                    $firstOfMonth->translatedFormat('F Y'),
                    ''
                );
            }, $months)
        );
    }

    public function execute(Request $request, Spotlight $spotlight, string $month): void
    {
        $startDate = Carbon::parse($month);
        $endDate = Carbon::make($startDate)->lastOfMonth();

        $spotlight->redirect(
            route('accounting.download', [
                'employee_ids[]' => $request->user(),
                'start' => $startDate->format('Y-m-d'),
                'end' => $endDate->format('Y-m-d'),
            ])
        );
    }

    public function shouldBeShown(Request $request): bool
    {
        return $request->user()->can('accounting.view.own');
    }
}
