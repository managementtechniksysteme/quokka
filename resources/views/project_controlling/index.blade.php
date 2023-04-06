@extends('layouts.app')

@php
    use \App\Models\Project;
@endphp

@if (old('project'))
    @php $currentProject = Project::find(old('project')); @endphp
@endif


@section('content')
    <div class="bg-gray-100 mt-0">
        <div class="container py-4">
            <h3>
                <svg class="icon icon-baseline text-muted mr-1">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#bar-chart-2"></use>
                </svg>
                Projektcontrolling
            </h3>
        </div>
    </div>

    <div class="container my-4">

        <div class="alert alert-info mt-1" role="alert">
            <div class="d-inline-flex align-items-center">
                <svg class="icon icon-24 mr-2">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#info"></use>
                </svg>
                <p class="m-0">
                    Die Eingaben für Start- und Enddatum haben nur Auswirkungen auf das
                    <strong>Projektcontrolling</strong>>.
                    Wenn keine Filter angegeben sind, Start bzw. Ende des Projektes verwendet.
                </p>
            </div>
        </div>

        <form class="needs-validation mt-4" action="{{ route('project-controlling.index') }}" method="get" novalidate>
            <div class="form-row">
                <div class="col-lg-6 form-group">
                    <label for="project">Projekt</label>
                    <project-dropdown :projects="{{ $projects }}" :current_project="{{ $currentProject ?? 'null' }}" inputname="project" v-cloak></project-dropdown>
                    <div class="invalid-feedback @error('project_id') d-block @enderror">
                        @error('project_id')
                        {{ $message }}
                        @enderror
                    </div>
                </div>

                <div class="col-sm-6 col-lg-2 form-group">
                    <label for="start">Startdatum</label>
                    <input type="date" class="form-control @error('start') is-invalid @enderror" id="start" name="start" placeholder="" value="{{ old('start', $start?->format('Y-m-d')) ?? '' }}" />
                    <div class="invalid-feedback">
                        @error('start')
                        {{ $message }}
                        @enderror
                    </div>
                </div>

                <div class="col-sm-6 col-lg-2 form-group">
                    <label for="end">Enddatum</label>
                    <input type="date" class="form-control @error('end') is-invalid @enderror" id="end" name="end" placeholder="" value="{{ old('end', $end?->format('Y-m-d')) ?? '' }}" />
                    <div class="invalid-feedback">
                        @error('end')
                        {{ $message }}
                        @enderror
                    </div>
                </div>

                <div class="col-lg-2 form-group d-none d-lg-block">
                    <label>&nbsp;</label>
                    <button type="submit" class="btn btn-primary d-flex align-items-center justify-content-center w-100">
                        <svg class="icon icon-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#eye"></use>
                        </svg>
                        Anzeigen
                    </button>
                </div>

                <div class="col form-group d-block d-lg-none">
                    <button type="submit" class="btn btn-primary d-flex align-items-center">
                        <svg class="icon icon-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#eye"></use>
                        </svg>
                        Anzeigen
                    </button>
                </div>
            </div>
        </form>

        @if($currentProject)
            <div class="row mt-4">

                <div class="col-lg-6">
                    <h4>
                        <svg class="icon icon-baseline text-muted mr-1">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#bar-chart-2"></use>
                        </svg>
                        Projektcontrolling
                    </h4>

                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <h5 class="card-title text-uppercase text-muted mb-2">Einnahmen</h5>
                                    <span class="h2 font-weight-bold text-green m-0">{{ Number::toLocal($accountingFinanceData['revenue'], 2) }}{{ $currencyUnit }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-4">
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <h5 class="card-title text-uppercase text-muted mb-2">Ausgaben</h5>
                                    <span class="h2 font-weight-bold text-red m-0">{{ Number::toLocal($accountingFinanceData['expense'], 2) }}{{ $currencyUnit }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row justify-content-center">
                        <div class="col-md-6 mb-4">
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <h5 class="card-title text-uppercase text-muted mb-2">Differnz</h5>
                                    <span class="h2 font-weight-bold @if($accountingFinanceData['revenue'] + $accountingFinanceData['expense'] >= 0) text-green @else text-red @endif  m-0">{{ Number::toLocal($accountingFinanceData['revenue'] + $accountingFinanceData['expense'], 2) }}{{ $currencyUnit }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <finance-revenue-expense-chart :revenue="{{ $accountingFinanceData['revenue'] }}" :expense="{{ $accountingFinanceData['expense'] }}" v-cloak></finance-revenue-expense-chart>
                    </div>
                </div>

                <div class="col-lg-6">
                    <h4>
                        <svg class="icon icon-baseline text-muted mr-1">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#dollar-sign"></use>
                        </svg>
                        Finanzcontrolling
                    </h4>

                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <h5 class="card-title text-uppercase text-muted mb-2">Auftragsvolumen</h5>
                                    <span class="h2 font-weight-bold text-green m-0">{{ Number::toLocal($manuelFinanceData['total_volume'], 2) }}{{ $currencyUnit }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-4">
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <h5 class="card-title text-uppercase text-muted mb-2">verrechnet</h5>
                                    <span class="h2 font-weight-bold text-red m-0">{{ Number::toLocal($manuelFinanceData['billed_volume'], 2) }}{{ $currencyUnit }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row justify-content-center">
                        <div class="col-md-6 mb-4">
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <h5 class="card-title text-uppercase text-muted mb-2">offen</h5>
                                    <span class="h2 font-weight-bold @if($manuelFinanceData['total_volume'] + $manuelFinanceData['billed_volume'] >= 0) text-green @else text-red @endif  m-0">{{ Number::toLocal($manuelFinanceData['total_volume'] + $manuelFinanceData['billed_volume'], 2) }}{{ $currencyUnit }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <finance-volume-chart :total_volume="{{ $manuelFinanceData['total_volume'] }}" :billed_volume="{{ $manuelFinanceData['billed_volume'] }}" v-cloak></finance-volume-chart>
                    </div>
                </div>
            </div>
        @endif

    </div>
@endsection
