<?php

namespace App\Helpers;

use App\Models\FinanceGroup;
use App\Models\FinanceRecord;
use App\Models\Project;
use Carbon\Carbon;

class Finances
{
    public static function getCurrentlyOpenProjectsData(): array
    {
        $openProjects = Project::where('is_pre_execution', false)
            ->where(fn ($query) =>
                $query->whereNull('ends_on')
                    ->orWhere('ends_on', '>', Carbon::today())
            )
            ->get();

        $totalVolume = $openProjects->sum(fn($project) => $project->costs);

        $billedVolume = $openProjects->sum(fn($project) => -$project->current_billed_financial_costs);

        return [
            'total_volume' => $totalVolume,
            'billed_volume' => $billedVolume,
        ];
    }

    public static function getPreExecutionProjectsData(): array
    {
        $preExecutionProjects = Project::where('is_pre_execution', true)->get();

        $totalVolume = $preExecutionProjects->sum(fn($project) => $project->costs);

        $billedVolume = $preExecutionProjects->sum(fn($project) => -$project->current_billed_financial_costs);

        return [
            'total_volume' => $totalVolume,
            'billed_volume' => $billedVolume,
        ];
    }

    public static function getGroupTotals() {
        $revenue = FinanceRecord::where('amount', '>=', 0)->sum('amount');
        $expense = FinanceRecord::where('amount', '<', 0)->sum('amount');

        return [
            'revenue' => $revenue,
            'expense' => $expense,
        ];
    }

    public static function getGroupData(FinanceGroup $financeGroup): array
    {
        $revenue = $financeGroup->financeRecords->sum(
                fn($financeRecord) => max($financeRecord->amount, 0)
            );

        $expense = $financeGroup->financeRecords->sum(
                fn($financeRecord) => min($financeRecord->amount, 0)
            );

        return [
            'revenue' => $revenue,
            'expense' => $expense,
        ];
    }

    public static function getProjectData(Project $project): array
    {
        $total_volume = $project->costs;
        $billed_volume = $project->current_billed_financial_costs;

        return [
            'total_volume' => $total_volume ?? 0,
            'billed_volume' => $billed_volume ? -$billed_volume : 0,
        ];
    }

    public static function getProjectReportData(): array
    {
        $currentlyOpenProjectsData = static::getCurrentlyOpenProjectsData();
        $preExecutionProjectsData = static::getPreExecutionProjectsData();

        $projectData = [];

        foreach (Project::where('include_in_finances', true)
                     ->orWhereNotNull('financial_costs')->get() as $project) {
           $projectData[$project->name] = static::getProjectData($project);
        }

        ksort($projectData);

        return [
            'currentlyOpenProjectsData' => $currentlyOpenProjectsData,
            'preExecutionProjectsData' => $preExecutionProjectsData,
            'projectData' => $projectData,
        ];
    }

    public static function getGroupReportData(): array
    {
        $groupTotals = self::getGroupTotals();

        $groupDetails = [];

        foreach (FinanceGroup::with('financeRecords')->get() as $financeGroup) {
            $groupDetails[$financeGroup->title] =
                $financeGroup->financeRecords->map->only('billed_on', 'title', 'amount')->toArray();
        }

        return [
            'groupTotals' => $groupTotals,
            'groupDetails' => $groupDetails,
        ];
    }
}
