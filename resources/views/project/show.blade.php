@extends('layouts.app')

{{-- Mobile app bar: back chevron + project name + kebab, same pattern as
     company/show.blade.php (2026-07-21). --}}
@section('mobile-detail-bar')
    <a href="{{ route('projects.index') }}" class="q-appbar__btn" aria-label="Zurück zu Projekte">
        <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#chevron-left"></use></svg>
    </a>
    <span class="q-appbar__title">{{ $project->name }}</span>
    <button class="q-appbar__btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#projectShowActionsSheet" aria-controls="projectShowActionsSheet" aria-label="Aktionen">
        <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#three-dots-vertical"></use></svg>
    </button>
@endsection

{{-- Outside .q-appbar deliberately — see the z-index note on company's own
     actions sheet, same fix applies here. --}}
@section('mobile-detail-sheets')
    <div class="offcanvas offcanvas-bottom q-sheet" tabindex="-1" id="projectShowActionsSheet" aria-label="Aktionen">
        <div class="q-sheet__handle" aria-hidden="true"><span class="q-sheet__handle-bar"></span></div>
        <div class="offcanvas-body">
            <div class="q-sheet__label">Aktionen</div>
            @can('update', $project)
                <a class="q-row" href="{{ route('projects.edit', $project) }}">
                    <span class="q-avatar q-avatar--muted"><svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#pencil"></use></svg></span>
                    <span class="q-row__title">Bearbeiten</span>
                </a>
            @endcan
            @can('email', $project)
                <a class="q-row" href="#">
                    <span class="q-avatar q-avatar--muted"><svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#envelope"></use></svg></span>
                    <span class="q-row__title">Email versenden</span>
                </a>
            @endcan
            @can('createPdf', $project)
                <a class="q-row" href="{{ route('projects.download', $project) }}">
                    <span class="q-avatar q-avatar--muted"><svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#printer"></use></svg></span>
                    <span class="q-row__title">PDF erstellen</span>
                </a>
            @endcan
            <a class="q-row" href="#">
                <span class="q-avatar q-avatar--muted"><svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#star"></use></svg></span>
                <span class="q-row__title">Favorisieren</span>
            </a>
            @can('delete', $project)
                <form action="{{ route('projects.destroy', ['project' => $project, 'redirect' => 'index']) }}" method="post">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="q-row q-row--danger">
                        <span class="q-avatar q-avatar--danger"><svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#trash"></use></svg></span>
                        <span class="q-row__title">Entfernen</span>
                    </button>
                </form>
            @endcan
        </div>
    </div>
@endsection

