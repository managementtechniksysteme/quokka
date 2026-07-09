@extends('project.show')

@section('tab')
    @if ($project->additionsReports->isEmpty())
        <div class="q-empty-state">
            <svg class="q-empty-icon"><use href="{{ asset('svg/bootstrap-icons.svg') }}#tools"></use></svg>
            <p>Diesem Projekt sind noch keine Regieberichte zugeordnet.</p>
            @can('create', \App\Models\AdditionsReport::class)
                <a class="btn q-btn d-inline-flex align-items-center gap-2" href="{{ route('additions-reports.create', ['project' => $project->id]) }}">
                    <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#plus"></use></svg>
                    Regiebericht anlegen
                </a>
            @endcan
        </div>
    @else
        @php $u = Auth::user(); $fin = $u->settings->show_finished_items ? '' : ' !ist:erledigt'; @endphp

        <div class="d-flex align-items-center gap-2 mb-3">
            <h2 class="q-subhead">Regieberichte</h2>
            @can('create', \App\Models\AdditionsReport::class)
                <a class="btn q-btn ms-auto d-inline-flex align-items-center gap-2" href="{{ route('additions-reports.create', ['project' => $project->id]) }}">
                    <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#plus"></use></svg>
                    Regiebericht anlegen
                </a>
            @endcan
        </div>

        @include('partials.list_filter', [
            'action' => route('projects.show', $project),
            'placeholder' => 'Regieberichte suchen',
            'sorts' => ['number-asc' => 'Nummer', 'number-desc' => 'Nummer', 'status-asc' => 'Status', 'status-desc' => 'Status'],
            'quickFilters' => [
                'Meine Regieberichte' => 't:' . $u->username . $fin,
                'Meine nicht unterschriebenen Regieberichte' => 't:' . $u->username . ' ist:neu',
            ],
        ])

        @if ($additionsReports->isEmpty())
            <div class="q-card"><div class="q-card__body text-center text-muted py-4">Keine Regieberichte passend zur aktuellen Filterung.</div></div>
        @else
            <div class="q-card q-list">
                @foreach ($additionsReports as $additionsReport)
                    @include('additions_report.overview_card_content', ['additionsReport' => $additionsReport, 'secondaryInformation' => 'withoutProject', 'actionRedirect' => 'project'])
                @endforeach
            </div>
            <div class="mt-3">{{ $additionsReports->links() }}</div>
        @endif
    @endif
@endsection
