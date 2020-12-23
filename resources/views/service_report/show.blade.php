@extends('layouts.app')

@section('content')
    <div class="bg-gray-100 mt-0">
        <div class="container py-4">
            @include('service_report.breadcrumb')

            <h3>
                Servicebericht
                <small class="text-muted">{{ $serviceReport->project->name }} #{{ $serviceReport->number }}</small>
            </h3>
        </div>
    </div>

    <div class="container mt-4">
        <a class="btn btn-primary d-inline-flex align-items-center" href="{{ route('service-reports.edit', $serviceReport) }}">
            <svg class="feather feather-16 mr-2">
                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#edit"></use>
            </svg>
            Servicebericht bearbeiten
        </a>
        <a class="btn btn-outline-warning d-none d-lg-inline-flex align-items-center" href="#">
            <svg class="feather feather-16 mr-2">
                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#star"></use>
            </svg>
            Servicebericht zu Favoriten hinzufügen
        </a>
        <a class="btn btn-outline-secondary d-none d-lg-inline-flex align-items-center" href="{{ route('service-reports.download', $serviceReport) }}">
            <svg class="feather feather-16 mr-2">
                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#printer"></use>
            </svg>
            PDF des Serviceberichtes erstellen
        </a>
        <form action="{{ route('service-reports.destroy', $serviceReport) }}" method="post" class="d-none d-lg-inline">
            @csrf
            @method('DELETE')

            <button type="submit" class="btn btn-outline-danger d-inline-flex align-items-center">
                <svg class="feather feather-16 mr-2">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#trash-2"></use>
                </svg>
                Servicebericht entfernen
            </button>
        </form>

        <div class="dropdown d-inline d-lg-none">
            <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="dropdownActionsButton" data-toggle="dropdown">
                Weitere Aktionen
            </button>
            <div class="dropdown-menu">
                <a class="dropdown-item d-inline-flex align-items-center" href="#">
                    <svg class="feather feather-16 mr-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#star"></use>
                    </svg>
                    Servicebericht zu Favoriten hinzufügen
                </a>
                <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('service-reports.download', $serviceReport) }}">
                    <svg class="feather feather-16 mr-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#printer"></use>
                    </svg>
                    PDF des Serviceberichtes erstellen
                </a>
                <form action="{{ route('service-reports.destroy', $serviceReport) }}" method="post">
                    @csrf
                    @method('DELETE')

                    <button type="submit" class="dropdown-item dropdown-item-delete d-inline-flex align-items-center">
                        <svg class="feather feather-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#trash-2"></use>
                        </svg>
                        Servicebericht entfernen
                    </button>
                </form>
            </div>
        </div>

        <div class="row mt-3 mt-md-4">
            <div class="col-sm-2">
                <div class="text-muted d-flex align-items-center">
                    <svg class="feather feather-16 mr-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#clipboard"></use>
                    </svg>
                    Projekt
                </div>
            </div>
            <div class="col">
                {{ $serviceReport->project->name }}
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-sm-2">
                <div class="text-muted d-flex align-items-center">
                    <svg class="feather feather-16 mr-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#user"></use>
                    </svg>
                    Techniker
                </div>
            </div>
            <div class="col">
                {{ $serviceReport->employee->person->name }}
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-sm-2">
                <div class="text-muted d-flex align-items-center">
                    <svg class="@if($serviceReport->isNew()) text-primary @elseif($serviceReport->isSigned()) text-warning @else text-success @endif feather feather-16 mr-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#git-commit"></use>
                    </svg>
                    Status
                </div>
            </div>
            <div class="col">
                {{ __($serviceReport->status) }}
                @switch($serviceReport->status)
                    @case('new')
                        (erstellt am {{ $serviceReport->created_at }})
                        @break
                    @case('signed')
                        am {{ $serviceReport->signature()->created_at }}
                        @break
                    @case('finished')
                        am {{ $serviceReport->updated_at }}
                        @break
                @endswitch
            </div>
        </div>

        <div class="text-muted d-flex align-items-center mt-4 mb-2">
            <svg class="feather feather-16 mr-2">
                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#clock"></use>
            </svg>
            Vollbrachte Leistungen
        </div>

        @include('service_report.show_services')

        <div class="text-muted d-flex align-items-center mt-4">
            <svg class="feather feather-16 mr-2">
                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#message-circle"></use>
            </svg>
            Bemerkungen
        </div>
        @if ($serviceReport->comment)
            @markdown ($serviceReport->comment)
        @else
            keine Bemerkungen angegeben
    @endif


@endsection
