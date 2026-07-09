@extends('project.show')

@section('tab')
    @if ($project->constructionReports->isEmpty())
        <div class="q-empty-state">
            <svg class="q-empty-icon"><use href="{{ asset('svg/bootstrap-icons.svg') }}#hammer"></use></svg>
            <p>Diesem Projekt sind noch keine Bautagesberichte zugeordnet.</p>
            @can('create', \App\Models\ConstructionReport::class)
                <a class="btn q-btn d-inline-flex align-items-center gap-2" href="{{ route('construction-reports.create', ['project' => $project->id]) }}">
                    <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#plus"></use></svg>
                    Bautagesbericht anlegen
                </a>
            @endcan
        </div>
    @else
        @php $u = Auth::user(); $fin = $u->settings->show_finished_items ? '' : ' !ist:erledigt'; @endphp

        <div class="d-flex align-items-center gap-2 mb-3">
            <h2 class="q-subhead">Bautagesberichte</h2>
            @can('create', \App\Models\ConstructionReport::class)
                <a class="btn q-btn ms-auto d-inline-flex align-items-center gap-2" href="{{ route('construction-reports.create', ['project' => $project->id]) }}">
                    <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#plus"></use></svg>
                    Bautagesbericht anlegen
                </a>
            @endcan
        </div>

        @include('partials.list_filter', [
            'action' => route('projects.show', $project),
            'placeholder' => 'Bautagesberichte suchen',
            'sorts' => ['number-asc' => 'Nummer', 'number-desc' => 'Nummer', 'status-asc' => 'Status', 'status-desc' => 'Status'],
            'quickFilters' => [
                'Meine Bautagesberichte' => 't:' . $u->username . $fin,
                'Meine nicht unterschriebenen Bautagesberichte' => 't:' . $u->username . ' ist:neu',
            ],
        ])

        @if ($constructionReports->isEmpty())
            <div class="q-card"><div class="q-card__body text-center text-muted py-4">Keine Bautagesberichte passend zur aktuellen Filterung.</div></div>
        @else
            <div class="q-card q-list">
                @foreach ($constructionReports as $constructionReport)
                    @include('construction_report.overview_card_content', ['constructionReport' => $constructionReport, 'secondaryInformation' => 'withoutProject', 'actionRedirect' => 'project'])
                @endforeach
            </div>
            <div class="mt-3">{{ $constructionReports->links() }}</div>
        @endif
    @endif
@endsection
