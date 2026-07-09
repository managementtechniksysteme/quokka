@extends('project.show')

@section('tab')
    @if ($project->serviceReports->isEmpty())
        <div class="text-center mt-5">
            <img class="empty-state" src="{{ asset('svg/no-data.svg') }}" alt="no data" />
            <p class="lead text-muted">Dem Projekt {{ $project->name }} sind keine Serviceberichte zugeordnet.</p>
            @can('create', \App\Models\ServiceReport::class)
                <p class="lead">Lege einen neuen Servicebericht an.</p>
                <a class="btn btn-primary text-white btn-lg d-inline-flex align-items-center gap-2" href="{{ route('service-reports.create', ['project' => $project->id]) }}">
                    <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#plus"></use></svg>
                    Servicebericht anlegen
                </a>
            @endcan
        </div>
    @else
        @php $u = Auth::user(); $fin = $u->settings->show_finished_items ? '' : ' !ist:erledigt'; @endphp

        <div class="d-flex align-items-center gap-2 mb-3">
            <h2 class="q-subhead">Serviceberichte</h2>
            <div class="ms-auto d-flex align-items-center gap-2">
                @can('create', \App\Models\ServiceReport::class)
                    <a class="btn q-btn d-inline-flex align-items-center gap-2" href="{{ route('service-reports.create', ['project' => $project->id]) }}">
                        <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#plus"></use></svg>
                        Servicebericht anlegen
                    </a>
                @endcan
                @can('downloadList', \App\Models\ServiceReport::class)
                    <a class="btn q-btn d-inline-flex align-items-center gap-2" href="{{ route('service-reports.download-list', ['project_id' => $project->id]) }}" target="_blank">
                        <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#printer"></use></svg>
                        PDF Liste
                    </a>
                @endcan
            </div>
        </div>

        @include('partials.list_filter', [
            'action' => route('projects.show', $project),
            'placeholder' => 'Serviceberichte suchen',
            'sorts' => ['number-asc' => 'Nummer', 'number-desc' => 'Nummer', 'status-asc' => 'Status', 'status-desc' => 'Status'],
            'quickFilters' => [
                'Meine Serviceberichte' => 't:' . $u->username . $fin,
                'Meine nicht unterschriebenen Serviceberichte' => 't:' . $u->username . ' ist:neu',
            ],
        ])

        @if ($serviceReports->isEmpty())
            <div class="q-card"><div class="q-card__body text-center text-muted py-4">Keine Serviceberichte passend zur aktuellen Filterung.</div></div>
        @else
            <div class="q-card q-list">
                @foreach ($serviceReports as $serviceReport)
                    @include('service_report.overview_card_content', ['serviceReport' => $serviceReport, 'secondaryInformation' => 'withoutProject', 'actionRedirect' => 'project'])
                @endforeach
            </div>
            <div class="mt-3">{{ $serviceReports->links() }}</div>
        @endif
    @endif
@endsection
