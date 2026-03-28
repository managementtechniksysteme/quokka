@extends('layouts.app')

@section('content')
    <div class="bg-gray-100 mt-0">
        <div class="container py-4">
            <h3>
                <svg class="icon-bs icon-baseline text-muted me-1">
                    <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#{{ DateTimeHelpers::iconStringForTimeOfDay(\Carbon\Carbon::now()) }}"></use>
                </svg>
                Übersicht - {{ DateTimeHelpers::greetingForTimeOfDay(\Carbon\Carbon::now()) }}, {{ Auth::user()->employee->person->first_name }}!
            </h3>
        </div>
    </div>

    <div class="container py-4">
        @can('accounting.view.own')
            <h4>
                <svg class="icon-bs icon-baseline text-muted me-1">
                    <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#cpu"></use>
                </svg>
                Leistungen diesen Monat
            </h4>

            <div class="row">
                <div class="col-md-6 col-xl-3 mb-4">
                    <div class="card shadow-sm">
                        <a class="stretched-link outline-none" href="{{ route('accounting.index') }}"></a>
                        <div class="card-body">
                            <div class="row">
                                <div class="col pe-0">
                                    <h5 class="card-title text-uppercase text-muted m-0">Stunden</h5>
                                    <span class="h2 fw-bold m-0">{{ Number::format($employeeMtdHourlyBasedServices) }}</span>
                                </div>
                                <div class="col-auto ps-0">
                                    <svg class="icon-bs icon-32 text-gray-500">
                                        <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#clock"></use>
                                    </svg>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col">
                                    <span class="text-muted">&nbsp;</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-3 mb-4">
                    <div class="card shadow-sm">
                        <a class="stretched-link outline-none" href="{{ route('accounting.index') }}"></a>
                        <div class="card-body">
                            <div class="row">
                                <div class="col pe-0">
                                    <h5 class="card-title text-uppercase text-muted m-0">Diäten</h5>
                                    <span class="h2 fw-bold m-0">{{ Number::format($employeeMtdAllowances) }}</span>
                                </div>
                                <div class="col-auto ps-0">
                                    <svg class="icon-bs icon-32 text-gray-500">
                                        <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#wallet2"></use>
                                    </svg>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col">
                                    <span class="text-muted">{{ Number::format($employeeMtdAllowancesInCurrency) }}€</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-3 mb-4">
                    <div class="card shadow-sm">
                        <a class="stretched-link outline-none" href="{{ route('accounting.index') }}"></a>
                        <div class="card-body">
                            <div class="row">
                                <div class="col pe-0">
                                    <h5 class="card-title text-uppercase text-muted m-0">Überstunden</h5>
                                    <span class="h2 fw-bold m-0">{{ Number::format($employeeMtdOvertime) }}</span>
                                </div>
                                <div class="col-auto ps-0">
                                    <svg class="icon-bs icon-32 text-gray-500">
                                        <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#plus-circle"></use>
                                    </svg>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col">
                                    <span class="text-muted">
                                        {{ Number::format($employeeMtdOvertime50) }} 50%
                                        <svg class="icon-bs icon-baseline text-muted">
                                            <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#dot"></use>
                                        </svg>
                                        {{ Number::format($employeeMtdOvertime100) }} 100%
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-3 mb-4">
                    <div class="card shadow-sm">
                        <a class="stretched-link outline-none" href="{{ route('logbook.index') }}"></a>
                        <div class="card-body">
                            <div class="row">
                                <div class="col pe-0">
                                    <h5 class="card-title text-uppercase text-muted m-0">Kilometer</h5>
                                    <span class="h2 fw-bold m-0">{{ Number::format($employeeMtdKilometres) }}</span>
                                </div>
                                <div class="col-auto ps-0">
                                    <svg class="icon-bs icon-32 text-gray-500">
                                        <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#truck"></use>
                                    </svg>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col">
                                    <span class="text-muted">
                                        {{ Number::format($employeeMtdCompanyKilometres) }} Firma
                                        <svg class="icon-bs icon-baseline text-muted">
                                            <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#dot"></use>
                                        </svg>
                                        {{ Number::format($employeeMtdPrivateKilometres) }} privat ({{ Number::format($employeeMtdPrivateKilometresInCurrency) }}€)
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-3 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted m-0">Verf. Urlaub</h5>
                                    <span class="h2 fw-bold m-0">{{ Number::format($employeeHolidays) }}{{ \App\Models\ApplicationSettings::get()->holidayService->unit_string }}</span>
                                </div>
                                <div class="col-auto ps-0">
                                    <svg class="icon-bs icon-32 text-gray-500">
                                        <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#sun"></use>
                                    </svg>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col">
                                    <span class="text-muted">&nbsp;</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endcan

        <h4>
            <svg class="icon-bs icon-baseline text-muted me-1">
                <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#check2-square"></use>
            </svg>
            Aufgaben
        </h4>

        <div class="row">
            <div class="col-md-6 col-xl-3 mb-4">
                <div class="card shadow-sm">
                    <a class="stretched-link outline-none" href="{{ route('tasks.index', ['sort' => 'due_on-desc']) }}"></a>
                    <div class="card-body">
                        <div class="row">
                            <div class="col pe-0">
                                <h5 class="card-title text-uppercase text-muted m-0">Erstellt MTD</h5>
                                <span class="h2 fw-bold m-0">{{ Number::format($employeeMtdCreatedTasks) }}</span>
                            </div>
                            <div class="col-auto ps-0">
                                <svg class="icon-bs icon-32 @if($employeeMtdCreatedTasks) text-blue-500 @else text-gray-500 @endif">
                                    <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#plus-square"></use>
                                </svg>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col">
                                <span class="text-muted">
                                    {{ Number::format($employeeMtdCreatedTasksResponsibleFor) }} ver.
                                    <svg class="icon-bs icon-baseline text-muted">
                                        <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#dot"></use>
                                    </svg>
                                    {{ Number::format($employeeMtdCreatedTasksInvolvedIn) }}  bet.
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3 mb-4">
                <div class="card shadow-sm">
                    <a class="stretched-link outline-none" href="{{ route('tasks.index', ['search' => 'ist:erledigt', 'sort' => 'due_on-desc']) }}"></a>
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h5 class="card-title text-uppercase text-muted m-0">Erledigt MTD</h5>
                                <span class="h2 fw-bold m-0">{{ Number::format($employeeMtdFinishedTasks) }}</span>
                            </div>
                            <div class="col-auto ps-0">
                                <svg class="icon-bs icon-32 @if($employeeMtdFinishedTasks) text-green-500 @else text-gray-500 @endif">
                                    <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#check2-square"></use>
                                </svg>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col">
                                <span class="text-muted">
                                    {{ Number::format($employeeMtdFinishedTasksResponsibleFor) }} ver.
                                    <svg class="icon-bs icon-baseline text-muted">
                                        <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#dot"></use>
                                    </svg>
                                    {{ Number::format($employeeMtdFinishedTasksInvolvedIn) }} bet.
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3 mb-4">
                <div class="card shadow-sm">
                    <a class="stretched-link outline-none" href="{{ route('tasks.index', ['search' => 'ist:überfällig', 'sort' => 'due_on-desc']) }}"></a>
                    <div class="card-body">
                        <div class="row">
                            <div class="col pe-0">
                                <h5 class="card-title text-uppercase text-muted m-0">Überfällig</h5>
                                <span class="h2 fw-bold m-0">{{ Number::format($employeeOverdueTasks) }}</span>
                            </div>
                            <div class="col-auto ps-0">
                                <svg class="icon-bs icon-32 text-gray-500 @if($employeeOverdueTasks) text-red-500 @else text-gray-500 @endif">
                                    <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#calendar-x"></use>
                                </svg>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col">
                                <span class="text-muted">
                                    {{ Number::format($employeeOverdueTasksResponsibleFor) }} ver.
                                    <svg class="icon-bs icon-baseline text-muted">
                                        <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#dot"></use>
                                    </svg>
                                    {{ Number::format($employeeOverdueTasksInvolvedIn) }} bet.
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3 mb-4">
                <div class="card shadow-sm">
                    <a class="stretched-link outline-none" href="{{ route('tasks.index', ['search' => 'ist:bald_fällig', 'sort' => 'due_on-desc']) }}"></a>
                    <div class="card-body">
                        <div class="row">
                            <div class="col pe-0">
                                <h5 class="card-title text-uppercase text-muted m-0">Bald fällig</h5>
                                <span class="h2 fw-bold m-0">{{ Number::format($employeeDueSoonTasks) }}</span>
                            </div>
                            <div class="col-auto ps-0">
                                <svg class="icon-bs icon-32 @if($employeeDueSoonTasks) text-yellow-500 @else text-gray-500 @endif">
                                    <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#calendar-day"></use>
                                </svg>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col">
                                <span class="text-muted">
                                    {{ Number::format($employeeDueSoonTasksResponsibleFor) }} ver.
                                    <svg class="icon-bs icon-baseline text-muted">
                                        <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#dot"></use>
                                    </svg>
                                    {{ Number::format($employeeDueSoonTasksInvolvedIn) }} bet.
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <p class="small">
            <span class="fw-bold">Legende:</span>
            ver. - verantwortlich für die Aufgabe,
            bet. - an der Aufgabe beteiligt,
            MTD - Month to Date (seit Monatsbeginn)
        </p>

        <h4 class="mt-4">
            <svg class="icon-bs icon-baseline text-muted me-1">
                <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#card-text"></use>
            </svg>
            Berichte
        </h4>

        <div class="row">
            <div class="col-md-6 col-xl-3 mb-4">
                <div class="card shadow-sm">
                    <a class="stretched-link outline-none" href="{{ route('service-reports.index', ['search' => 'ist:neu']) }}"></a>
                    <div class="card-body">
                        <div class="row">
                            <div class="col pe-0">
                                <h5 class="card-title text-uppercase text-muted m-0">offene SB</h5>
                                <span class="h2 fw-bold m-0">{{ Number::format($employeeNewServiceReports) }}</span>
                            </div>
                            <div class="col-auto ps-0">
                                <svg class="icon-bs icon-32 @if($employeeNewServiceReports) text-blue-500 @else text-gray-500 @endif">
                                    <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#gear"></use>
                                </svg>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col">
                                <span class="text-muted">
                                    {{ Number::format($employeeMtdNewServiceReports) }} MTD
                                    @if(Auth::user()->can('service-reports.view.own') && Auth::user()->can('service-reports.view.other'))
                                        <svg class="icon-bs icon-baseline text-muted">
                                            <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#dot"></use>
                                        </svg>
                                        {{ Number::format($newServiceReports) }} ges.
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3 mb-4">
                <div class="card shadow-sm">
                    <a class="stretched-link outline-none" href="{{ route('additions-reports.index', ['search' => 'ist:neu']) }}"></a>
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h5 class="card-title text-uppercase text-muted m-0">offene RB</h5>
                                <span class="h2 fw-bold m-0">{{ Number::format($employeeNewAdditionsReports) }}</span>
                            </div>
                            <div class="col-auto ps-0">
                                <svg class="icon-bs icon-32 @if($employeeNewAdditionsReports) text-blue-500 @else text-gray-500 @endif">
                                    <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#tools"></use>
                                </svg>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col">
                                <span class="text-muted">
                                    {{ Number::format($employeeMtdNewAdditionsReports) }} MTD
                                    @if(Auth::user()->can('additions-reports.view.own') && Auth::user()->can('additions-reports.view.involved'))
                                        <svg class="icon-bs icon-baseline text-muted">
                                            <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#dot"></use>
                                        </svg>
                                        {{ Number::format($employeeNewAdditionsReportsInvolvedIn) }} bet.
                                    @endif
                                    @if(Auth::user()->can('additions-reports.view.own') && Auth::user()->can('additions-reports.view.involved') && Auth::user()->can('additions-reports.view.other'))
                                        <svg class="icon-bs icon-baseline text-muted">
                                            <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#dot"></use>
                                        </svg>
                                        {{ Number::format($newAdditionsReports) }} ges.
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3 mb-4">
                <div class="card shadow-sm">
                    <a class="stretched-link outline-none" href="{{ route('inspection-reports.index', ['search' => 'ist:neu']) }}"></a>
                    <div class="card-body">
                        <div class="row">
                            <div class="col pe-0">
                                <h5 class="card-title text-uppercase text-muted m-0">offene PB</h5>
                                <span class="h2 fw-bold m-0">{{ Number::format($employeeNewInspectionReports) }}</span>
                            </div>
                            <div class="col-auto ps-0">
                                <svg class="icon-bs icon-32 @if($employeeNewInspectionReports) text-blue-500 @else text-gray-500 @endif">
                                    <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#patch-check"></use>
                                </svg>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col">
                                <span class="text-muted">
                                    {{ Number::format($employeeMtdNewInspectionReports) }} MTD
                                    @if(Auth::user()->can('inspection-reports.view.own') && Auth::user()->can('inspection-reports.view.other'))
                                        <svg class="icon-bs icon-baseline text-muted">
                                            <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#dot"></use>
                                        </svg>
                                        {{ Number::format($newInspectionReports) }} ges.
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3 mb-4">
                <div class="card shadow-sm">
                    <a class="stretched-link outline-none" href="{{ route('construction-reports.index', ['search' => 'ist:neu']) }}"></a>
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h5 class="card-title text-uppercase text-muted m-0">offene BT</h5>
                                <span class="h2 fw-bold m-0">{{ Number::format($employeeNewConstructionReports) }}</span>
                            </div>
                            <div class="col-auto ps-0">
                                <svg class="icon-bs icon-32 @if($employeeNewConstructionReports) text-blue-500 @else text-gray-500 @endif">
                                    <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#hammer"></use>
                                </svg>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col">
                                <span class="text-muted">
                                    {{ Number::format($employeeMtdNewConstructionReports) }} MTD
                                    @if(Auth::user()->can('construction-reports.view.own') && Auth::user()->can('construction-reports.view.involved'))
                                        <svg class="icon-bs icon-baseline text-muted">
                                            <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#dot"></use>
                                        </svg>
                                        {{ Number::format($employeeNewConstructionReportsInvolvedIn) }} bet.
                                    @endif
                                    @if(Auth::user()->can('construction-reports.view.own') && Auth::user()->can('construction-reports.view.involved') && Auth::user()->can('construction-reports.view.other'))
                                        <svg class="icon-bs icon-baseline text-muted">
                                            <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#dot"></use>
                                        </svg>
                                        {{ Number::format($newConstructionReports) }} ges.
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 col-xl-3 mb-4">
                <div class="card shadow-sm">
                    <a class="stretched-link outline-none" href="{{ route('flow-meter-inspection-reports.index', ['search' => 'ist:neu']) }}"></a>
                    <div class="card-body">
                        <div class="row">
                            <div class="col pe-0">
                                <h5 class="card-title text-uppercase text-muted m-0">offene DM</h5>
                                <span class="h2 fw-bold m-0">{{ Number::format($employeeNewFlowMeterInspectionReports) }}</span>
                            </div>
                            <div class="col-auto ps-0">
                                <svg class="icon-bs icon-32 @if($employeeNewFlowMeterInspectionReports) text-blue-500 @else text-gray-500 @endif">
                                    <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#patch-check"></use>
                                </svg>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col">
                                <span class="text-muted">
                                    {{ Number::format($employeeMtdNewFlowMeterInspectionReports) }} MTD
                                    @if(Auth::user()->can('inspection-reports.view.own') && Auth::user()->can('flow-meter-inspection-reports.view.other'))
                                        <svg class="icon-bs icon-baseline text-muted">
                                            <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#dot"></use>
                                        </svg>
                                        {{ Number::format($newFlowMeterInspectionReports) }} ges.
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if(Auth::user()->can('service-reports.approve') || Auth::user()->can('additions-reports.approve') || Auth::user()->can('inspection-reports.approve') || Auth::user()->can('construction-reports.approve'))
            <div class="row">
                @can('service-reports.approve')
                    <div class="col-md-6 col-xl-3 mb-4">
                        <div class="card shadow-sm">
                            <a class="stretched-link outline-none" href="{{ route('service-reports.index', ['search' => 'ist:unterschrieben']) }}"></a>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col pe-0">
                                        <h5 class="card-title text-uppercase text-muted m-0">erledigbare SB</h5>
                                        <span class="h2 fw-bold m-0">{{ Number::format($signedServiceReports) }}</span>
                                    </div>
                                    <div class="col-auto ps-0">
                                        <svg class="icon-bs icon-32 @if($signedServiceReports) text-yellow-500 @else text-gray-500 @endif">
                                            <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#gear"></use>
                                        </svg>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col">
                                        <span class="text-muted">{{ Number::format($mtdSignedServiceReports) }} MTD</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endcan
                @can('additions-reports.approve')
                    <div class="col-md-6 col-xl-3 mb-4">
                        <div class="card shadow-sm">
                            <a class="stretched-link outline-none" href="{{ route('additions-reports.index', ['search' => 'ist:unterschrieben']) }}"></a>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <h5 class="card-title text-uppercase text-muted m-0">erledigbare RB</h5>
                                        <span class="h2 fw-bold m-0">{{ Number::format($signedAdditionsReports) }}</span>
                                    </div>
                                    <div class="col-auto ps-0">
                                        <svg class="icon-bs icon-32 @if($signedAdditionsReports) text-yellow-500 @else text-gray-500 @endif">
                                            <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#tools"></use>
                                        </svg>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col">
                                        <span class="text-muted">{{ Number::format($mtdSignedAdditionsReports) }} MTD</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endcan
                @can('inspection-reports.approve')
                    <div class="col-md-6 col-xl-3 mb-4">
                        <div class="card shadow-sm">
                            <a class="stretched-link outline-none" href="{{ route('inspection-reports.index', ['search' => 'ist:unterschrieben']) }}"></a>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col pe-0">
                                        <h5 class="card-title text-uppercase text-muted m-0">erledigbare PB</h5>
                                        <span class="h2 fw-bold m-0">{{ Number::format($signedInspectionReports) }}</span>
                                    </div>
                                    <div class="col-auto ps-0">
                                        <svg class="icon-bs icon-32 @if($signedInspectionReports) text-yellow-500 @else text-gray-500 @endif">
                                            <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#patch-check"></use>
                                        </svg>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col">
                                        <span class="text-muted">{{ Number::format($mtdSignedInspectionReports) }} MTD</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endcan
                @can('construction-reports.approve')
                    <div class="col-md-6 col-xl-3 mb-4">
                        <div class="card shadow-sm">
                            <a class="stretched-link outline-none" href="{{ route('construction-reports.index', ['search' => 'ist:unterschrieben']) }}"></a>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <h5 class="card-title text-uppercase text-muted m-0">erledigbare BT</h5>
                                        <span class="h2 fw-bold m-0">{{ Number::format($signedConstructionReports) }}</span>
                                    </div>
                                    <div class="col-auto ps-0">
                                        <svg class="icon-bs icon-32 @if($signedConstructionReports) text-yellow-500 @else text-gray-500 @endif">
                                            <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#hammer"></use>
                                        </svg>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col">
                                        <span class="text-muted">{{ Number::format($mtdSignedConstructionReports) }} MTD</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endcan
            </div>

            <div class="row">
                @can('flow-meter-inspection-reports.approve')
                    <div class="col-md-6 col-xl-3 mb-4">
                        <div class="card shadow-sm">
                            <a class="stretched-link outline-none" href="{{ route('flow-meter-inspection-reports.index', ['search' => 'ist:unterschrieben']) }}"></a>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col pe-0">
                                        <h5 class="card-title text-uppercase text-muted m-0">erledigbare DM</h5>
                                        <span class="h2 fw-bold m-0">{{ Number::format($signedFlowMeterInspectionReports) }}</span>
                                    </div>
                                    <div class="col-auto ps-0">
                                        <svg class="icon-bs icon-32 @if($signedFlowMeterInspectionReports) text-yellow-500 @else text-gray-500 @endif">
                                            <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#patch-check"></use>
                                        </svg>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col">
                                        <span class="text-muted">{{ Number::format($mtdSignedFlowMeterInspectionReports) }} MTD</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endcan
            </div>
        @endcan

        <p class="small">
            <span class="fw-bold">Legende:</span>
            SB - Serviceberichte,
            RB - Regieberichte,
            PB - Prüfberichte,
            BT - Bautagesberichte,
            DM - Prüfberichte für Durchflussmesseinrichtungen,
            MTD - Month to Date (seit Monatsbeginn)@if(Auth::user()->can('additions-reports.view.involved') || Auth::user()->can('construction-reports.view.involved')),
            bet. - beteiligt{{''}}@endif{{''}}@if(Auth::user()->can('service-reports.view.other') || Auth::user()->can('additions-reports.view.other') || Auth::user()->can('inspection-reports.view.other') || Auth::user()->can('construction-reports.view.other')),
            ges. - gesamt
            @endif
        </p>

        <h4 class="mt-4">
            <svg class="icon-bs icon-baseline text-muted me-1">
                <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#box-seam"></use>
            </svg>
            Lieferscheine
        </h4>

        <div class="row">
            @can('viewAny', \App\Models\DeliveryNote::class)
                <div class="col-md-6 col-xl-3 mb-4">
                    <div class="card shadow-sm">
                        <a class="stretched-link outline-none" href="{{ route('delivery-notes.index', ['search' => 'ist:neu']) }}"></a>
                        <div class="card-body">
                            <div class="row">
                                <div class="col pe-0">
                                    <h5 class="card-title text-uppercase text-muted m-0">offene LI</h5>
                                    <span class="h2 fw-bold m-0">{{ Number::format($newDeliveryNotes) }}</span>
                                </div>
                                <div class="col-auto ps-0">
                                    <svg class="icon-bs icon-32 @if($newDeliveryNotes) text-blue-500 @else text-gray-500 @endif">
                                        <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#box-seam"></use>
                                    </svg>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col">
                                    <span class="text-muted">
                                        {{ Number::format($mtdNewDeliveryNotes) }} MTD
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endcan

            @can('delivery-notes.approve')
                <div class="col-md-6 col-xl-3 mb-4">
                    <div class="card shadow-sm">
                        <a class="stretched-link outline-none" href="{{ route('delivery-notes.index', ['search' => 'ist:unterschrieben']) }}"></a>
                        <div class="card-body">
                            <div class="row">
                                <div class="col pe-0">
                                    <h5 class="card-title text-uppercase text-muted m-0">erledigbare LI</h5>
                                    <span class="h2 fw-bold m-0">{{ Number::format($signedDeliveryNotes) }}</span>
                                </div>
                                <div class="col-auto ps-0">
                                    <svg class="icon-bs icon-32 @if($signedDeliveryNotes) text-yellow-500 @else text-gray-500 @endif">
                                        <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#box-seam"></use>
                                    </svg>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col">
                                    <span class="text-muted">
                                        {{ Number::format($mtdSignedDeliveryNotes) }} MTD
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endcan
        </div>

        <p class="small">
            <span class="fw-bold">Legende:</span>
            LI - Lieferscheine,
            MTD - Month to Date (seit Monatsbeginn),
            ges. - gesamt
        </p>
    </div>
@endsection