@section('content')
    <div class="q-container">

        {{-- Desktop only: mobile's app bar back button covers this. --}}
        <nav class="q-breadcrumb d-none d-md-flex">
            <a href="{{ route('projects.index') }}">Projekte</a>
            <span class="q-breadcrumb__sep">/</span>
            <span>{{ $project->name }}</span>
        </nav>

        <div class="q-page-head d-none d-md-flex">
            <div class="d-flex align-items-center gap-3">
                <span class="q-avatar">
                    <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#clipboard"></use></svg>
                </span>
                <div>
                    <div class="q-eyebrow">Projekt</div>
                    <h1 class="q-title">{{ $project->name }}</h1>
                    <div class="q-meta">
                        <span class="q-status q-status--{{ $project->state }}">{{ $project->state_label }}</span>
                        <a class="q-chip" href="{{ route('companies.show', $project->company) }}">
                            <svg class="icon-bs icon-12"><use href="{{ asset('svg/bootstrap-icons.svg') }}#briefcase"></use></svg>
                            {{ $project->company->full_name }}
                        </a>
                    </div>
                </div>
            </div>

            <div class="d-flex align-items-center gap-2">
                @can('update', $project)
                    <a class="btn btn-primary text-white d-inline-flex align-items-center gap-2" href="{{ route('projects.edit', $project) }}">
                        <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#pencil"></use></svg>
                        Bearbeiten
                    </a>
                @endcan

                <div class="dropdown">
                    <button class="q-kebab" type="button" id="projectShowDropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#three-dots-vertical"></use></svg>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="projectShowDropdown">
                        @can('email', $project)
                            <a class="dropdown-item d-inline-flex align-items-center" href="#">
                                <svg class="icon-bs icon-16 me-2"><use href="{{ asset('svg/bootstrap-icons.svg') }}#envelope"></use></svg>
                                Email versenden
                            </a>
                        @endcan
                        @can('createPdf', $project)
                            <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('projects.download', $project) }}">
                                <svg class="icon-bs icon-16 me-2"><use href="{{ asset('svg/bootstrap-icons.svg') }}#printer"></use></svg>
                                PDF erstellen
                            </a>
                        @endcan
                        <a class="dropdown-item d-inline-flex align-items-center" href="#">
                            <svg class="icon-bs icon-16 me-2"><use href="{{ asset('svg/bootstrap-icons.svg') }}#star"></use></svg>
                            Favorisieren
                        </a>
                        @can('delete', $project)
                            <form action="{{ route('projects.destroy', ['project' => $project, 'redirect' => 'index']) }}" method="post">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="dropdown-item dropdown-item-danger d-inline-flex align-items-center">
                                    <svg class="icon-bs icon-16 me-2"><use href="{{ asset('svg/bootstrap-icons.svg') }}#trash"></use></svg>
                                    Entfernen
                                </button>
                            </form>
                        @endcan
                    </div>
                </div>
            </div>
        </div>

        {{-- Mobile: less top margin than desktop, same reasoning as
             company/show.blade.php (no breadcrumb/page-head above it here). --}}
        <div class="q-detail q-detail--aside-start mt-2 pt-1 mt-md-4 pt-md-2">
            <aside>
                <nav class="q-subnav">
                    <a class="q-subnav__item @if (request()->tab == 'overview' || !request()->tab) active @endif" href="{{ route('projects.show', [$project, 'tab' => 'overview']) }}">
                        <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#clipboard"></use></svg>
                        <span class="flex-grow-1">Stammdaten</span>
                    </a>

                    @can('viewAny', \App\Models\InterimInvoice::class)
                        <a class="q-subnav__item @if (request()->tab == 'interim_invoices') active @endif" href="{{ route('projects.show', [$project, 'tab' => 'interim_invoices']) }}">
                            <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#currency-euro"></use></svg>
                            <span class="flex-grow-1">Teilrechnungen</span>
                            @if($project->interim_invoices_count > 0)<span class="q-subnav__count">{{ $project->interim_invoices_count }}</span>@endif
                        </a>
                    @endcan
                    @can('viewAny', \App\Models\Task::class)
                        <a class="q-subnav__item @if (request()->tab == 'tasks') active @endif" href="{{ route('projects.show', [$project, 'tab' => 'tasks']) }}">
                            <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#check2-square"></use></svg>
                            <span class="flex-grow-1">Aufgaben</span>
                            @if($project->tasks_count > 0)<span class="q-subnav__count">{{ $project->tasks_count }}</span>@endif
                        </a>
                    @endcan
                    @can('viewAny', \App\Models\Memo::class)
                        <a class="q-subnav__item @if (request()->tab == 'memos') active @endif" href="{{ route('projects.show', [$project, 'tab' => 'memos']) }}">
                            <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#voicemail"></use></svg>
                            <span class="flex-grow-1">Aktenvermerke</span>
                            @if($project->memos_count > 0)<span class="q-subnav__count">{{ $project->memos_count }}</span>@endif
                        </a>
                    @endcan
                    @can('viewAny', \App\Models\ServiceReport::class)
                        <a class="q-subnav__item @if (request()->tab == 'service_reports') active @endif" href="{{ route('projects.show', [$project, 'tab' => 'service_reports']) }}">
                            <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#gear"></use></svg>
                            <span class="flex-grow-1">Serviceberichte</span>
                            @if($project->service_reports_count > 0)<span class="q-subnav__count">{{ $project->service_reports_count }}</span>@endif
                        </a>
                    @endcan
                    @can('viewAny', \App\Models\AdditionsReport::class)
                        <a class="q-subnav__item @if (request()->tab == 'additions_reports') active @endif" href="{{ route('projects.show', [$project, 'tab' => 'additions_reports']) }}">
                            <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#tools"></use></svg>
                            <span class="flex-grow-1">Regieberichte</span>
                            @if($project->additions_reports_count > 0)<span class="q-subnav__count">{{ $project->additions_reports_count }}</span>@endif
                        </a>
                    @endcan
                    @can('viewAny', \App\Models\InspectionReport::class)
                        <a class="q-subnav__item @if (request()->tab == 'inspection_reports') active @endif" href="{{ route('projects.show', [$project, 'tab' => 'inspection_reports']) }}">
                            <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#patch-check"></use></svg>
                            <span class="flex-grow-1">Prüfberichte</span>
                            @if($project->inspection_reports_count > 0)<span class="q-subnav__count">{{ $project->inspection_reports_count }}</span>@endif
                        </a>
                    @endcan
                    @can('viewAny', \App\Models\FlowMeterInspectionReport::class)
                        <a class="q-subnav__item @if (request()->tab == 'flow_meter_inspection_reports') active @endif" href="{{ route('projects.show', [$project, 'tab' => 'flow_meter_inspection_reports']) }}">
                            <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#patch-check"></use></svg>
                            <span class="flex-grow-1">Prüfberichte DM</span>
                            @if($project->flow_meter_inspection_reports_count > 0)<span class="q-subnav__count">{{ $project->flow_meter_inspection_reports_count }}</span>@endif
                        </a>
                    @endcan
                    @can('viewAny', \App\Models\ConstructionReport::class)
                        <a class="q-subnav__item @if (request()->tab == 'construction_reports') active @endif" href="{{ route('projects.show', [$project, 'tab' => 'construction_reports']) }}">
                            <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#hammer"></use></svg>
                            <span class="flex-grow-1">Bautagesberichte</span>
                            @if($project->construction_reports_count > 0)<span class="q-subnav__count">{{ $project->construction_reports_count }}</span>@endif
                        </a>
                    @endcan
                    @can('viewAny', \App\Models\DeliveryNote::class)
                        <a class="q-subnav__item @if (request()->tab == 'delivery_notes') active @endif" href="{{ route('projects.show', [$project, 'tab' => 'delivery_notes']) }}">
                            <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#box-seam"></use></svg>
                            <span class="flex-grow-1">Lieferscheine</span>
                            @if($project->delivery_notes_count > 0)<span class="q-subnav__count">{{ $project->delivery_notes_count }}</span>@endif
                        </a>
                    @endcan
                </nav>
            </aside>

            <div>
                @yield('tab')
            </div>
        </div>

    </div>
@endsection
