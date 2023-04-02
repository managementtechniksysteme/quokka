<?php

namespace App\Helpers;

use App\Models\FinanceGroup;
use App\Models\Project;

class Finances
{
    public static function getCurrentlyOpenProjectsData(): array
    {
        $openProjects = Project::where('is_pre_execution', false)
            ->where('include_in_finances', true)
            ->get();

        $financeGroups = FinanceGroup::whereHas('project', fn($query) => $query->where('is_pre_execution', false))
            ->orWhereDoesntHave('project')
            ->with('financeRecords')->get();

        $revenue = $openProjects->sum(fn($project) => $project->billed_costs) +
            $financeGroups->sum(
                fn($financeGroup) => $financeGroup->financeRecords->sum(
                    fn($financeRecord) => $financeRecord->amount >= 0 ? $financeRecord->amount : 0
                )
            );

        $expense = $openProjects->sum(fn($project) => $project->current_costs) +
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

        $expense = $preExecutionProjects->sum(fn($project) => $project->current_costs) +
            $financeGroups->sum(
                fn($financeGroup) => $financeGroup->financeRecords->sum(
                    fn($financeRecord) => min($financeRecord->amount, 0)
                )
            );

        return [
            'revenue' => $revenue,
            'expense' => -$expense,
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
        return [];
    }
}
