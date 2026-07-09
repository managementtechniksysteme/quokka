@extends('project.show')

@section('tab')
    @if ($project->flowMeterInspectionReports->isEmpty())
        <div class="q-empty-state">
            <svg class="q-empty-icon"><use href="{{ asset('svg/bootstrap-icons.svg') }}#patch-check"></use></svg>
            <p>Diesem Projekt sind noch keine Prüfberichte für Durchflussmesseinrichtungen zugeordnet.</p>
            @can('create', \App\Models\FlowMeterInspectionReport::class)
                <a class="btn q-btn d-inline-flex align-items-center gap-2" href="{{ route('flow-meter-inspection-reports.create', ['project' => $project->id]) }}">
                    <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#plus"></use></svg>
                    Prüfbericht anlegen
                </a>
            @endcan
        </div>
    @else
        @php $u = Auth::user(); $fin = $u->settings->show_finished_items ? '' : ' !ist:erledigt'; @endphp

        <div class="d-flex align-items-center gap-2 mb-3">
            <h2 class="q-subhead">Prüfberichte Durchflussmesseinrichtungen</h2>
            @can('create', \App\Models\FlowMeterInspectionReport::class)
                <a class="btn q-btn ms-auto d-inline-flex align-items-center gap-2" href="{{ route('flow-meter-inspection-reports.create', ['project' => $project->id]) }}">
                    <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#plus"></use></svg>
                    Prüfbericht anlegen
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

        @if ($flowMeterInspectionReports->isEmpty())
            <div class="q-card"><div class="q-card__body text-center text-muted py-4">Keine Prüfberichte passend zur aktuellen Filterung.</div></div>
        @else
            <div class="q-card q-list">
                @foreach ($flowMeterInspectionReports as $flowMeterInspectionReport)
                    @include('flow_meter_inspection_report.overview_card_content', ['flowMeterInspectionReport' => $flowMeterInspectionReport, 'secondaryInformation' => 'withoutProject', 'actionRedirect' => 'project'])
                @endforeach
            </div>
            <div class="mt-3">{{ $flowMeterInspectionReports->links() }}</div>
        @endif
    @endif
@endsection
