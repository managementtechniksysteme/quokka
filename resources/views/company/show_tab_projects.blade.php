@extends('company.show')

@section('tab')
    @if ($company->projects->isEmpty())
        <div class="q-empty-state">
            <svg class="q-empty-icon"><use href="{{ asset('svg/bootstrap-icons.svg') }}#clipboard"></use></svg>
            <p>Dieser Firma sind noch keine Projekte zugeordnet.</p>
            @can('create', \App\Models\Project::class)
                <a class="btn q-btn d-inline-flex align-items-center gap-2" href="{{ route('projects.create', ['company' => $company->id]) }}">
                    <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#plus"></use></svg>
                    Projekt anlegen
                </a>
            @endcan
        </div>
    @else
        <div class="d-flex align-items-center gap-2 mb-3">
            <h2 class="q-subhead">Projekte</h2>

            <div class="ms-auto d-flex align-items-center gap-2">
                @can('create', \App\Models\Project::class)
                    <a class="btn q-btn d-inline-flex align-items-center gap-2" href="{{ route('projects.create', ['company' => $company->id]) }}">
                        <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#plus"></use></svg>
                        <span class="d-none d-md-inline">Projekt anlegen</span>
                        <span class="d-inline d-md-none">Projekt</span>
                    </a>
                @endcan

                @if(Auth::user()->can('downloadList', \App\Models\Project::class) || Auth::user()->can('downloadList', \App\Models\Task::class) || Auth::user()->can('downloadList', \App\Models\ServiceReport::class))
                    {{-- Desktop: unchanged dropdown. --}}
                    <div class="dropdown d-none d-md-block">
                        <button class="q-kebab" type="button" id="projectsListDropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Listen erstellen">
                            <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#three-dots-vertical"></use></svg>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end">
                            @can('downloadList', \App\Models\Project::class)
                                <a class="dropdown-item d-inline-flex align-items-center gap-2" href="{{ route('projects.download-list', ['company_id' => $company->id]) }}" target="_blank"><svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#clipboard"></use></svg>Projektliste</a>
                            @endcan
                            @can('downloadList', \App\Models\Task::class)
                                <a class="dropdown-item d-inline-flex align-items-center gap-2" href="{{ route('tasks.download-list', ['company_id' => $company->id]) }}" target="_blank"><svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#check2-square"></use></svg>Aufgabenliste</a>
                            @endcan
                            @can('downloadList', \App\Models\ServiceReport::class)
                                <a class="dropdown-item d-inline-flex align-items-center gap-2" href="{{ route('service-reports.download-list', ['company_id' => $company->id]) }}" target="_blank"><svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#gear"></use></svg>Serviceberichtliste</a>
                            @endcan
                        </div>
                    </div>

                    {{-- Mobile: a sheet, same as every other page-level kebab
                         menu (2026-07-21, user: "The kebab next to that
                         button should also use a sheet"). --}}
                    <button class="q-kebab d-md-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#projectsListSheet" aria-controls="projectsListSheet" aria-label="Listen erstellen">
                        <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#three-dots-vertical"></use></svg>
                    </button>
                    <div class="offcanvas offcanvas-bottom q-sheet" tabindex="-1" id="projectsListSheet" aria-label="Listen erstellen">
                        <div class="q-sheet__handle" aria-hidden="true"><span class="q-sheet__handle-bar"></span></div>
                        <div class="offcanvas-body">
                            <div class="q-sheet__label">Listen erstellen</div>
                            @can('downloadList', \App\Models\Project::class)
                                <a class="q-row" href="{{ route('projects.download-list', ['company_id' => $company->id]) }}" target="_blank">
                                    <span class="q-avatar q-avatar--muted"><svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#clipboard"></use></svg></span>
                                    <span class="q-row__title">Projektliste</span>
                                </a>
                            @endcan
                            @can('downloadList', \App\Models\Task::class)
                                <a class="q-row" href="{{ route('tasks.download-list', ['company_id' => $company->id]) }}" target="_blank">
                                    <span class="q-avatar q-avatar--muted"><svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#check2-square"></use></svg></span>
                                    <span class="q-row__title">Aufgabenliste</span>
                                </a>
                            @endcan
                            @can('downloadList', \App\Models\ServiceReport::class)
                                <a class="q-row" href="{{ route('service-reports.download-list', ['company_id' => $company->id]) }}" target="_blank">
                                    <span class="q-avatar q-avatar--muted"><svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#gear"></use></svg></span>
                                    <span class="q-row__title">Serviceberichtliste</span>
                                </a>
                            @endcan
                        </div>
                    </div>
                @endif
            </div>
        </div>

        @include('partials.list_filter', [
            'action' => route('companies.show', $company),
            'placeholder' => 'Projekte suchen',
            'sorts' => ['name-asc' => 'Name', 'name-desc' => 'Name', 'wage-costs-asc' => 'Lohnkosten', 'wage-costs-desc' => 'Lohnkosten', 'material-costs-asc' => 'Materialkosten', 'material-desc' => 'Materialkosten'],
        ])

        @if ($projects->isEmpty())
            <div class="q-card"><div class="q-card__body text-center text-muted py-4">Keine Projekte passend zur Suche gefunden.</div></div>
        @else
            <div class="q-card q-list">
                @foreach ($projects as $project)
                    @include('project.overview_card_content', ['project' => $project, 'secondaryInformation' => 'dates', 'actionRedirect' => 'company'])
                @endforeach
            </div>
            <div class="mt-3">{{ $projects->links() }}</div>

            @include('project.cost_legend')
        @endif
    @endif
@endsection
