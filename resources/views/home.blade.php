@extends('layouts.app')

@section('content')
    <div class="bg-gray-100 mt-0">
        <div class="container py-4">
            <h3>
                <svg class="icon-bs icon-baseline text-muted mr-2">
                    <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#{{ DateTimeHelpers::iconStringForTimeOfDay(\Carbon\Carbon::now()) }}"></use>
                </svg>
                Übersicht - {{ DateTimeHelpers::greetingForTimeOfDay(\Carbon\Carbon::now()) }}, {{ Auth::user()->employee->person->first_name }}!
            </h3>
        </div>
    </div>

    <div class="container py-4">
        @can('accounting.view.own')
            <h4>
                <svg class="icon-bs icon-baseline text-muted mr-2">
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
                                <div class="col pr-0">
                                    <h5 class="card-title text-uppercase text-muted m-0">Stunden</h5>
                                    <span class="h2 font-weight-bold m-0">{{ Number::toLocal(Auth::user()->employee->mtd_hourly_based_services) }}</span>
                                </div>
                                <div class="col-auto pl-0">
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
                                <div class="col pr-0">
                                    <h5 class="card-title text-uppercase text-muted m-0">Diäten</h5>
                                    <span class="h2 font-weight-bold m-0">{{ Number::toLocal(Auth::user()->employee->mtd_allowances) }}</span>
                                </div>
                                <div class="col-auto pl-0">
                                    <svg class="icon-bs icon-32 text-gray-500">
                                        <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#wallet2"></use>
                                    </svg>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col">
                                    <span class="text-muted">{{ Number::toLocal(Auth::user()->employee->mtd_allowances_in_currency) }}€</span>
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
                                <div class="col pr-0">
                                    <h5 class="card-title text-uppercase text-muted m-0">Überstunden</h5>
                                    <span class="h2 font-weight-bold m-0">{{ Number::toLocal(Auth::user()->employee->mtd_overtime) }}</span>
                                </div>
                                <div class="col-auto pl-0">
                                    <svg class="icon-bs icon-32 text-gray-500">
                                        <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#plus-circle"></use>
                                    </svg>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col">
                                    <span class="text-muted">
                                        {{ Auth::user()->employee->mtd_overtime_50 }} 50%
                                        <svg class="icon-bs icon-baseline text-muted">
                                            <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#dot"></use>
                                        </svg>
                                        {{ Number::toLocal(Auth::user()->employee->mtd_overtime_100) }} 100%
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
                                    <span class="h2 font-weight-bold m-0">{{ Number::toLocal(Auth::user()->employee->holidays) }}{{ \App\Models\ApplicationSettings::get()->holidayService->unit_string }}</span>
                                </div>
                                <div class="col-auto pl-0">
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
            <svg class="icon-bs icon-baseline text-muted mr-2">
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
                            <div class="col pr-0">
                                <h5 class="card-title text-uppercase text-muted m-0">Erstellt MTD</h5>
                                <span class="h2 font-weight-bold m-0">{{ Number::toLocal(Auth::user()->employee->mtd_created_tasks) }}</span>
                            </div>
                            <div class="col-auto pl-0">
                                <svg class="icon-bs icon-32 @if(Auth::user()->employee->mtd_created_tasks) text-blue-500 @else text-gray-500 @endif">
                                    <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#plus-square"></use>
                                </svg>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col">
                                <span class="text-muted">
                                    {{ Number::toLocal(Auth::user()->employee->mtd_created_tasks_responsible_for) }} ver.
                                    <svg class="icon-bs icon-baseline text-muted">
                                        <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#dot"></use>
                                    </svg>
                                    {{ Number::toLocal(Auth::user()->employee->mtd_created_tasks_involved_in) }}  bet.
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
                                <span class="h2 font-weight-bold m-0">{{ Number::toLocal(Auth::user()->employee->mtd_finished_tasks) }}</span>
                            </div>
                            <div class="col-auto pl-0">
                                <svg class="icon-bs icon-32 @if(Auth::user()->employee->mtd_finished_tasks) text-green-500 @else text-gray-500 @endif">
                                    <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#check2-square"></use>
                                </svg>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col">
                                <span class="text-muted">
                                    {{ Number::toLocal(Auth::user()->employee->mtd_finished_tasks_responsible_for) }} ver.
                                    <svg class="icon-bs icon-baseline text-muted">
                                        <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#dot"></use>
                                    </svg>
                                    {{ Number::toLocal(Auth::user()->employee->mtd_finished_tasks_involved_in) }} bet.
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
                            <div class="col pr-0">
                                <h5 class="card-title text-uppercase text-muted m-0">Überfällig</h5>
                                <span class="h2 font-weight-bold m-0">{{ Number::toLocal(Auth::user()->employee->overdue_tasks) }}</span>
                            </div>
                            <div class="col-auto pl-0">
                                <svg class="icon-bs icon-32 text-gray-500 @if(Auth::user()->employee->overdue_tasks) text-red-500 @else text-gray-500 @endif">
                                    <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#calendar-x"></use>
                                </svg>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col">
                                <span class="text-muted">
                                    {{ Number::toLocal(Auth::user()->employee->overdue_tasks_responsible_for) }} ver.
                                    <svg class="icon-bs icon-baseline text-muted">
                                        <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#dot"></use>
                                    </svg>
                                    {{ Number::toLocal(Auth::user()->employee->overdue_tasks_involved_in) }} bet.
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
                            <div class="col pr-0">
                                <h5 class="card-title text-uppercase text-muted m-0">Bald fällig</h5>
                                <span class="h2 font-weight-bold m-0">{{ Number::toLocal(Auth::user()->employee->due_soon_tasks) }}</span>
                            </div>
                            <div class="col-auto pl-0">
                                <svg class="icon-bs icon-32 @if(Auth::user()->employee->due_soon_tasks) text-yellow-500 @else text-gray-500 @endif">
                                    <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#calendar-day"></use>
                                </svg>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col">
                                <span class="text-muted">
                                    {{ Number::toLocal(Auth::user()->employee->due_soon_tasks_responsible_for) }} ver.
                                    <svg class="icon-bs icon-baseline text-muted">
                                        <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#dot"></use>
                                    </svg>
                                    {{ Number::toLocal(Auth::user()->employee->due_soon_tasks_involved_in) }} bet.
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <p class="small">
            <span class="font-weight-bold">Legende:</span>
            ver. - verantwortlich für die Aufgabe,
            bet. - an der Aufgabe beteiligt,
            MTD - Month to Date (seit Monatsbeginn)
        </p>

        <h4 class="mt-4">
            <svg class="icon-bs icon-baseline text-muted mr-2">
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
                            <div class="col pr-0">
                                <h5 class="card-title text-uppercase text-muted m-0">offene SB</h5>
                                <span class="h2 font-weight-bold m-0">{{ Number::toLocal(Auth::user()->employee->new_service_reports) }}</span>
                            </div>
                            <div class="col-auto pl-0">
                                <svg class="icon-bs icon-32 @if(Auth::user()->employee->new_service_reports) text-blue-500 @else text-gray-500 @endif">
                                    <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#gear"></use>
                                </svg>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col">
                                <span class="text-muted">
                                    {{ Number::toLocal(Auth::user()->employee->mtd_new_service_reports) }} MTD
                                    @if(Auth::user()->can('service-reports.view.own') && Auth::user()->can('service-reports.view.other'))
                                        <svg class="icon-bs icon-baseline text-muted">
                                            <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#dot"></use>
                                        </svg>
                                        {{ Number::toLocal(\App\Models\ServiceReport::newServiceReports()) }} ges.
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
                                <span class="h2 font-weight-bold m-0">{{ Number::toLocal(Auth::user()->employee->new_additions_reports) }}</span>
                            </div>
                            <div class="col-auto pl-0">
                                <svg class="icon-bs icon-32 @if(Auth::user()->employee->new_additions_reports) text-blue-500 @else text-gray-500 @endif">
                                    <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#tools"></use>
                                </svg>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col">
                                <span class="text-muted">
                                    {{ Number::toLocal(Auth::user()->employee->mtd_new_additions_reports) }} MTD
                                    @if(Auth::user()->can('additions-reports.view.own') && Auth::user()->can('additions-reports.view.involved'))
                                        <svg class="icon-bs icon-baseline text-muted">
                                            <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#dot"></use>
                                        </svg>
                                        {{ Number::toLocal(Auth::user()->employee->new_additions_reports_involved_in) }} bet.
                                    @endif
                                    @if(Auth::user()->can('additions-reports.view.own')&& Auth::user()->can('additions-reports.view.involved') && Auth::user()->can('additions-reports.view.other'))
                                        <svg class="icon-bs icon-baseline text-muted">
                                            <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#dot"></use>
                                        </svg>
                                        {{ Number::toLocal(\App\Models\AdditionsReport::newAdditionsReports()) }} ges.
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3 mb-4">
                <div class="card shadow-sm">
                    <a class="stretched-link outline-none" href=""></a>
                    <div class="card-body">
                        <div class="row">
                            <div class="col pr-0">
                                <h5 class="card-title text-uppercase text-muted m-0">offene PB</h5>
                                <span class="h2 font-weight-bold m-0">NA</span>
                            </div>
                            <div class="col-auto pl-0">
                                <svg class="icon-bs icon-32 text-gray-500">
                                    <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#patch-check"></use>
                                </svg>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col">
                                <span class="text-muted">
                                    NA MTD
                                    @if(Auth::user()->can('inspection-reports.view.own') && Auth::user()->can('inspection-reports.view.other'))
                                        <svg class="icon-bs icon-baseline text-muted">
                                            <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#dot"></use>
                                        </svg>
                                        NA ges.
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3 mb-4">
                <div class="card shadow-sm">
                    <a class="stretched-link outline-none" href=""></a>
                    <div class="card-body">
                        <div class="row">
                            <div class="col pr-0">
                                <h5 class="card-title text-uppercase text-muted m-0">offene BT</h5>
                                <span class="h2 font-weight-bold m-0">NA</span>
                            </div>
                            <div class="col-auto pl-0">
                                <svg class="icon-bs icon-32 text-gray-500">
                                    <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#hammer"></use>
                                </svg>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col">
                                <span class="text-muted">
                                    NA MTD
                                    @if(Auth::user()->can('construction-reports.view.own') && Auth::user()->can('construction-reports.view.other'))
                                        <svg class="icon-bs icon-baseline text-muted">
                                            <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#dot"></use>
                                        </svg>
                                        NA ges.
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
                                    <div class="col pr-0">
                                        <h5 class="card-title text-uppercase text-muted m-0">erledigbare SB</h5>
                                        <span class="h2 font-weight-bold m-0">{{ Number::toLocal(\App\Models\ServiceReport::signedServiceReports()) }}</span>
                                    </div>
                                    <div class="col-auto pl-0">
                                        <svg class="icon-bs icon-32 @if(\App\Models\ServiceReport::signedServiceReports()) text-yellow-500 @else text-gray-500 @endif">
                                            <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#gear"></use>
                                        </svg>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col">
                                        <span class="text-muted">{{ Number::toLocal(\App\Models\ServiceReport::mtdSignedServiceReports()) }} MTD</span>
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
                                        <span class="h2 font-weight-bold m-0">{{ Number::toLocal(\App\Models\AdditionsReport::signedAdditionsReports()) }}</span>
                                    </div>
                                    <div class="col-auto pl-0">
                                        <svg class="icon-bs icon-32 @if(\App\Models\AdditionsReport::signedAdditionsReports()) text-yellow-500 @else text-gray-500 @endif">
                                            <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#tools"></use>
                                        </svg>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col">
                                        <span class="text-muted">{{ Number::toLocal(\App\Models\AdditionsReport::mtdSignedAdditionsReports()) }} MTD</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endcan
                @can('inspection-reports.approve')
                    <div class="col-md-6 col-xl-3 mb-4">
                        <div class="card shadow-sm">
                            <a class="stretched-link outline-none" href=""></a>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col pr-0">
                                        <h5 class="card-title text-uppercase text-muted m-0">erledigbare PB</h5>
                                        <span class="h2 font-weight-bold m-0">NA</span>
                                    </div>
                                    <div class="col-auto pl-0">
                                        <svg class="icon-bs icon-32 text-gray-500">
                                            <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#patch-check"></use>
                                        </svg>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col">
                                        <span class="text-muted">NA MTD</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endcan
                @can('construction-reports.approve')
                    <div class="col-md-6 col-xl-3 mb-4">
                        <div class="card shadow-sm">
                            <a class="stretched-link outline-none" href=""></a>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col pr-0">
                                        <h5 class="card-title text-uppercase text-muted m-0">erledigbare BT</h5>
                                        <span class="h2 font-weight-bold m-0">NA</span>
                                    </div>
                                    <div class="col-auto pl-0">
                                        <svg class="icon-bs icon-32 text-gray-500">
                                            <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#hammer"></use>
                                        </svg>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col">
                                        <span class="text-muted">NA MTD</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endcan
            </div>
        @endcan

        <p class="small">
            <span class="font-weight-bold">Legende:</span>
            SB - Serviceberichte,
            RB - Regieberichte,
            PB - Prüfberichte,
            BT - Bautagesberichte,
            MTD - Month to Date (seit Monatsbeginn)@if(Auth::user()->can('additions-reports.view.involved') || Auth::user()->can('construction-reports.view.involved')),
            bet. - beteiligt{{''}}@endif{{''}}@if(Auth::user()->can('service-reports.view.other') || Auth::user()->can('additions-reports.view.other') || Auth::user()->can('inspection-reports.view.other') || Auth::user()->can('construction-reports.view.other')),
            ges. - gesamt
            @endif
        </p>

        <h4 class="mt-4">
            <svg class="icon-bs icon-baseline text-muted mr-2">
                <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#bell"></use>
            </svg>
            Letzte Benachrichtigungen
        </h4>

    </div>
@endsection
