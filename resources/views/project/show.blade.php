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
                        <svg class="icon icon-16 text-yellow ml-1">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#star"></use>
                        </svg>
                    @endif
                </small>
            </h3>

            <div class="scroll-x d-flex">
                @can('update', $project)
                    <a class="btn btn-outline-secondary border-0 d-inline-flex align-items-center" href="{{ route('projects.edit', $project) }}">
                        <svg class="icon icon-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#edit"></use>
                        </svg>
                        Bearbeiten
                    </a>
                @endcan
                @can('email', $project)
                    <a class="btn btn-outline-secondary border-0 d-inline-flex align-items-center" href="#">
                        <svg class="icon icon-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#mail"></use>
                        </svg>
                        Email versenden
                    </a>
                @endcan
                @can('createPdf', $project)
                    <a class="btn btn-outline-secondary border-0 d-inline-flex align-items-center" href="#">
                        <svg class="icon icon-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#printer"></use>
                        </svg>
                        PDF erstellen
                    </a>
                @endcan
                <a class="btn btn-outline-secondary border-0 d-inline-flex align-items-center" href="#">
                    <svg class="icon icon-16 mr-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#star"></use>
                    </svg>
                    Favorisieren
                </a>
                @can('delete', $project)
                    <form action="{{ route('projects.destroy', ['project' => $project, 'redirect' => $actionRedirect ?? 'index']) }}" method="post" >
                        @csrf
                        @method('DELETE')

                        <button type="submit" class="btn btn-outline-secondary border-0 d-inline-flex align-items-center">
                            <svg class="icon icon-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#trash-2"></use>
                            </svg>
                            Entfernen
                        </button>
                    </form>
                @endcan
            </div>

        </div>
    </div>

    <div class="container my-4">
        <div class="row mt-4">
            <div class="d-none d-lg-block col-lg-3">
                <div class="menu border-right pr-3">
                    <a class="menu-item @if (request()->tab == 'overview') active @endif rounded text-muted d-flex align-items-center p-2" href="{{ route('projects.show', [$project, 'tab' => 'overview']) }}">
                        <svg class="icon icon-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#clipboard"></use>
                        </svg>
                        Stammdaten
                    </a>

                    @can('viewAny', \App\Models\Task::class)
                        <a class="menu-item @if (request()->tab == 'tasks') active @endif rounded text-muted d-flex align-items-center p-2" href="{{ route('projects.show', [$project, 'tab' => 'tasks']) }}">
                            <svg class="icon icon-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#check-square"></use>
                            </svg>
                            Aufgaben
                            <span class="ml-auto">{{ $project->tasks_count > 0 ? $project->tasks_count : '' }}</span>
                        </a>
                    @endcan

                    @can('viewAny', \App\Models\Memo::class)
                        <a class="menu-item @if (request()->tab == 'memos') active @endif rounded text-muted d-flex align-items-center p-2" href="{{ route('projects.show', [$project, 'tab' => 'memos']) }}">
                            <svg class="icon icon-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#voicemail"></use>
                            </svg>
                            Aktenvermerke
                            <span class="ml-auto">{{ $project->memos_count > 0 ? $project->memos_count : '' }}</span>
                        </a>
                    @endcan

                    @can('viewAny', \App\Models\ServiceReport::class)
                        <a class="menu-item @if (request()->tab == 'service_reports') active @endif rounded text-muted d-flex align-items-center p-2" href="{{ route('projects.show', [$project, 'tab' => 'service_reports']) }}">
                            <svg class="icon icon-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#settings"></use>
                            </svg>
                            Serviceberichte
                            <span class="ml-auto">{{ $project->service_reports_count > 0 ? $project->service_reports_count : '' }}</span>
                        </a>
                    @endcan
                    @can('viewAny', \App\Models\AdditionsReport::class)
                        <a class="menu-item @if (request()->tab == 'additions_reports') active @endif rounded text-muted d-flex align-items-center p-2" href="{{ route('projects.show', [$project, 'tab' => 'additions_reports']) }}">
                            <svg class="icon-bs icon-16 mr-2">
                                <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#tools"></use>
                            </svg>
                            Regieberichte
                            <span class="ml-auto">{{ $project->additions_reports_count > 0 ? $project->additions_reports_count : '' }}</span>
                        </a>
                    @endcan
                    @can('viewAny', \App\Models\InspectionReport::class)
                        <a class="menu-item @if (request()->tab == 'inspection_reports') active @endif rounded text-muted d-flex align-items-center p-2" href="{{ route('projects.show', [$project, 'tab' => 'inspection_reports']) }}">
                            <svg class="icon-bs icon-16 mr-2">
                                <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#patch-check"></use>
                            </svg>
                            Prüfberichte
                            <span class="ml-auto">{{ $project->inspection_reports_count > 0 ? $project->inspection_reports_count : '' }}</span>
                        </a>
                    @endcan
                    @can('viewAny', \App\Models\ConstructionReport::class)
                        <a class="menu-item @if (request()->tab == 'construction_reports') active @endif rounded text-muted d-flex align-items-center p-2" href="{{ route('projects.show', [$project, 'tab' => 'construction_reports']) }}">
                            <svg class="icon-bs icon-16 mr-2">
                                <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#hammer"></use>
                            </svg>
                            Bautagesberichte
                            <span class="ml-auto">{{ $project->construction_reports_count > 0 ? $project->construction_reports_count : '' }}</span>
                        </a>
                    @endcan
                </div>
            </div>

            <div class="menu d-block d-lg-none col mb-4">
                <div class="scroll-x border-bottom pb-1">
                    <a class="menu-item @if (request()->tab == 'overview') active @endif rounded text-muted d-inline-flex align-items-center p-2" href="{{ route('projects.show', [$project, 'tab' => 'overview']) }}">
                        <svg class="icon icon-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#clipboard"></use>
                        </svg>
                        Stammdaten
                    </a>

                    @can('viewAny', \App\Models\Task::class)
                        <a class="menu-item @if (request()->tab == 'tasks') active @endif rounded text-muted d-inline-flex align-items-center p-2" href="{{ route('projects.show', [$project, 'tab' => 'tasks']) }}">
                            <svg class="icon icon-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#check-square"></use>
                            </svg>
                            Aufgaben
                        </a>
                    @endcan

                    @can('viewAny', \App\Models\Memo::class)
                        <a class="menu-item @if (request()->tab == 'memos') active @endif rounded text-muted d-inline-flex align-items-center p-2" href="{{ route('projects.show', [$project, 'tab' => 'memos']) }}">
                            <svg class="icon icon-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#voicemail"></use>
                            </svg>
                            Aktenvermerke
                        </a>
                    @endcan

                    @can('viewAny', \App\Models\ServiceReport::class)
                        <a class="menu-item @if (request()->tab == 'service_reports') active @endif rounded text-muted d-inline-flex align-items-center p-2" href="{{ route('projects.show', [$project, 'tab' => 'service_reports']) }}">
                            <svg class="icon icon-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#settings"></use>
                            </svg>
                            Serviceberichte
                        </a>
                    @endcan

                    @can('viewAny', \App\Models\AdditionsReport::class)
                        <a class="menu-item @if (request()->tab == 'additions_reports') active @endif rounded text-muted d-inline-flex align-items-center p-2" href="{{ route('projects.show', [$project, 'tab' => 'additions_reports']) }}">
                            <svg class="icon-bs icon-16 mr-2">
                                <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#tools"></use>
                            </svg>
                            Regieberichte
                        </a>
                    @endcan

                    @can('viewAny', \App\Models\InspectionReport::class)
                        <a class="menu-item @if (request()->tab == 'inspection_reports') active @endif rounded text-muted d-inline-flex align-items-center p-2" href="{{ route('projects.show', [$project, 'tab' => 'inspection_reports']) }}">
                            <svg class="icon-bs icon-16 mr-2">
                                <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#patch-check"></use>
                            </svg>
                            Prüfberichte
                        </a>
                    @endcan

                    @can('viewAny', \App\Models\ConstructionReport::class)
                        <a class="menu-item @if (request()->tab == 'construction_reports') active @endif rounded text-muted d-inline-flex align-items-center p-2" href="{{ route('projects.show', [$project, 'tab' => 'construction_reports']) }}">
                            <svg class="icon-bs icon-16 mr-2">
                                <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#hammer"></use>
                            </svg>
                            Bautagesberichte
                        </a>
                    @endcan
                </div>
            </div>

            <div class="col-lg-9">
                @yield ('tab')
            </div>
        </div>

    </div>
@endsection
