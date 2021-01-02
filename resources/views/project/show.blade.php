@extends('layouts.app')

@section('content')
    <div class="bg-gray-100 mt-0">
        <div class="container pt-4">
            @include('project.breadcrumb')

            <h3>
                Projekt
                <small class="text-muted d-inline-flex align-items-center">
                    {{ $project->name }}
                    @if(false)
                        <svg class="feather feather-16 text-yellow ml-1">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#star"></use>
                        </svg>
                    @endif
                </small>
            </h3>

            <div class="scroll-x d-flex">
                <a class="btn btn-outline-secondary border-0 d-inline-flex align-items-center" href="{{ route('projects.edit', $project) }}">
                    <svg class="feather feather-16 mr-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#edit"></use>
                    </svg>
                    Bearbeiten
                </a>
                <a class="btn btn-outline-secondary border-0 d-inline-flex align-items-center" href="#">
                    <svg class="feather feather-16 mr-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#mail"></use>
                    </svg>
                    Email versenden
                </a>
                <a class="btn btn-outline-secondary border-0 d-inline-flex align-items-center" href="#">
                    <svg class="feather feather-16 mr-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#printer"></use>
                    </svg>
                    PDF erstellen
                </a>
                <a class="btn btn-outline-secondary border-0 d-inline-flex align-items-center" href="#">
                    <svg class="feather feather-16 mr-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#star"></use>
                    </svg>
                    Favorisieren
                </a>
                <form action="{{ route('projects.destroy', $project) }}" method="post" >
                    @csrf
                    @method('DELETE')

                    <button type="submit" class="btn btn-outline-secondary border-0 d-inline-flex align-items-center">
                        <svg class="feather feather-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#trash-2"></use>
                        </svg>
                        Entfernen
                    </button>
                </form>
            </div>

        </div>
    </div>

    <div class="container mt-4">
        <div class="row mt-4">
            <div class="d-none d-lg-block col-lg-3">
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

                    <a class="menu-item @if (request()->tab == 'memos') active @endif rounded text-muted d-flex align-items-center p-2" href="{{ route('projects.show', [$project, 'tab' => 'memos']) }}">
                        <svg class="feather feather-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#voicemail"></use>
                        </svg>
                        Aktenvermerke
                        <span class="ml-auto">{{ $project->memos_count }}</span>
                    </a>

                    <a class="menu-item @if (request()->tab == 'service_reports') active @endif rounded text-muted d-flex align-items-center p-2" href="{{ route('projects.show', [$project, 'tab' => 'service_reports']) }}">
                        <svg class="feather feather-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#settings"></use>
                        </svg>
                        Serviceberichte
                        <span class="ml-auto">{{ $project->service_reports_count }}</span>
                    </a>
                </div>
            </div>

            <div class="menu d-block d-lg-none col mb-4">
                <div class="scroll-x border-bottom pb-1">
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

                    <a class="menu-item @if (request()->tab == 'memos') active @endif rounded text-muted d-inline-flex align-items-center p-2" href="{{ route('projects.show', [$project, 'tab' => 'memos']) }}">
                        <svg class="feather feather-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#voicemail"></use>
                        </svg>
                        Aktenvermerke
                    </a>

                    <a class="menu-item @if (request()->tab == 'service_reports') active @endif rounded text-muted d-inline-flex align-items-center p-2" href="{{ route('projects.show', [$project, 'tab' => 'service_reports']) }}">
                        <svg class="feather feather-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#settings"></use>
                        </svg>
                        Serviceberichte
                    </a>
                </div>
            </div>

            <div class="col-lg-9">
                @yield ('tab')
            </div>
        </div>

    </div>
@endsection
