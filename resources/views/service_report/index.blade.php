@extends('layouts.app')

@section('content')
    <div class="bg-gray-100 mt-0">
        <div class="container py-4">
            <h3>Serviceberichte</h3>
        </div>
    </div>

    <div class="container mt-4">
        @unless ($serviceReports->isEmpty())
            <a class="btn btn-primary d-inline-flex align-items-center" href="{{ route('service-reports.create') }}">
                <svg class="feather feather-16 mr-2">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#plus"></use>
                </svg>
                Servicebericht anlegen
            </a>

            <div class="row mt-4">

                <div class="col col-md-6">

                    <form action="{{ route('service-reports.index') }}" method="get">
                        @if(request()->sort)
                            <input type="hidden" id="sort" name="sort" value="{{ request()->sort }}">
                        @endif

                        <div class="input-group">
                            <input type="text" class="form-control" id="search" name="search" placeholder="Serviceberichte suchen">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary d-flex align-items-center justify-content-center" type="submit">
                                    <svg class="feather feather-16">
                                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#search"></use>
                                    </svg>
                                </button>
                            </div>
                        </div>

                    </form>

                </div>

                <div class="col-auto ml-auto">
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary btn-block dropdown-toggle d-flex align-items-center justify-content-center" type="button" id="sortOrderDropdown" data-toggle="dropdown">
                            <svg class="feather feather-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#arrow-up"></use>
                            </svg>
                            Sortierung
                        </button>
                        <div class="dropdown-menu dropdown-menu-right w-100">
                            <form action="{{ route('service-reports.index') }}" method="get">
                                @if(request()->search)
                                    <input type="hidden" id="search" name="search" value="{{ request()->search }}">
                                @endif

                                <button type="submit" name="sort" value="number-asc" class="dropdown-item btn-block  d-inline-flex align-items-center">
                                    <svg class="feather feather-16 mr-2">
                                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#arrow-up"></use>
                                    </svg>
                                    Nummer
                                </button>
                                <button type="submit" name="sort" value="number-desc" class="dropdown-item btn-block  d-inline-flex align-items-center">
                                    <svg class="feather feather-16 mr-2">
                                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#arrow-down"></use>
                                    </svg>
                                    Nummer
                                </button>

                                <button type="submit" name="sort" value="status-asc" class="dropdown-item btn-block  d-inline-flex align-items-center">
                                    <svg class="feather feather-16 mr-2">
                                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#arrow-up"></use>
                                    </svg>
                                    Status
                                </button>
                                <button type="submit" name="sort" value="status-desc" class="dropdown-item btn-block  d-inline-flex align-items-center">
                                    <svg class="feather feather-16 mr-2">
                                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#arrow-down"></use>
                                    </svg>
                                    Status
                                </button>

                            </form>
                        </div>
                    </div>
                </div>

            </div>
        @endunless

        <div class="mt-3">
            @forelse ($serviceReports as $serviceReport)
                @component('service_report.overview_card', [ 'serviceReport' => $serviceReport ])
                @endcomponent
            @empty
                <div class="text-center mt-4">
                    <img class="empty-state" src="{{ asset('svg/no-data.svg') }}" alt="no data" />
                    <p class="lead text-muted">Es sind keine Serviceberichte im System vorhanden.</p>
                    <p class="lead">Lege einen neuen Servicebericht an.</p>
                    <a class="btn btn-primary btn-lg d-inline-flex align-items-center" href="{{ route('service-reports.create') }}">
                        <svg class="feather feather-20 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#plus"></use>
                        </svg>
                        Servicebericht anlegen
                    </a>
                </div>
            @endforelse
        </div>

        <div class="mt-2">
            {{ $serviceReports->links() }}
        </div>

    </div>
@endsection
