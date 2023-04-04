<?php

namespace App\Helpers;

use App\Models\FinanceGroup;
use App\Models\Project;
use Carbon\Carbon;

class Finances
{
    public static function getCurrentlyOpenProjectsData(): array
    {
        $openProjects = Project::where('is_pre_execution', false)
            ->where('include_in_finances', true)
            ->where(fn ($query) =>
                $query->whereNull('ends_on')
                    ->orWhere('ends_on', '>', Carbon::today())
            )
            ->get();

        $financeGroups = FinanceGroup::whereHas('project',
            fn($query) => $query->where('is_pre_execution', false)
                ->where(fn ($query) =>
                    $query->whereNull('ends_on')
                        ->orWhere('ends_on', '>', Carbon::today())))
            ->orWhereDoesntHave('project')
            ->with('financeRecords')->get();

        $revenue = $openProjects->sum(fn($project) => $project->billed_costs) +
            $financeGroups->sum(
                fn($financeGroup) => $financeGroup->financeRecords->sum(
                    fn($financeRecord) => $financeRecord->amount >= 0 ? $financeRecord->amount : 0
                )
            );

        $expense = $openProjects->sum(fn($project) => -$project->current_costs) +
            $financeGroups->sum(
                fn($financeGroup) => $financeGroup->financeRecords->sum(
                    fn($financeRecord) => $financeRecord->amount < 0 ? $financeRecord->amount : 0
                )
            );

        return [
            'revenue' => $revenue,
            'expense' => $expense,
        ];
    }

    public static function getPreExecutionProjectsData(): array
    {
        $preExecutionProjects = Project::where('is_pre_execution', true)
            ->where('include_in_finances', true)
            ->get();

        $financeGroups = FinanceGroup::whereHas('project', fn($query) => $query->where('is_pre_execution', true))
            ->with('financeRecords')->get();

        $revenue = $preExecutionProjects->sum(fn($project) => $project->billed_costs) +
            $financeGroups->sum(
                fn($financeGroup) => $financeGroup->financeRecords->sum(
                    fn($financeRecord) => max($financeRecord->amount, 0)
                )
            );

        $expense = $preExecutionProjects->sum(fn($project) => -$project->current_costs) +
            $financeGroups->sum(
                fn($financeGroup) => $financeGroup->financeRecords->sum(
                    fn($financeRecord) => min($financeRecord->amount, 0)
                )
            );

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
        $revenue = $project->billed_costs;
        $expense = $project->current_costs;

        return [
            'revenue' => $revenue ?? 0,
            'expense' => $expense ? -$expense : 0,
        ];
    }

    public static function getReportData(): array
    {
        $currentlyOpenProjectsData = static::getCurrentlyOpenProjectsData();
        $preExecutionProjectsData = static::getPreExecutionProjectsData();

        $groupData = [];

        foreach (FinanceGroup::all() as $financeGroup) {
            $groupData[$financeGroup->title_string] = static::getGroupData($financeGroup);
        }

        foreach (Project::where('include_in_finances', true)->get() as $project) {
           $groupData[$project->name] = static::getProjectData($project);
        }

        ksort($groupData);

        $manualGroupDetails = [];

        foreach (FinanceGroup::with('financeRecords')->get() as $financeGroup) {
            $manualGroupDetails[$financeGroup->title_string] =
                $financeGroup->financeRecords->map->only('billed_on', 'title', 'amount')->toArray();
        }

        return [
            'currentlyOpenProjectsData' => $currentlyOpenProjectsData,
            'preExecutionProjectsData' => $preExecutionProjectsData,
            'groupData' => $groupData,
            'manualGroupDetails' => $manualGroupDetails,
        ];
    }
}
