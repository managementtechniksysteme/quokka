@extends('project.show')

@section('tab')
    @if ($project->inspectionReports->isEmpty())
        <div class="q-empty-state">
            <svg class="q-empty-icon"><use href="{{ asset('svg/bootstrap-icons.svg') }}#patch-check"></use></svg>
            <p>Diesem Projekt sind noch keine Prüfberichte zugeordnet.</p>
            @can('create', \App\Models\InspectionReport::class)
                <a class="btn q-btn d-inline-flex align-items-center gap-2" href="{{ route('inspection-reports.create', ['project' => $project->id]) }}">
                    <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#plus"></use></svg>
                    Prüfbericht anlegen
                </a>
            @endcan
        </div>
    @else
        @php $u = Auth::user(); $fin = $u->settings->show_finished_items ? '' : ' !ist:erledigt'; @endphp

        <div class="d-flex align-items-center gap-2 mb-3">
            <h2 class="q-subhead">Prüfberichte</h2>
            @can('create', \App\Models\InspectionReport::class)
                <a class="btn q-btn ms-auto d-inline-flex align-items-center gap-2" href="{{ route('inspection-reports.create', ['project' => $project->id]) }}">
                    <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#plus"></use></svg>
                    <span class="d-none d-md-inline">Prüfbericht anlegen</span>
                    <span class="d-inline d-md-none">Prüfbericht</span>
                </a>
            @endcan
        </div>

        @include('partials.list_filter', [
            'action' => route('projects.show', $project),
            'placeholder' => 'Prüfberichte suchen',
            'sorts' => ['number-asc' => 'Nummer', 'number-desc' => 'Nummer', 'status-asc' => 'Status', 'status-desc' => 'Status'],
            'quickFilters' => [
                'Meine Prüfberichte' => 't:' . $u->username . $fin,
                'Meine nicht unterschriebenen Prüfberichte' => 't:' . $u->username . ' ist:neu',
            ],
        ])

        @if ($inspectionReports->isEmpty())
            <div class="q-card"><div class="q-card__body text-center text-muted py-4">Keine Prüfberichte passend zur aktuellen Filterung.</div></div>
        @else
            <div class="q-card q-list">
                @foreach ($inspectionReports as $inspectionReport)
                    @include('inspection_report.overview_card_content', ['inspectionReport' => $inspectionReport, 'secondaryInformation' => 'withoutProject', 'actionRedirect' => 'project'])
                @endforeach
            </div>
            <div class="mt-3">{{ $inspectionReports->links() }}</div>
        @endif
    @endif
@endsection
