@extends('layouts.app')

@section('content')
    <div class="container">
        <h3>
            Projekt
            <small class="text-muted">{{ $project->name }}</small>
        </h3>

        <a class="btn btn-primary d-inline-flex align-items-center" href="{{ route('projects.edit', $project) }}">
            <svg class="feather feather-16 mr-2">
                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#edit"></use>
            </svg>
            Projekt bearbeiten
        </a>
        <a class="btn btn-outline-warning d-none d-lg-inline-flex align-items-center" href="#">
            <svg class="feather feather-16 mr-2">
                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#star"></use>
            </svg>
            Projekt zu Favoriten hinzufügen
        </a>
        <a class="btn btn-outline-secondary d-none d-lg-inline-flex align-items-center" href="#">
            <svg class="feather feather-16 mr-2">
                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#printer"></use>
            </svg>
            Stammblatt PDF erstellen
        </a>
        <form action="{{ route('projects.destroy', $project) }}" method="post" class="d-none d-lg-inline">
            @csrf
            @method('DELETE')

            <button type="submit" class="btn btn-outline-danger d-inline-flex align-items-center">
                <svg class="feather feather-16 mr-2">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#trash-2"></use>
                </svg>
                Projekt entfernen
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
                    Projekt zu Favoriten hinzufügen
                </a>
                <a class="dropdown-item d-inline-flex align-items-center" href="#">
                    <svg class="feather feather-16 mr-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#printer"></use>
                    </svg>
                    Stammblatt PDF erstellen
                </a>
                <form action="{{ route('projects.destroy', $project) }}" method="post">
                    @csrf
                    @method('DELETE')

                    <button type="submit" class="dropdown-item dropdown-item-delete d-inline-flex align-items-center">
                        <svg class="feather feather-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#trash-2"></use>
                        </svg>
                        Projekt entfernen
                    </button>
                </form>
            </div>
        </div>

        <div class="row mt-4">
            <div class="d-none d-md-block col-md-3">
                <div class="menu border-right pr-3">
                    <a class="menu-item @if (request()->tab == 'overview') active @endif rounded text-muted d-flex align-items-center p-2" href="{{ route('projects.show', [$project, 'tab' => 'overview']) }}">
                        <svg class="feather feather-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#clipboard"></use>
                        </svg>
                        Stammdaten
                    </a>

                    <a class="menu-item @if (request()->tab == 'tasks') active @endif rounded text-muted d-flex align-items-center p-2" href="{{ route('projects.show', [$project, 'tab' => 'tasks']) }}">
                        <svg class="feather feather-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#check-square"></use>
                        </svg>
                        Aufgaben
                        <span class="ml-auto">{{ $project->tasks_count }}</span>
                    </a>
                </div>
            </div>

            <div class="menu d-block d-md-none col mb-4">
                <div class="border-bottom pb-2">
                    <a class="menu-item @if (request()->tab == 'overview') active @endif rounded text-muted d-inline-flex align-items-center p-2" href="{{ route('projects.show', [$project, 'tab' => 'overview']) }}">
                        <svg class="feather feather-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#clipboard"></use>
                        </svg>
                        Stammdaten
                    </a>

                    <a class="menu-item @if (request()->tab == 'tasks') active @endif rounded text-muted d-inline-flex align-items-center p-2" href="{{ route('projects.show', [$project, 'tab' => 'tasks']) }}">
                        <svg class="feather feather-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#check-square"></use>
                        </svg>
                        Aufgaben
                    </a>
                </div>
            </div>

            <div class="col-md-9">
                @yield ('tab')
            </div>
        </div>

    </div>
@endsection
