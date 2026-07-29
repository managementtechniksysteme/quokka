<?php

namespace App\Support\Metrics;

use Carbon\Carbon;
use Illuminate\Http\Request;

class MetricsFilters
{
    public function __construct(
        public readonly Carbon $from,
        public readonly Carbon $to,
        public readonly Carbon $previousFrom,
        public readonly Carbon $previousTo,
        public readonly Carbon $nextFrom,
        public readonly Carbon $nextTo,
        public readonly string $period,
        public readonly ?int $companyId = null,
        public readonly ?string $employeeId = null,
        public readonly ?int $projectId = null,
        public readonly ?string $reportType = null,
        public readonly bool $onlyActiveProjects = true,
    ) {
    }

    public static function fromRequest(Request $request): self
    {
        $period = $request->input('period', 'quarter');

        [$from, $to] = match ($period) {
            '30d' => [Carbon::today()->subDays(29), Carbon::today()],
            'year' => [Carbon::today()->startOfYear(), Carbon::today()],
            'custom' => [
                $request->filled('from') ? Carbon::parse($request->input('from')) : Carbon::today()->subDays(29),
                $request->filled('to') ? Carbon::parse($request->input('to')) : Carbon::today(),
            ],
            default => [Carbon::today()->firstOfQuarter(), Carbon::today()],
        };

        $from = $from->copy()->startOfDay();
        $to = $to->copy()->endOfDay();

        $periodLengthInDays = $from->diffInDays($to->copy()->startOfDay()) + 1;
        $previousTo = $from->copy()->subDay()->endOfDay();
        $previousFrom = $previousTo->copy()->subDays($periodLengthInDays - 1)->startOfDay();
        $nextFrom = $to->copy()->addDay()->startOfDay();
        $nextTo = $nextFrom->copy()->addDays($periodLengthInDays - 1)->endOfDay();

        return new self(
            from: $from,
            to: $to,
            previousFrom: $previousFrom,
            previousTo: $previousTo,
            nextFrom: $nextFrom,
            nextTo: $nextTo,
            period: $period,
            companyId: $request->filled('company_id') ? (int) $request->input('company_id') : null,
            employeeId: $request->filled('employee_id') ? $request->input('employee_id') : null,
            projectId: $request->filled('project_id') ? (int) $request->input('project_id') : null,
            reportType: $request->filled('report_type') ? $request->input('report_type') : null,
            onlyActiveProjects: $request->boolean('only_active_projects', true),
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'period' => $this->period,
            'from' => $this->from->format('Y-m-d'),
            'to' => $this->to->format('Y-m-d'),
            'company_id' => $this->companyId,
            'employee_id' => $this->employeeId,
            'project_id' => $this->projectId,
            'report_type' => $this->reportType,
            'only_active_projects' => $this->onlyActiveProjects,
        ];
    }
}
