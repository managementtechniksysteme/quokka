@extends('layouts.app')

{{-- Mobile app bar: badge+title matching the desktop header, with the
     filter trigger teleported in from MetricsFilterBar.vue itself (same
     pattern as accounting/logbook's mobile-detail-bar, 2026-07-29) so it
     opens the same reactive filter sheet instead of a separate mechanism. --}}
@section('mobile-detail-bar')
    <span class="q-appbar__badge q-appbar__badge--tint">
        <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#bar-chart"></use></svg>
    </span>
    <span class="q-appbar__title">Kennzahlen</span>
    <div id="metricsMobileActions" class="d-flex align-items-center gap-2"></div>
@endsection

@section('content')
<div class="q-container">

    @php
        // Previous/next period keep the exact same length and turn the nav
        // into a "custom" range from here on — lets you keep clicking to walk
        // through history without recomputing calendar-aligned quarters/years
        // (2026-07-29, user: "quickly make comparisons" between timeframes).
        $previousPeriodUrl = route('metrics.index', array_merge($filtersArray, [
            'period' => 'custom',
            'from' => $filters->previousFrom->format('Y-m-d'),
            'to' => $filters->previousTo->format('Y-m-d'),
        ]));
        $nextPeriodUrl = route('metrics.index', array_merge($filtersArray, [
            'period' => 'custom',
            'from' => $filters->nextFrom->format('Y-m-d'),
            'to' => $filters->nextTo->format('Y-m-d'),
        ]));
    @endphp

    <div class="q-page-head d-none d-md-flex">
        <div>
            <div class="q-eyebrow">Auswertung</div>
            <h1 class="q-title">Kennzahlen</h1>
            <div class="q-subtitle d-inline-flex align-items-center gap-2">
                <a href="{{ $previousPeriodUrl }}" class="btn q-btn q-btn-icon" aria-label="Vorherige Periode" title="Vorherige Periode">
                    <svg class="icon-bs icon-14"><use href="{{ asset('svg/bootstrap-icons.svg') }}#chevron-left"></use></svg>
                </a>
                {{ $filters->from->format('d.m.Y') }} – {{ $filters->to->format('d.m.Y') }}
                <a href="{{ $nextPeriodUrl }}" class="btn q-btn q-btn-icon" aria-label="Nächste Periode" title="Nächste Periode">
                    <svg class="icon-bs icon-14"><use href="{{ asset('svg/bootstrap-icons.svg') }}#chevron-right"></use></svg>
                </a>
            </div>
        </div>
    </div>
    {{-- Mobile: the app bar already carries "Kennzahlen" + its own icon
         + the filter trigger (mobile-detail-bar section above) — just the
         date range + period nav here, same convention as
         latest_changes/index.blade.php. --}}
    <div class="d-flex d-md-none align-items-center gap-2 mb-3">
        <a href="{{ $previousPeriodUrl }}" class="btn q-btn q-btn-icon" aria-label="Vorherige Periode" title="Vorherige Periode">
            <svg class="icon-bs icon-14"><use href="{{ asset('svg/bootstrap-icons.svg') }}#chevron-left"></use></svg>
        </a>
        <div class="q-subtitle mb-0">{{ $filters->from->format('d.m.Y') }} – {{ $filters->to->format('d.m.Y') }}</div>
        <a href="{{ $nextPeriodUrl }}" class="btn q-btn q-btn-icon" aria-label="Nächste Periode" title="Nächste Periode">
            <svg class="icon-bs icon-14"><use href="{{ asset('svg/bootstrap-icons.svg') }}#chevron-right"></use></svg>
        </a>
    </div>

    <metrics-filter-bar
        action-url="{{ route('metrics.index') }}"
        :filters="{{ json_encode($filtersArray) }}"
        :companies="{{ $companies }}"
        :employees="{{ $employees }}"
        :projects="{{ $projects }}"
        :report-types="{{ json_encode($reportTypes) }}"
        v-cloak
    ></metrics-filter-bar>

    {{-- ============================= INFO BANNER ============================= --}}
    <div class="q-banner q-banner--info">
        <svg class="icon icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#info-circle"></use></svg>
        <span>
            Im gewählten Zeitraum berücksichtigt: <strong>Aufgaben</strong>, wenn Start- oder Enddatum im Zeitraum liegt ·
            <strong>Berichte</strong> (Bau-/Prüf-/Regiebericht, Durchflussmessung), wenn das Berichtsdatum im Zeitraum liegt ·
            <strong>Serviceberichte</strong>, wenn mindestens eine erbrachte Leistung im Zeitraum liegt.
        </span>
    </div>

    {{-- ============================= KPI TILES ============================= --}}
    @php
        $timeToSignature = $metrics->averageTimeToSignature();
        $onTimeRate = $metrics->onTimeTaskRate();
        $overdue = $metrics->overdueTasksSummary();
        $activeProjects = $metrics->activeProjectsSummary();
        $utilisation = $metrics->teamUtilisationPercentage();
        $avgHours = $metrics->averageHoursPerWeek();
        $distance = $metrics->drivenDistanceSummary();
    @endphp
    <div class="q-tiles q-tiles--6">
        <div class="q-card q-tile q-tile--info">
            <div class="q-tile__head"><span class="q-tile__label">Ø Zeit bis Unterschrift</span><span class="q-tile__icon"><svg class="icon-bs icon-14"><use href="{{ asset('svg/bootstrap-icons.svg') }}#pen"></use></svg></span></div>
            <div class="q-tile__value">{{ $timeToSignature['mean'] !== null ? number_format($timeToSignature['mean'], 1, ',', '.') : '–' }}<span class="q-tile__unit">Tage</span></div>
            <div class="q-tile__sub">{{ $timeToSignature['median'] !== null ? 'Median '.number_format($timeToSignature['median'], 1, ',', '.').' Tage' : 'Keine Daten' }}</div>
        </div>
        <div class="q-card q-tile q-tile--success">
            <div class="q-tile__head"><span class="q-tile__label">Termintreue Aufgaben</span><span class="q-tile__icon"><svg class="icon-bs icon-14"><use href="{{ asset('svg/bootstrap-icons.svg') }}#check2-square"></use></svg></span></div>
            <div class="q-tile__value">{{ $onTimeRate['rate'] ?? '–' }}<span class="q-tile__unit">%</span></div>
            <div class="q-tile__sub">{{ $onTimeRate['on_time'] }} von {{ $onTimeRate['total'] }} pünktlich</div>
        </div>
        <div class="q-card q-tile q-tile--danger">
            <div class="q-tile__head"><span class="q-tile__label">Ø Verzug überfällig</span><span class="q-tile__icon"><svg class="icon-bs icon-14"><use href="{{ asset('svg/bootstrap-icons.svg') }}#hourglass-split"></use></svg></span></div>
            <div class="q-tile__value">{{ $overdue['average_days'] !== null ? number_format($overdue['average_days'], 1, ',', '.') : '–' }}<span class="q-tile__unit">Tage</span></div>
            <div class="q-tile__sub">{{ $overdue['count'] }} überfällige Aufgaben</div>
        </div>
        <div class="q-card q-tile q-tile--accent">
            <div class="q-tile__head"><span class="q-tile__label">Aktive Projekte</span><span class="q-tile__icon"><svg class="icon-bs icon-14"><use href="{{ asset('svg/bootstrap-icons.svg') }}#clipboard"></use></svg></span></div>
            <div class="q-tile__value">{{ $activeProjects['count'] }}</div>
            <div class="q-tile__sub {{ $activeProjects['delta'] > 0 ? 'q-tile__sub--up' : ($activeProjects['delta'] < 0 ? 'q-tile__sub--down' : '') }}">
                @if($activeProjects['delta'] !== 0)
                    <svg class="icon-bs"><use href="{{ asset('svg/bootstrap-icons.svg') }}#{{ $activeProjects['delta'] > 0 ? 'arrow-up-right' : 'arrow-down-right' }}"></use></svg>
                    {{ $activeProjects['delta'] > 0 ? '+' : '' }}{{ $activeProjects['delta'] }} zur Vorperiode
                @else
                    Unverändert zur Vorperiode
                @endif
            </div>
        </div>
        <div class="q-card q-tile q-tile--warning-icon">
            <div class="q-tile__head"><span class="q-tile__label">Auslastung Team</span><span class="q-tile__icon"><svg class="icon-bs icon-14"><use href="{{ asset('svg/bootstrap-icons.svg') }}#people"></use></svg></span></div>
            <div class="q-tile__value">{{ $utilisation ?? '–' }}<span class="q-tile__unit">%</span></div>
            <div class="q-tile__sub">{{ $avgHours !== null ? 'Ø '.number_format($avgHours, 1, ',', '.').' Std / Woche' : 'Keine Daten' }}</div>
        </div>
        <div class="q-card q-tile q-tile--violet">
            <div class="q-tile__head"><span class="q-tile__label">Gefahrene Strecke</span><span class="q-tile__icon"><svg class="icon-bs icon-14"><use href="{{ asset('svg/bootstrap-icons.svg') }}#truck"></use></svg></span></div>
            <div class="q-tile__value">{{ number_format($distance['kilometres'], 0, ',', '.') }}<span class="q-tile__unit">km</span></div>
            <div class="q-tile__sub">{{ $distance['trips'] }} Fahrten</div>
        </div>
    </div>

    {{-- ============================= COLUMN LABELS ============================= --}}
    <div class="q-colheads">
        <div class="q-colhead"><svg class="icon-bs icon-14"><use href="{{ asset('svg/bootstrap-icons.svg') }}#list-task"></use></svg>Aufgaben &amp; Team</div>
        <div class="q-colhead"><svg class="icon-bs icon-14"><use href="{{ asset('svg/bootstrap-icons.svg') }}#cash-stack"></use></svg>Abrechnung &amp; Berichte</div>
    </div>

    {{-- ============================= ROW 1: task status + report status ============================= --}}
    @php
        $taskStatus = $metrics->taskStatusBreakdown();
        $reportStatus = $metrics->reportStatusBreakdown();
        $taskStatusPct = fn ($n) => $taskStatus['total'] > 0 ? round($n / $taskStatus['total'] * 100, 1) : 0;
        $reportStatusPct = fn ($n) => $reportStatus['total'] > 0 ? round($n / $reportStatus['total'] * 100, 1) : 0;
    @endphp
    <div class="q-chartgrid">
        <div class="q-card">
            <div class="q-card__head">
                <div>
                    <div class="q-card__title">Aufgaben-Status</div>
                    <div class="q-card__hint">{{ $taskStatus['total'] }} Aufgaben im Zeitraum (Start- oder Enddatum)</div>
                </div>
            </div>
            <div class="q-card__body">
                <div class="q-stackbar">
                    <div class="q-stackbar__seg" style="width:{{ $taskStatusPct($taskStatus['new']) }}%; background:var(--q-sky)"></div>
                    <div class="q-stackbar__seg" style="width:{{ $taskStatusPct($taskStatus['inProgress']) }}%; background:var(--q-amber)"></div>
                    <div class="q-stackbar__seg" style="width:{{ $taskStatusPct($taskStatus['finished']) }}%; background:var(--q-green)"></div>
                    <div class="q-stackbar__seg" style="width:{{ $taskStatusPct($taskStatus['overdue']) }}%; background:var(--q-red)"></div>
                </div>
                <div class="q-stackbar-legend">
                    <span class="q-stackbar-legend__item"><span class="q-chart-legend__dot" style="background:var(--q-sky)"></span>Neu · <b>{{ $taskStatus['new'] }}</b></span>
                    <span class="q-stackbar-legend__item"><span class="q-chart-legend__dot" style="background:var(--q-amber)"></span>In Bearbeitung · <b>{{ $taskStatus['inProgress'] }}</b></span>
                    <span class="q-stackbar-legend__item"><span class="q-chart-legend__dot" style="background:var(--q-green)"></span>Fertig · <b>{{ $taskStatus['finished'] }}</b></span>
                    <span class="q-stackbar-legend__item"><span class="q-chart-legend__dot" style="background:var(--q-red)"></span>Überfällig · <b>{{ $taskStatus['overdue'] }}</b></span>
                </div>
            </div>
        </div>

        <div class="q-card">
            <div class="q-card__head">
                <div>
                    <div class="q-card__title">Unterschrift-Status</div>
                    <div class="q-card__hint">{{ $reportStatus['total'] }} Berichte im Zeitraum</div>
                </div>
            </div>
            <div class="q-card__body">
                <div class="q-stackbar">
                    <div class="q-stackbar__seg" style="width:{{ $reportStatusPct($reportStatus['new']) }}%; background:var(--q-sky)"></div>
                    <div class="q-stackbar__seg" style="width:{{ $reportStatusPct($reportStatus['signed']) }}%; background:var(--q-amber)"></div>
                    <div class="q-stackbar__seg" style="width:{{ $reportStatusPct($reportStatus['finished']) }}%; background:var(--q-green)"></div>
                </div>
                <div class="q-stackbar-legend">
                    <span class="q-stackbar-legend__item"><span class="q-chart-legend__dot" style="background:var(--q-sky)"></span>Neu · <b>{{ $reportStatus['new'] }}</b></span>
                    <span class="q-stackbar-legend__item"><span class="q-chart-legend__dot" style="background:var(--q-amber)"></span>Unterschrieben · <b>{{ $reportStatus['signed'] }}</b></span>
                    <span class="q-stackbar-legend__item"><span class="q-chart-legend__dot" style="background:var(--q-green)"></span>Fertig · <b>{{ $reportStatus['finished'] }}</b></span>
                </div>
            </div>
        </div>
    </div>

    {{-- ============================= ROW 2: on-time rate + time to signature ============================= --}}
    @php
        $onTimeByCustomer = $metrics->onTimeRateByCustomer();
        $onTimeByProject = $metrics->onTimeRateByProject();
        $onTimeByEmployee = $metrics->onTimeRateByEmployee();
        // 0% renders as neutral gray, not red — an invisible zero-width bar
        // paired with alarm-red text reads as a rendering glitch rather than
        // a real (if small-sample) "all late" signal (2026-07-29, user).
        $rateColor = fn ($rate) => $rate === 0 ? 'var(--q-faint)' : ($rate >= 85 ? 'var(--q-green)' : ($rate >= 65 ? 'var(--q-amber)' : 'var(--q-red)'));

        $sigByCustomer = $metrics->timeToSignatureByCustomer();
        $sigByEmployee = $metrics->timeToSignatureByEmployee();
        $sigMaxMean = max($sigByCustomer->max('mean') ?? 0, $sigByEmployee->max('mean') ?? 0);
    @endphp
    <div class="q-chartgrid">
        <div class="q-card">
            <div class="q-card__head">
                <div>
                    <div class="q-card__title">Termintreue</div>
                    <div class="q-card__hint">Anteil rechtzeitig abgeschlossen</div>
                </div>
                <ul class="nav nav-pills" id="ontime-tabs" role="tablist">
                    <li class="nav-item" role="presentation"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#ontime-customer" type="button" role="tab">Kunde</button></li>
                    <li class="nav-item" role="presentation"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#ontime-project" type="button" role="tab">Projekt</button></li>
                    <li class="nav-item" role="presentation"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#ontime-employee" type="button" role="tab">Mitarbeiter</button></li>
                </ul>
            </div>
            <div class="q-card__body">
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="ontime-customer" role="tabpanel">
                        <div class="q-hbars q-scroll-cap">
                            @forelse($onTimeByCustomer as $row)
                                @include('metrics.partials.hbar_row', ['label' => $row->label, 'value' => $row->rate.'%', 'percentage' => $row->rate, 'color' => $rateColor($row->rate)])
                            @empty
                                <p class="text-muted mb-0">Keine Daten im Zeitraum.</p>
                            @endforelse
                        </div>
                    </div>
                    <div class="tab-pane fade" id="ontime-project" role="tabpanel">
                        <div class="q-hbars q-scroll-cap">
                            @forelse($onTimeByProject as $row)
                                @include('metrics.partials.hbar_row', ['label' => $row->label, 'value' => $row->rate.'%', 'percentage' => $row->rate, 'color' => $rateColor($row->rate)])
                            @empty
                                <p class="text-muted mb-0">Keine Daten im Zeitraum.</p>
                            @endforelse
                        </div>
                    </div>
                    <div class="tab-pane fade" id="ontime-employee" role="tabpanel">
                        <div class="q-hbars q-scroll-cap">
                            @forelse($onTimeByEmployee as $row)
                                @include('metrics.partials.hbar_row', ['label' => $row->label, 'value' => $row->rate.'%', 'percentage' => $row->rate, 'color' => $rateColor($row->rate)])
                            @empty
                                <p class="text-muted mb-0">Keine Daten im Zeitraum.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="q-card">
            <div class="q-card__head">
                <div>
                    <div class="q-card__title">Zeit bis Unterschrift</div>
                    <div class="q-card__hint">Nach Kunde bzw. Mitarbeiter, absteigend sortiert</div>
                </div>
                <ul class="nav nav-pills" id="sig-tabs" role="tablist">
                    <li class="nav-item" role="presentation"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#sig-customer" type="button" role="tab">Kunde</button></li>
                    <li class="nav-item" role="presentation"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#sig-employee" type="button" role="tab">Mitarbeiter</button></li>
                </ul>
            </div>
            <div class="q-card__body">
                <div class="q-dualbar__legend">
                    <span class="q-chart-legend"><span class="q-chart-legend__dot" style="background:var(--q-accent)"></span>Ø Mittelwert</span>
                    <span class="q-chart-legend"><span class="q-chart-legend__dot" style="background:var(--q-faint)"></span>Median</span>
                </div>
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="sig-customer" role="tabpanel">
                        <div class="q-hbars q-scroll-cap">
                            @forelse($sigByCustomer as $row)
                                @include('metrics.partials.dualbar_row', ['label' => $row->label, 'mean' => $row->mean, 'median' => $row->median, 'maxMean' => $sigMaxMean, 'unit' => 'Tage'])
                            @empty
                                <p class="text-muted mb-0">Keine Daten im Zeitraum.</p>
                            @endforelse
                        </div>
                    </div>
                    <div class="tab-pane fade" id="sig-employee" role="tabpanel">
                        <div class="q-hbars q-scroll-cap">
                            @forelse($sigByEmployee as $row)
                                @include('metrics.partials.dualbar_row', ['label' => $row->label, 'mean' => $row->mean, 'median' => $row->median, 'maxMean' => $sigMaxMean, 'unit' => 'Tage'])
                            @empty
                                <p class="text-muted mb-0">Keine Daten im Zeitraum.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ============================= ROW 3: employee workload + time to completion ============================= --}}
    @php
        $workload = $metrics->employeeWorkload();
        $completionByCustomer = $metrics->timeToCompletionByCustomer();
        $completionMaxMean = $completionByCustomer->max('mean') ?? 0;
    @endphp
    <div class="q-chartgrid">
        <div class="q-card">
            <div class="q-card__head">
                <div>
                    <div class="q-card__title">Mitarbeiter-Auslastung</div>
                    <div class="q-card__hint">Offene Aufgaben je Mitarbeiter</div>
                </div>
                <ul class="nav nav-pills" id="util-tabs" role="tablist">
                    <li class="nav-item" role="presentation"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#util-relative" type="button" role="tab">Zur ausgelastetsten Person</button></li>
                    <li class="nav-item" role="presentation"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#util-share" type="button" role="tab">Anteil am Team</button></li>
                </ul>
            </div>
            <div class="q-card__body">
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="util-relative" role="tabpanel">
                        <div class="q-card__hint mb-3">Auslastung relativ zur Person mit den meisten offenen Aufgaben (= 100%).</div>
                        <div class="q-people-list q-scroll-cap">
                            @forelse($workload as $row)
                                @include('metrics.partials.workload_row', ['row' => $row, 'value' => $row->relative_to_busiest])
                            @empty
                                <p class="text-muted mb-0">Keine Mitarbeiter im Zeitraum.</p>
                            @endforelse
                        </div>
                    </div>
                    <div class="tab-pane fade" id="util-share" role="tabpanel">
                        <div class="q-card__hint mb-3">Anteil der offenen Aufgaben dieser Person am gesamten Team-Aufkommen.</div>
                        <div class="q-people-list q-scroll-cap">
                            @forelse($workload as $row)
                                @include('metrics.partials.workload_row', ['row' => $row, 'value' => $row->share_of_team])
                            @empty
                                <p class="text-muted mb-0">Keine Mitarbeiter im Zeitraum.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="q-card">
            <div class="q-card__head">
                <div>
                    <div class="q-card__title">Zeit bis Erledigung</div>
                    <div class="q-card__hint">Unterschrift → Bericht erledigt, nach Kunde</div>
                </div>
            </div>
            <div class="q-card__body">
                <div class="q-dualbar__legend">
                    <span class="q-chart-legend"><span class="q-chart-legend__dot" style="background:var(--q-accent)"></span>Ø Mittelwert</span>
                    <span class="q-chart-legend"><span class="q-chart-legend__dot" style="background:var(--q-faint)"></span>Median</span>
                </div>
                <div class="q-hbars q-scroll-cap">
                    @forelse($completionByCustomer as $row)
                        @include('metrics.partials.dualbar_row', ['label' => $row->label, 'mean' => $row->mean, 'median' => $row->median, 'maxMean' => $completionMaxMean, 'unit' => 'Tage'])
                    @empty
                        <p class="text-muted mb-0">Keine Daten im Zeitraum.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- ============================= ROW 4: accounting breakdown + logbook ============================= --}}
    @php
        $accDimensions = ['total' => null, 'company' => 'company', 'project' => 'project', 'employee' => 'employee'];
        $accData = collect($accDimensions)->map(fn ($dimension) => $metrics->topNWithRest($metrics->accountingBreakdown($dimension), 6, 'value'));

        $logByVehicle = $metrics->topNWithRest($metrics->logbookDistanceByVehicle(), 6, 'kilometres');
        $logByCustomer = $metrics->topNWithRest($metrics->logbookDistanceByCustomer(), 6, 'kilometres');
        $logByEmployee = $metrics->topNWithRest($metrics->logbookDistanceByEmployee(), 6, 'kilometres');
        $logMax = collect([$logByVehicle, $logByCustomer, $logByEmployee])->map(fn ($c) => $c->max('kilometres') ?? 0)->max();
    @endphp
    <div class="q-chartgrid">
        <div class="q-card">
            <div class="q-card__head">
                <div>
                    <div class="q-card__title">Abrechnungsarten</div>
                    <div class="q-card__hint">Anteil am Gesamtumsatz</div>
                </div>
                <ul class="nav nav-pills" id="acc-tabs" role="tablist">
                    <li class="nav-item" role="presentation"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#acc-total" type="button" role="tab">Gesamt</button></li>
                    <li class="nav-item" role="presentation"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#acc-company" type="button" role="tab">Kunde</button></li>
                    <li class="nav-item" role="presentation"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#acc-project" type="button" role="tab">Projekt</button></li>
                    <li class="nav-item" role="presentation"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#acc-employee" type="button" role="tab">Mitarbeiter</button></li>
                </ul>
            </div>
            <div class="q-card__body">
                <div class="tab-content">
                    @foreach($accData as $key => $items)
                        <div class="tab-pane fade {{ $key === 'total' ? 'show active' : '' }}" id="acc-{{ $key }}" role="tabpanel">
                            @include('metrics.partials.donut', ['items' => $items])
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="q-card">
            <div class="q-card__head">
                <div>
                    <div class="q-card__title">Fahrtenbuch – Strecken</div>
                    <div class="q-card__hint">Firmenfahrzeuge, ohne Privatfahrten</div>
                </div>
                <ul class="nav nav-pills" id="log-tabs" role="tablist">
                    <li class="nav-item" role="presentation"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#log-vehicle" type="button" role="tab">Fahrzeug</button></li>
                    <li class="nav-item" role="presentation"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#log-customer" type="button" role="tab">Kunde</button></li>
                    <li class="nav-item" role="presentation"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#log-employee" type="button" role="tab">Mitarbeiter</button></li>
                </ul>
            </div>
            <div class="q-card__body">
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="log-vehicle" role="tabpanel">
                        <div class="q-hbars">
                            @forelse($logByVehicle as $row)
                                @include('metrics.partials.hbar_row', ['label' => $row->label, 'value' => number_format($row->kilometres, 0, ',', '.').' km', 'percentage' => $logMax > 0 ? round($row->kilometres / $logMax * 100, 1) : 0, 'color' => $row->label === 'Sonstige' ? null : 'var(--q-violet)', 'icon' => $row->label === 'Sonstige' ? null : 'truck'])
                            @empty
                                <p class="text-muted mb-0">Keine Fahrten im Zeitraum.</p>
                            @endforelse
                        </div>
                    </div>
                    <div class="tab-pane fade" id="log-customer" role="tabpanel">
                        <div class="q-hbars">
                            @forelse($logByCustomer as $row)
                                @include('metrics.partials.hbar_row', ['label' => $row->label, 'value' => number_format($row->kilometres, 0, ',', '.').' km', 'percentage' => $logMax > 0 ? round($row->kilometres / $logMax * 100, 1) : 0, 'color' => $row->label === 'Sonstige' ? null : 'var(--q-violet)'])
                            @empty
                                <p class="text-muted mb-0">Keine Fahrten im Zeitraum.</p>
                            @endforelse
                        </div>
                    </div>
                    <div class="tab-pane fade" id="log-employee" role="tabpanel">
                        <div class="q-hbars">
                            @forelse($logByEmployee as $row)
                                @include('metrics.partials.hbar_row', ['label' => $row->label, 'value' => number_format($row->kilometres, 0, ',', '.').' km', 'percentage' => $logMax > 0 ? round($row->kilometres / $logMax * 100, 1) : 0, 'color' => $row->label === 'Sonstige' ? null : 'var(--q-violet)'])
                            @empty
                                <p class="text-muted mb-0">Keine Fahrten im Zeitraum.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
