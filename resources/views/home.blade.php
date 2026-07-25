@extends('layouts.app')

@section('content')
    <div class="q-container">

        {{-- Greeting --}}
        <div class="q-page-head">
            <div>
                {{-- Desktop: the app bar doesn't exist there, so "Übersicht" only
                     appears here — full greeting sentence as the title. --}}
                <div class="q-eyebrow d-none d-md-block">Übersicht</div>
                <h1 class="q-title d-none d-md-block">{{ DateTimeHelpers::greetingForTimeOfDay(\Carbon\Carbon::now()) }}, {{ Auth::user()->employee->person->first_name }} 👋</h1>
                {{-- Mobile: the app bar's section label already says "Übersicht"
                     (partials/navbar.blade.php), so repurpose this eyebrow as the
                     time-of-day greeting instead of repeating it, and shorten the
                     bold title to just the name — also helps it stay on one line. --}}
                <div class="q-eyebrow d-md-none">{{ DateTimeHelpers::greetingForTimeOfDay(\Carbon\Carbon::now()) }}</div>
                <h1 class="q-title d-md-none">{{ Auth::user()->employee->person->first_name }} 👋</h1>
            </div>
            <div class="d-flex gap-2">
                @can('accounting.view.own')
                    <a href="{{ route('accounting.index') }}" class="btn btn-primary text-white d-inline-flex align-items-center gap-2">
                        <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#clock"></use></svg>
                        Leistung erfassen
                    </a>
                @endcan
                @can('create', \App\Models\Task::class)
                    <a href="{{ route('tasks.create') }}" class="btn q-btn d-inline-flex align-items-center gap-2">
                        <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#plus"></use></svg>
                        Aufgabe anlegen
                    </a>
                @endcan
            </div>
        </div>

        {{-- ===== Leistungen diesen Monat ===== --}}
        @can('accounting.view.own')
            <div class="q-section-label">
                <span>Leistungen diesen Monat</span>
                <span class="hint">{{ \Carbon\Carbon::now()->translatedFormat('F Y') }}</span>
            </div>

            <div class="q-tiles q-tiles--5">
                <a href="{{ route('accounting.index') }}" class="q-card q-tile">
                    <div class="q-tile__head">
                        <span class="q-tile__label">Stunden</span>
                        <span class="q-tile__icon"><svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#clock"></use></svg></span>
                    </div>
                    <div class="q-tile__value">{{ Number::toLocal($employeeMtdHourlyBasedServices) }}</div>
                    <div class="q-tile__sub">geleistet</div>
                </a>

                <a href="{{ route('accounting.index') }}" class="q-card q-tile">
                    <div class="q-tile__head">
                        <span class="q-tile__label">Diäten</span>
                        <span class="q-tile__icon"><svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#wallet2"></use></svg></span>
                    </div>
                    <div class="q-tile__value">{{ Number::toLocal($employeeMtdAllowances) }}</div>
                    <div class="q-tile__sub">{{ Number::toLocal($employeeMtdAllowancesInCurrency) }}€</div>
                </a>

                <a href="{{ route('accounting.index') }}" class="q-card q-tile">
                    <div class="q-tile__head">
                        <span class="q-tile__label">Überstunden</span>
                        <span class="q-tile__icon"><svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#plus-circle"></use></svg></span>
                    </div>
                    <div class="q-tile__value">{{ Number::toLocal($employeeMtdOvertime) }}</div>
                    <div class="q-tile__sub">{{ Number::toLocal($employeeMtdOvertime50) }} 50% · {{ Number::toLocal($employeeMtdOvertime100) }} 100%</div>
                </a>

                <a href="{{ route('logbook.index') }}" class="q-card q-tile">
                    <div class="q-tile__head">
                        <span class="q-tile__label">Kilometer</span>
                        <span class="q-tile__icon"><svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#truck"></use></svg></span>
                    </div>
                    <div class="q-tile__value">{{ Number::toLocal($employeeMtdKilometres) }}</div>
                    <div class="q-tile__sub">{{ Number::toLocal($employeeMtdCompanyKilometres) }} Firma · {{ Number::toLocal($employeeMtdPrivateKilometres) }} privat ({{ Number::toLocal($employeeMtdPrivateKilometresInCurrency) }}€)</div>
                </a>

                <div class="q-card q-tile">
                    <div class="q-tile__head">
                        <span class="q-tile__label">Urlaub</span>
                        <span class="q-tile__icon"><svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#sun"></use></svg></span>
                    </div>
                    <div class="q-tile__value">{{ Number::toLocal($employeeHolidays) }}<span class="q-tile__unit">Tage</span></div>
                    <div class="q-tile__sub">verfügbar</div>
                </div>
            </div>
        @endcan

        {{-- ===== Aufgaben ===== --}}
        <div class="q-section-label">
            <span>Aufgaben</span>
            <span class="hint">ver. = verantwortlich · bet. = beteiligt · MTD = seit Monatsbeginn</span>
        </div>

        <div class="q-tiles q-tiles--4">
            <a href="{{ route('tasks.index', ['sort' => 'due_on-desc']) }}" class="q-card q-tile">
                <div class="q-tile__head">
                    <span class="q-tile__label">Erstellt MTD</span>
                    <span class="q-tile__icon"><svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#plus-square"></use></svg></span>
                </div>
                <div class="q-tile__value">{{ Number::toLocal($employeeMtdCreatedTasks) }}</div>
                <div class="q-tile__sub">{{ Number::toLocal($employeeMtdCreatedTasksResponsibleFor) }} ver. · {{ Number::toLocal($employeeMtdCreatedTasksInvolvedIn) }} bet.</div>
            </a>

            <a href="{{ route('tasks.index', ['search' => 'ist:erledigt', 'sort' => 'due_on-desc']) }}" class="q-card q-tile">
                <div class="q-tile__head">
                    <span class="q-tile__label">Erledigt MTD</span>
                    <span class="q-tile__icon"><svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#check2-square"></use></svg></span>
                </div>
                <div class="q-tile__value">{{ Number::toLocal($employeeMtdFinishedTasks) }}</div>
                <div class="q-tile__sub">{{ Number::toLocal($employeeMtdFinishedTasksResponsibleFor) }} ver. · {{ Number::toLocal($employeeMtdFinishedTasksInvolvedIn) }} bet.</div>
            </a>

            <a href="{{ route('tasks.index', ['search' => 'ist:überfällig', 'sort' => 'due_on-desc']) }}" @class(['q-card', 'q-tile', 'q-tile--danger' => $employeeOverdueTasks])>
                <div class="q-tile__head">
                    <span class="q-tile__label">Überfällig</span>
                    <span class="q-tile__icon"><svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#calendar-x"></use></svg></span>
                </div>
                <div class="q-tile__value">{{ Number::toLocal($employeeOverdueTasks) }}</div>
                <div class="q-tile__sub">{{ Number::toLocal($employeeOverdueTasksResponsibleFor) }} ver. · {{ Number::toLocal($employeeOverdueTasksInvolvedIn) }} bet.</div>
            </a>

            <a href="{{ route('tasks.index', ['search' => 'ist:bald_fällig', 'sort' => 'due_on-desc']) }}" @class(['q-card', 'q-tile', 'q-tile--warning' => $employeeDueSoonTasks])>
                <div class="q-tile__head">
                    <span class="q-tile__label">Bald fällig</span>
                    <span class="q-tile__icon"><svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#calendar-day"></use></svg></span>
                </div>
                <div class="q-tile__value">{{ Number::toLocal($employeeDueSoonTasks) }}</div>
                <div class="q-tile__sub">{{ Number::toLocal($employeeDueSoonTasksResponsibleFor) }} ver. · {{ Number::toLocal($employeeDueSoonTasksInvolvedIn) }} bet.</div>
            </a>
        </div>

        {{-- ===== Berichte & Lieferscheine (matrix) ===== --}}
        @php $showActivity = Auth::user()->can('tools-viewlatestchanges'); @endphp

        <div @class(['q-dash-cols' => $showActivity])>
            {{-- Report matrix --}}
            <div>
                <div class="q-section-label">
                    <span>Berichte und Lieferscheine</span>
                    <span class="hint">offen = dir zugeordnet</span>
                </div>
                @if($totalErledigbar)
                    <div class="q-banner">
                        <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#check2-square"></use></svg>
                        <span class="q-banner__count">{{ Number::toLocal($totalErledigbar) }}</span>
                        <span class="q-banner__label">{{ trans_choice('wartet auf Erledigung|warten auf Erledigung', $totalErledigbar) }}</span>
                    </div>
                @endif
                <div class="q-card">
                    <div @class(['q-matrix__grid', 'q-matrix__grid--3col' => !$showErledigbarColumn, 'q-matrix__head'])>
                        <span class="q-matrix__h">Typ</span>
                        <span class="q-matrix__h num">Offen</span>
                        <span class="q-matrix__h num">Offen gesamt</span>
                        @if($showErledigbarColumn)
                            <span class="q-matrix__h num">Erledigbar</span>
                        @endif
                    </div>

                    @foreach($reportRows as $r)
                        <a href="{{ $r['route'] }}" @class(['q-matrix__grid', 'q-matrix__grid--3col' => !$showErledigbarColumn, 'q-matrix__row'])>
                            <span class="q-matrix__name">
                                <span @class(['q-ab', 'q-ab--accent' => ($r['accent'] ?? false)])>{{ $r['ab'] }}</span>
                                {{-- Chip-only on mobile: full names (e.g. "Prüfberichte
                                     Durchflussmesseinrichtungen") truncate hard against the
                                     three fixed-width number columns on a narrow screen —
                                     the 2-letter chip already identifies the row, desktop
                                     keeps both since it has the room (2026-07-24). --}}
                                <span class="d-none d-md-inline">{{ $r['name'] }}</span>
                            </span>
                            @if(!is_null($r['offen']))
                                <span class="num num--offen">{{ Number::toLocal($r['offen']) }}</span>
                            @else
                                <span class="num num--none">–</span>
                            @endif
                            @if(!is_null($r['gesamt']))
                                <span class="num num--muted">{{ Number::toLocal($r['gesamt']) }}</span>
                            @else
                                <span class="num num--none">–</span>
                            @endif
                            @if($showErledigbarColumn)
                                @if(!is_null($r['erledigbar']))
                                    <span @class(['num', 'num--action', 'is-zero' => !$r['erledigbar']])>{{ Number::toLocal($r['erledigbar']) }}</span>
                                @else
                                    <span class="num num--none">–</span>
                                @endif
                            @endif
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- Activity feed --}}
            @if($showActivity)
                <div>
                    <div class="q-section-label">
                        <span>Letzte Aktivität</span>
                        <a href="{{ route('latest-changes.index') }}" class="q-link ms-auto">Alle</a>
                    </div>
                    <div class="q-card">
                        <div class="q-activity">
                            @forelse($latestChanges as $change)
                                <a href="{{ $change->route }}" class="q-activity__item">
                                    <span class="q-activity__icon">@component('partials.model_icon', ['model' => $change->model])@endcomponent</span>
                                    <span class="q-activity__body">
                                        <span class="q-activity__title">{{ $change->name }}</span>
                                        <span class="q-activity__meta">{{ $change->type }} · {{ $change->updated_at->diffForHumans() }}</span>
                                    </span>
                                </a>
                            @empty
                                <div class="q-activity__empty">Keine kürzlichen Änderungen</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
