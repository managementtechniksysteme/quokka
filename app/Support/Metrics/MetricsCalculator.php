<?php

namespace App\Support\Metrics;

use App\Models\Accounting;
use App\Models\AdditionsReport;
use App\Models\ApplicationSettings;
use App\Models\ConstructionReport;
use App\Models\Employee;
use App\Models\FlowMeterInspectionReport;
use App\Models\InspectionReport;
use App\Models\Logbook;
use App\Models\MaterialService;
use App\Models\Project;
use App\Models\ServiceReport;
use App\Models\Task;
use App\Models\WageService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Spatie\Activitylog\Models\Activity;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MetricsCalculator
{
    private const REPORT_TYPES = [
        'construction' => ['label' => 'Baubericht', 'model' => ConstructionReport::class, 'date_field' => 'services_provided_on'],
        'inspection' => ['label' => 'Prüfbericht', 'model' => InspectionReport::class, 'date_field' => 'inspected_on'],
        'service' => ['label' => 'Servicebericht', 'model' => ServiceReport::class, 'date_field' => null],
        'additions' => ['label' => 'Regiebericht', 'model' => AdditionsReport::class, 'date_field' => 'services_provided_on'],
        'flow_meter' => ['label' => 'Durchflussmessung', 'model' => FlowMeterInspectionReport::class, 'date_field' => 'inspected_on'],
    ];

    public function __construct(private readonly MetricsFilters $filters)
    {
    }

    public static function reportTypeOptions(): array
    {
        return collect(self::REPORT_TYPES)->map(fn ($type) => $type['label'])->all();
    }

    // ---------------------------------------------------------------
    // KPI tiles
    // ---------------------------------------------------------------

    public function averageTimeToSignature(): array
    {
        $days = $this->signedReportDurations($this->reportsInRange($this->filters->from, $this->filters->to));

        return [
            'mean' => $days->isEmpty() ? null : round($days->avg(), 1),
            'median' => $days->isEmpty() ? null : round($this->median($days), 1),
            'count' => $days->count(),
        ];
    }

    public function onTimeTaskRate(): array
    {
        $tasks = $this->tasksInRange()
            ->where('status', 'finished')
            ->whereNotNull('due_on')
            ->get(['ends_on', 'due_on']);

        $onTime = $tasks->filter(fn ($task) => $task->ends_on && $task->ends_on->lte($task->due_on))->count();
        $total = $tasks->count();

        return [
            'rate' => $total ? (int) round($onTime / $total * 100) : null,
            'on_time' => $onTime,
            'total' => $total,
        ];
    }

    public function overdueTasksSummary(): array
    {
        $tasks = $this->tasksInRange()
            ->where('status', '!=', 'finished')
            ->whereNotNull('due_on')
            ->where('due_on', '<', Carbon::today())
            ->get(['due_on']);

        $count = $tasks->count();
        $averageDays = $count
            ? round($tasks->avg(fn ($task) => abs($task->due_on->diffInDays(Carbon::today()))), 1)
            : null;

        return ['average_days' => $averageDays, 'count' => $count];
    }

    public function activeProjectsSummary(): array
    {
        $current = $this->activeProjectsQuery($this->filters->to)->count();
        $previous = $this->activeProjectsQuery($this->filters->previousTo)->count();

        return ['count' => $current, 'delta' => $current - $previous];
    }

    public function teamUtilisationPercentage(): ?int
    {
        $workload = $this->employeeWorkload();

        return $workload->isEmpty() ? null : (int) round($workload->avg('relative_to_busiest'));
    }

    public function averageHoursPerWeek(): ?float
    {
        $employees = $this->employeesInScope();

        if ($employees->isEmpty()) {
            return null;
        }

        $totalHours = $this->applyDimensionFilters(
            Accounting::query()
                ->whereIn('service_id', $this->hourBasedServiceIds())
                ->whereBetween('service_provided_on', [$this->filters->from, $this->filters->to])
                ->whereIn('employee_id', $employees->pluck('person_id'))
        )->sum('amount');

        $weeks = ($this->filters->from->diffInDays($this->filters->to->copy()->startOfDay()) + 1) / 7;

        if ($weeks <= 0) {
            return null;
        }

        return round($totalHours / $weeks / $employees->count(), 1);
    }

    public function drivenDistanceSummary(): array
    {
        $entries = $this->logbookEntries()->get(['driven_kilometres']);

        return ['kilometres' => (int) $entries->sum('driven_kilometres'), 'trips' => $entries->count()];
    }

    // ---------------------------------------------------------------
    // Row 1: status breakdowns
    // ---------------------------------------------------------------

    public function taskStatusBreakdown(): array
    {
        $tasks = $this->tasksInRange()->get(['status', 'due_on']);
        $total = $tasks->count();

        $overdue = $tasks->filter(fn ($task) => $task->status !== 'finished' && $task->due_on && $task->due_on->isPast())->count();
        $finished = $tasks->where('status', 'finished')->count();
        $new = $tasks->filter(fn ($task) => $task->status === 'new' && !($task->due_on && $task->due_on->isPast()))->count();
        $inProgress = max($total - $overdue - $finished - $new, 0);

        return compact('total', 'new', 'inProgress', 'finished', 'overdue');
    }

    public function reportStatusBreakdown(): array
    {
        $reports = $this->reportsInRange($this->filters->from, $this->filters->to);

        return [
            'total' => $reports->count(),
            'new' => $reports->where('status', 'new')->count(),
            'signed' => $reports->where('status', 'signed')->count(),
            'finished' => $reports->where('status', 'finished')->count(),
        ];
    }

    // ---------------------------------------------------------------
    // Row 2: grouped comparisons
    // ---------------------------------------------------------------

    public function onTimeRateByCustomer(): Collection
    {
        return $this->onTimeRateGroupedBy('company');
    }

    public function onTimeRateByProject(): Collection
    {
        return $this->onTimeRateGroupedBy('project');
    }

    public function onTimeRateByEmployee(): Collection
    {
        return $this->onTimeRateGroupedBy('employee');
    }

    public function timeToSignatureByCustomer(): Collection
    {
        return $this->timeToSignatureGroupedBy('company');
    }

    public function timeToSignatureByEmployee(): Collection
    {
        return $this->timeToSignatureGroupedBy('employee');
    }

    // ---------------------------------------------------------------
    // Row 3: employee workload + time to completion
    // ---------------------------------------------------------------

    public function employeeWorkload(): Collection
    {
        $employees = $this->employeesInScope();

        $baseQuery = fn () => $this->tasksInRange(includeEmployee: false)
            ->where('status', '!=', 'finished')
            ->whereIn('employee_id', $employees->pluck('person_id'));

        $openTaskCounts = (clone $baseQuery())
            ->selectRaw('employee_id, count(*) as open_tasks')
            ->groupBy('employee_id')
            ->pluck('open_tasks', 'employee_id');

        $maxOpen = $openTaskCounts->max() ?: 0;
        $totalOpen = $openTaskCounts->sum();

        return $employees->map(function ($employee) use ($openTaskCounts, $maxOpen, $totalOpen) {
            $open = $openTaskCounts[$employee->person_id] ?? 0;

            return (object) [
                'employee' => $employee,
                'open_tasks' => $open,
                'relative_to_busiest' => $maxOpen > 0 ? (int) round($open / $maxOpen * 100) : 0,
                'share_of_team' => $totalOpen > 0 ? (int) round($open / $totalOpen * 100) : 0,
            ];
        })->sortByDesc('open_tasks')->values();
    }

    public function timeToCompletionByCustomer(): Collection
    {
        $reports = $this->attachSignedAndFinishedTimestamps($this->reportsInRange($this->filters->from, $this->filters->to))
            ->filter(fn ($report) => $report->signed_at && $report->finished_at);

        $projects = $this->projectsFor($reports->pluck('project_id'));

        return $reports->groupBy(fn ($report) => $projects[$report->project_id]?->company?->full_name ?? 'Unbekannt')
            ->map(function ($group, $label) {
                $days = $group->map(fn ($report) => $report->signed_at->diffInHours($report->finished_at) / 24);

                return (object) [
                    'label' => $label,
                    'mean' => round($days->avg(), 1),
                    'median' => round($this->median($days), 1),
                    'count' => $days->count(),
                ];
            })->sortByDesc('mean')->values();
    }

    // ---------------------------------------------------------------
    // Row 4: accounting breakdown + logbook
    // ---------------------------------------------------------------

    public function accountingBreakdown(?string $dimension = null): Collection
    {
        $materialServiceIds = MaterialService::pluck('id');
        $wageServiceIds = WageService::whereNotNull('costs')->pluck('id');

        $accounting = $this->applyDimensionFilters(
            Accounting::query()
                ->whereBetween('service_provided_on', [$this->filters->from, $this->filters->to])
                ->whereIn('service_id', $materialServiceIds->merge($wageServiceIds))
        )->with(['service', 'project.company', 'employee.person'])->get();

        return $accounting->groupBy(function ($row) use ($dimension) {
            return match ($dimension) {
                'company' => $row->project?->company?->full_name ?? 'Unbekannt',
                'project' => $row->project?->name ?? 'Unbekannt',
                'employee' => $row->employee?->person?->name ?? 'Unbekannt',
                default => $row->service?->name ?? 'Unbekannt',
            };
        })->map(function ($group, $label) use ($materialServiceIds) {
            $value = $group->sum(function ($row) use ($materialServiceIds) {
                return $materialServiceIds->contains($row->service_id)
                    ? $row->amount
                    : $row->amount * ($row->service?->costs ?? 0);
            });

            return (object) ['label' => $label, 'value' => round($value, 2)];
        })->sortByDesc('value')->values();
    }

    public function logbookDistanceByVehicle(): Collection
    {
        return $this->logbookDistanceGroupedBy('vehicle');
    }

    public function logbookDistanceByCustomer(): Collection
    {
        return $this->logbookDistanceGroupedBy('company');
    }

    public function logbookDistanceByEmployee(): Collection
    {
        return $this->logbookDistanceGroupedBy('employee');
    }

    // ---------------------------------------------------------------
    // Shared helpers
    // ---------------------------------------------------------------

    public function topNWithRest(Collection $items, int $n, string $valueKey, string $restLabel = 'Sonstige'): Collection
    {
        if ($items->count() <= $n) {
            return $items;
        }

        $rest = $items->slice($n);

        return $items->take($n)->push((object) [
            'label' => $restLabel,
            $valueKey => $rest->sum($valueKey),
        ]);
    }

    private function tasksInRange(bool $includeEmployee = true): Builder
    {
        return $this->applyDimensionFilters(
            Task::query()->where(
                fn ($query) => $query
                    ->whereBetween('starts_on', [$this->filters->from, $this->filters->to])
                    ->orWhereBetween('ends_on', [$this->filters->from, $this->filters->to])
            ),
            includeEmployee: $includeEmployee,
        );
    }

    private function applyDimensionFilters(Builder $query, bool $includeEmployee = true): Builder
    {
        return $query
            ->when($this->filters->projectId, fn ($q) => $q->where('project_id', $this->filters->projectId))
            ->when($includeEmployee && $this->filters->employeeId, fn ($q) => $q->where('employee_id', $this->filters->employeeId))
            ->when($this->filters->companyId, fn ($q) => $q->whereHas('project', fn ($q2) => $q2->where('company_id', $this->filters->companyId)))
            ->when($this->filters->onlyActiveProjects, fn ($q) => $q->whereHas('project', fn ($q2) => $q2
                ->where(fn ($q3) => $q3->whereNull('ends_on')->orWhere('ends_on', '>=', Carbon::today()))));
    }

    private function activeProjectsQuery(Carbon $asOf): Builder
    {
        return Project::query()
            ->where(fn ($q) => $q->whereNull('ends_on')->orWhere('ends_on', '>=', $asOf))
            ->when($this->filters->companyId, fn ($q) => $q->where('company_id', $this->filters->companyId));
    }

    private function employeesInScope(): Collection
    {
        return Employee::with('person')
            ->when($this->filters->employeeId, fn ($q) => $q->where('person_id', $this->filters->employeeId))
            ->where(fn ($q) => $q->whereNull('left_on')->orWhere('left_on', '>=', $this->filters->from))
            ->where('entered_on', '<=', $this->filters->to)
            ->get();
    }

    private function hourBasedServiceIds(): Collection
    {
        $specialServiceIds = array_filter([
            ApplicationSettings::get()->allowances_service_id,
            ApplicationSettings::get()->overtime_50_service_id,
            ApplicationSettings::get()->overtime_100_service_id,
            ApplicationSettings::get()->time_balance_service_id,
            ApplicationSettings::get()->holiday_service_id,
        ]);

        return WageService::whereUnit(ApplicationSettings::get()->services_hour_unit)
            ->whereNotIn('id', $specialServiceIds)
            ->pluck('id');
    }

    private function logbookEntries(): Builder
    {
        return $this->applyDimensionFilters(
            Logbook::query()
                ->whereBetween('driven_on', [$this->filters->from, $this->filters->to])
                ->whereHas('vehicle', fn ($q) => $q->where('private', false))
        );
    }

    private function logbookDistanceGroupedBy(string $dimension): Collection
    {
        $entries = $this->logbookEntries()->with(['vehicle', 'project.company', 'employee.person'])->get();

        return $entries->groupBy(function ($entry) use ($dimension) {
            return match ($dimension) {
                'company' => $entry->project?->company?->full_name ?? 'Unbekannt',
                'employee' => $entry->employee?->person?->name ?? 'Unbekannt',
                default => $entry->vehicle ? "{$entry->vehicle->make_model} · {$entry->vehicle->registration_identifier}" : 'Unbekannt',
            };
        })->map(fn ($group, $label) => (object) [
            'label' => $label,
            'kilometres' => (int) $group->sum('driven_kilometres'),
            'trips' => $group->count(),
        ])->sortByDesc('kilometres')->values();
    }

    private function onTimeRateGroupedBy(string $dimension): Collection
    {
        $tasks = $this->tasksInRange()
            ->where('status', 'finished')
            ->whereNotNull('due_on')
            ->with(['project.company', 'responsibleEmployee.person'])
            ->get(['id', 'ends_on', 'due_on', 'project_id', 'employee_id']);

        return $tasks->groupBy(function ($task) use ($dimension) {
            return match ($dimension) {
                'company' => $task->project?->company?->full_name ?? 'Unbekannt',
                'project' => $task->project?->name ?? 'Unbekannt',
                'employee' => $task->responsibleEmployee?->person?->name ?? 'Unbekannt',
            };
        })->map(function ($group, $label) {
            $onTime = $group->filter(fn ($task) => $task->ends_on && $task->ends_on->lte($task->due_on))->count();
            $total = $group->count();

            return (object) [
                'label' => $label,
                'rate' => $total ? (int) round($onTime / $total * 100) : 0,
                'total' => $total,
            ];
        })->sortByDesc('rate')->values();
    }

    private function timeToSignatureGroupedBy(string $dimension): Collection
    {
        $reports = $this->attachSignedAndFinishedTimestamps($this->reportsInRange($this->filters->from, $this->filters->to))
            ->filter(fn ($report) => $report->date && $report->signed_at);

        $projects = $this->projectsFor($reports->pluck('project_id'));
        $employees = $this->employeesFor($reports->pluck('employee_id'));

        return $reports->groupBy(function ($report) use ($dimension, $projects, $employees) {
            return match ($dimension) {
                'company' => $projects[$report->project_id]?->company?->full_name ?? 'Unbekannt',
                'employee' => $employees[$report->employee_id]?->person?->name ?? 'Unbekannt',
            };
        })->map(function ($group, $label) {
            $days = $group->map(fn ($report) => $report->date->diffInHours($report->signed_at) / 24);

            return (object) [
                'label' => $label,
                'mean' => round($days->avg(), 1),
                'median' => round($this->median($days), 1),
                'count' => $days->count(),
            ];
        })->sortByDesc('mean')->values();
    }

    private function signedReportDurations(Collection $reports): Collection
    {
        return $this->attachSignedAndFinishedTimestamps($reports)
            ->filter(fn ($report) => $report->date && $report->signed_at)
            ->map(fn ($report) => $report->date->diffInHours($report->signed_at) / 24);
    }

    private function reportTypes(): array
    {
        if ($this->filters->reportType && isset(self::REPORT_TYPES[$this->filters->reportType])) {
            return [$this->filters->reportType => self::REPORT_TYPES[$this->filters->reportType]];
        }

        return self::REPORT_TYPES;
    }

    private function reportsInRange(Carbon $from, Carbon $to): Collection
    {
        $reports = collect();

        foreach ($this->reportTypes() as $key => $type) {
            $model = $type['model'];
            $query = $this->applyDimensionFilters($model::query());

            if ($type['date_field'] !== null) {
                $query->whereBetween($type['date_field'], [$from, $to]);
                $rows = $query->get(['id', 'project_id', 'employee_id', 'status', $type['date_field']]);

                foreach ($rows as $row) {
                    $reports->push((object) [
                        'id' => $row->id,
                        'type' => $key,
                        'model' => $model,
                        'label' => $type['label'],
                        'project_id' => $row->project_id,
                        'employee_id' => $row->employee_id,
                        'status' => $row->status,
                        'date' => $row->{$type['date_field']},
                    ]);
                }

                continue;
            }

            $query->whereHas('services', fn ($q) => $q->whereBetween('provided_on', [$from, $to]))
                ->withMax('services', 'provided_on');
            $rows = $query->get(['id', 'project_id', 'employee_id', 'status']);

            foreach ($rows as $row) {
                $reports->push((object) [
                    'id' => $row->id,
                    'type' => $key,
                    'model' => $model,
                    'label' => $type['label'],
                    'project_id' => $row->project_id,
                    'employee_id' => $row->employee_id,
                    'status' => $row->status,
                    'date' => $row->services_max_provided_on ? Carbon::parse($row->services_max_provided_on) : null,
                ]);
            }
        }

        return $reports;
    }

    private function attachSignedAndFinishedTimestamps(Collection $reports): Collection
    {
        $signedByKey = collect();
        $finishedByKey = collect();

        foreach ($reports->groupBy('model') as $model => $group) {
            $ids = $group->pluck('id')->all();

            Media::where('model_type', $model)
                ->where('collection_name', 'signature')
                ->whereIn('model_id', $ids)
                ->get(['model_id', 'created_at'])
                ->each(function ($media) use ($signedByKey, $model) {
                    $signedByKey["{$model}|{$media->model_id}"] = $media->created_at;
                });

            Activity::where('subject_type', $model)
                ->whereIn('subject_id', $ids)
                ->whereJsonContains('attribute_changes->attributes->status', 'finished')
                ->get(['subject_id', 'created_at'])
                ->groupBy('subject_id')
                ->each(function ($transitions, $subjectId) use ($finishedByKey, $model) {
                    $finishedByKey["{$model}|{$subjectId}"] = $transitions->max('created_at');
                });
        }

        return $reports->map(function ($report) use ($signedByKey, $finishedByKey) {
            $key = "{$report->model}|{$report->id}";
            $report->signed_at = $signedByKey[$key] ?? null;
            $report->finished_at = $finishedByKey[$key] ?? null;

            return $report;
        });
    }

    private function projectsFor(Collection $projectIds): Collection
    {
        return Project::with('company')->whereIn('id', $projectIds->unique()->filter())->get()->keyBy('id');
    }

    private function employeesFor(Collection $employeeIds): Collection
    {
        return Employee::with('person')->whereIn('person_id', $employeeIds->unique()->filter())->get()->keyBy('person_id');
    }

    private function median(Collection $values): float
    {
        $sorted = $values->sort()->values();
        $count = $sorted->count();

        if ($count === 0) {
            return 0.0;
        }

        $middle = intdiv($count, 2);

        if ($count % 2) {
            return $sorted[$middle];
        }

        return ($sorted[$middle - 1] + $sorted[$middle]) / 2;
    }
}
