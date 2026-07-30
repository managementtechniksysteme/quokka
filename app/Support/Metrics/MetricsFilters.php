<?php

namespace App\Support\Metrics;

use Carbon\Carbon;
use Illuminate\Http\Request;

class MetricsFilters
{
    public function __construct(
        public readonly Carbon $from,
        public readonly Carbon $to,
        public readonly Carbon $previousTo,
        public readonly string $period,
        public readonly Carbon $anchor,
        public readonly ?int $companyId = null,
        public readonly ?string $employeeId = null,
        public readonly ?int $projectId = null,
        public readonly ?string $reportType = null,
        public readonly bool $onlyActiveProjects = true,
    ) {
    }

    public static function fromRequest(Request $request): self
    {
        $period = $request->input('period', 'live');
        $anchor = $request->filled('anchor') ? Carbon::parse($request->input('anchor'))->startOfDay() : Carbon::today();
        $today = Carbon::today();

        // Month/quarter/year are calendar-aligned via $anchor (which unit to
        // show), capped at today so the *current* one still reads as "to
        // date" — a *past* unit is never capped, so it shows in full rather
        // than only as many days as the current one has elapsed (2026-07-29,
        // user: clicking back from a partial year/quarter should land on the
        // FULL previous one, not a same-length slice of it; also replaced the
        // rolling "30 Tage" with a calendar month for the same reason — a
        // rolling window makes back/forward shift by a day or two instead of
        // a clean, obvious unit).
        //
        // 'live' is always month-to-date anchored on today, regardless of
        // any anchor param — it's not a navigable period, it's "right now"
        // (2026-07-30, user). The calculator uses this window for the cards
        // that are inherently date-bound (reports, hours, revenue); the
        // cards that describe current state (open task status, workload,
        // overdue) bypass this window entirely and query live state instead
        // — see MetricsCalculator::liveOpenTasksQuery().
        [$from, $to, $previousTo] = match ($period) {
            'live' => [
                $today->copy()->startOfMonth(),
                $today->copy(),
                $today->copy()->subMonth()->endOfMonth(),
            ],
            'month' => [
                $anchor->copy()->startOfMonth(),
                $anchor->copy()->endOfMonth()->min($today),
                $anchor->copy()->subMonth()->endOfMonth(),
            ],
            'year' => [
                $anchor->copy()->startOfYear(),
                $anchor->copy()->endOfYear()->min($today),
                $anchor->copy()->subYear()->endOfYear(),
            ],
            'custom' => [
                $request->filled('from') ? Carbon::parse($request->input('from')) : $today->copy()->subDays(29),
                $request->filled('to') ? Carbon::parse($request->input('to')) : $today->copy(),
                null,
            ],
            default => [
                $anchor->copy()->firstOfQuarter(),
                $anchor->copy()->lastOfQuarter()->min($today),
                $anchor->copy()->subQuarter()->lastOfQuarter(),
            ],
        };

        $from = $from->copy()->startOfDay();
        $to = $to->copy()->endOfDay();

        // 'custom' has no calendar unit to align to — previous period is just
        // the same length immediately before, computed here once $from is final.
        $previousTo = ($previousTo ?? $from->copy()->subDay())->copy()->endOfDay();

        return new self(
            from: $from,
            to: $to,
            previousTo: $previousTo,
            period: $period,
            anchor: $anchor,
            companyId: $request->filled('company_id') ? (int) $request->input('company_id') : null,
            employeeId: $request->filled('employee_id') ? $request->input('employee_id') : null,
            projectId: $request->filled('project_id') ? (int) $request->input('project_id') : null,
            reportType: $request->filled('report_type') ? $request->input('report_type') : null,
            onlyActiveProjects: $request->boolean('only_active_projects', true),
        );
    }

    public function isLive(): bool
    {
        return $this->period === 'live';
    }

    /**
     * Query params for the "previous period" nav link. Month/quarter/year
     * stay as that period type with the anchor shifted a calendar unit back
     * (so repeated clicks keep landing on real calendar boundaries, no
     * drift); custom shifts by the current period's exact length instead,
     * since there's no calendar unit for it to align to.
     *
     * @return array<string, string>
     */
    public function previousPeriodParams(): array
    {
        // 'live' isn't a navigable period — there's no "previous now"; the
        // nav chevrons are hidden for it in the view, this is just a safe
        // fallback.
        if ($this->period === 'live') {
            return ['period' => 'live'];
        }

        if ($this->period === 'month') {
            return ['period' => 'month', 'anchor' => $this->anchor->copy()->subMonth()->format('Y-m-d')];
        }

        if ($this->period === 'quarter') {
            return ['period' => 'quarter', 'anchor' => $this->anchor->copy()->subQuarter()->format('Y-m-d')];
        }

        if ($this->period === 'year') {
            return ['period' => 'year', 'anchor' => $this->anchor->copy()->subYear()->format('Y-m-d')];
        }

        $length = $this->periodLengthInDays();
        $to = $this->from->copy()->subDay();
        $from = $to->copy()->subDays($length - 1);

        return ['period' => 'custom', 'from' => $from->format('Y-m-d'), 'to' => $to->format('Y-m-d')];
    }

    /**
     * @return array<string, string>
     */
    public function nextPeriodParams(): array
    {
        if ($this->period === 'live') {
            return ['period' => 'live'];
        }

        if ($this->period === 'month') {
            return ['period' => 'month', 'anchor' => $this->anchor->copy()->addMonth()->format('Y-m-d')];
        }

        if ($this->period === 'quarter') {
            return ['period' => 'quarter', 'anchor' => $this->anchor->copy()->addQuarter()->format('Y-m-d')];
        }

        if ($this->period === 'year') {
            return ['period' => 'year', 'anchor' => $this->anchor->copy()->addYear()->format('Y-m-d')];
        }

        $length = $this->periodLengthInDays();
        $from = $this->to->copy()->addDay();
        $to = $from->copy()->addDays($length - 1);

        return ['period' => 'custom', 'from' => $from->format('Y-m-d'), 'to' => $to->format('Y-m-d')];
    }

    private function periodLengthInDays(): int
    {
        return $this->from->diffInDays($this->to->copy()->startOfDay()) + 1;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'period' => $this->period,
            'anchor' => $this->anchor->format('Y-m-d'),
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
