@extends('layouts.app')

@section('content')
    <div class="bg-gray-100 mt-0">
        <div class="container pt-4">
            <h3>
                <svg class="icon icon-baseline text-muted mr-1">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#clipboard"></use>
                </svg>
                Projekt Finanzübersicht
            </h3>

            <div class="scroll-x d-flex">
                @can('finances-createpdf')
                    <a class="btn btn-outline-secondary border-0 d-inline-flex align-items-center" href="{{ route('project-finances.download') }}">
                        <svg class="icon icon-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#printer"></use>
                        </svg>
                        PDF erstellen
                    </a>
                @endcan
            </div>
        </div>
    </div>

    <div class="container my-4">
        <div class="row">

            <div class="col-lg-6">
                <h4>
                    <svg class="icon-bs icon-baseline text-muted mr-1">
                        <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#circle"></use>
                    </svg>
                    Aktuell offene Projekte
                </h4>

                <div class="row">
                    <div class="col-md-6 mb-4">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title text-uppercase text-muted mb-2">Auftragsvolumen</h5>
                                <span class="h2 font-weight-bold text-green m-0">{{ Number::toLocal($currentlyOpenProjectsData['total_volume'], 2) }}{{ $currencyUnit }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 mb-4">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title text-uppercase text-muted mb-2">verrechnet</h5>
                                <span class="h2 font-weight-bold text-red m-0">{{ Number::toLocal($currentlyOpenProjectsData['billed_volume'], 2) }}{{ $currencyUnit }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row justify-content-center">
                    <div class="col-md-6 mb-4">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title text-uppercase text-muted mb-2">offen</h5>
                                <span class="h2 font-weight-bold @if($currentlyOpenProjectsData['total_volume'] + $currentlyOpenProjectsData['billed_volume'] >= 0) text-green @else text-red @endif  m-0">{{ Number::toLocal($currentlyOpenProjectsData['total_volume'] + $currentlyOpenProjectsData['billed_volume'], 2) }}{{ $currencyUnit }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <finance-volume-chart :total_volume="{{ $currentlyOpenProjectsData['total_volume'] }}" :billed_volume="{{ $currentlyOpenProjectsData['billed_volume'] }}" v-cloak></finance-volume-chart>
                </div>
            </div>

            <div class="col-lg-6">
                <h4>
                    <svg class="icon-bs icon-baseline text-muted mr-1">
                        <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#arrow-bar-left"></use>
                    </svg>
                    Projekte in der Vorphase
                </h4>

                <div class="row">
                    <div class="col-md-6 mb-4">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title text-uppercase text-muted mb-2">Auftragsvolumen</h5>
                                <span class="h2 font-weight-bold text-green m-0">{{ Number::toLocal($preExecutionProjectsData['total_volume'], 2) }}{{ $currencyUnit }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 mb-4">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title text-uppercase text-muted mb-2">verrechnet</h5>
                                <span class="h2 font-weight-bold text-red m-0">{{ Number::toLocal($preExecutionProjectsData['billed_volume'], 2) }}{{ $currencyUnit }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row justify-content-center">
                    <div class="col-md-6 mb-4">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title text-uppercase text-muted mb-2">offen</h5>
                                <span class="h2 font-weight-bold @if($preExecutionProjectsData['total_volume'] + $preExecutionProjectsData['billed_volume'] >= 0) text-green @else text-red @endif  m-0">{{ Number::toLocal($preExecutionProjectsData['total_volume'] + $preExecutionProjectsData['billed_volume'], 2) }}{{ $currencyUnit }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <finance-volume-chart :total_volume="{{ $preExecutionProjectsData['total_volume'] }}" :billed_volume="{{ $preExecutionProjectsData['billed_volume'] }}" v-cloak></finance-volume-chart>
                </div>
            </div>
        </div>

        <h4 class="mt-4">
            <svg class="icon-bs icon-baseline text-muted mr-1">
                <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#list"></use>
            </svg>
            Übersicht einzelner Projekte
        </h4>

        <form class="needs-validation mt-4" action="{{ route('project-finances.index') }}" method="get" novalidate>
            <div class="row">
                <div class="col d-flex">
                    <div class="form-group flex-grow-1 mr-2">
                        <project-dropdown :projects="{{ $projects }}" :current_project="{{ $currentProject ?? 'null' }}" inputname="project" v-cloak></project-dropdown>
                        <div class="invalid-feedback @error('project_id') d-block @enderror">
                            @error('project_id')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary d-inline-flex align-items-center">
                            <svg class="icon icon-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#eye"></use>
                            </svg>
                            Anzeigen
                        </button>
                    </div>
                </div>
            </div>
        </form>

        @if($projectData)
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title text-uppercase text-muted mb-2">Auftragsvolumen</h5>
                            <span class="h2 font-weight-bold text-green m-0">{{ Number::toLocal($projectData['total_volume'], 2) }}{{ $currencyUnit }}</span>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title text-uppercase text-muted mb-2">verrechnet</h5>
                            <span class="h2 font-weight-bold text-red m-0">{{ Number::toLocal($projectData['billed_volume'], 2) }}{{ $currencyUnit }}</span>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title text-uppercase text-muted mb-2">offen</h5>
                            <span class="h2 font-weight-bold @if($projectData['total_volume'] + $projectData['billed_volume'] >= 0) text-green @else text-red @endif m-0">{{ Number::toLocal($projectData['total_volume'] + $projectData['billed_volume'], 2) }}{{ $currencyUnit }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <finance-volume-chart :total_volume="{{ $projectData['total_volume'] }}" :billed_volume="{{ $projectData['billed_volume'] }}" v-cloak></finance-volume-chart>
            </div>
        @endif

    </div>
@endsection
