@extends('project.show')

@section('tab')
    @if ($project->memos->isEmpty())
        <div class="text-center mt-5">
            <img class="empty-state" src="{{ asset('svg/no-data.svg') }}" alt="no data" />
            <p class="lead text-muted">Dem Projekt {{ $project->name }} sind keine Aktenvermerke zugeordnet.</p>
            @can('create', \App\Models\Memo::class)
                <p class="lead">Lege einen neuen Aktenvermerk an.</p>
                <a class="btn btn-primary text-white btn-lg d-inline-flex align-items-center gap-2" href="{{ route('memos.create', ['project' => $project->id]) }}">
                    <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#plus"></use></svg>
                    Aktenvermerk anlegen
                </a>
            @endcan
        </div>
    @else
        @php $u = Auth::user(); @endphp

        <div class="d-flex align-items-center gap-2 mb-3">
            <h2 class="q-subhead">Aktenvermerke</h2>
            @can('create', \App\Models\Memo::class)
                <a class="btn q-btn ms-auto d-inline-flex align-items-center gap-2" href="{{ route('memos.create', ['project' => $project->id]) }}">
                    <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#plus"></use></svg>
                    Aktenvermerk anlegen
                </a>
            @endcan
        </div>

        @include('partials.list_filter', [
            'action' => route('projects.show', $project),
            'placeholder' => 'Aktenvermerke suchen',
            'sorts' => ['number-asc' => 'Nummer', 'number-desc' => 'Nummer', 'meeting_held_on-asc' => 'Datum', 'meeting_held_on-desc' => 'Datum', 'title-asc' => 'Titel', 'title-desc' => 'Titel', 'draft-asc' => 'Entwurf', 'draft-desc' => 'Entwurf'],
            'quickFilters' => [
                'Meine Aktenvermerke' => 'von:' . $u->username,
                'Meine Entwürfe' => 'von:' . $u->username . ' ist:entwurf',
                'Beteiligte Aktenvermerke' => 'bm:' . $u->username,
            ],
        ])

        @if ($memos->isEmpty())
            <div class="q-card"><div class="q-card__body text-center text-muted py-4">Keine Aktenvermerke passend zur aktuellen Filterung.</div></div>
        @else
            <div class="q-card q-list">
                @foreach ($memos as $memo)
                    @include('memo.overview_card_content', ['memo' => $memo, 'secondaryInformation' => 'withoutProject', 'actionRedirect' => 'project'])
                @endforeach
            </div>
            <div class="mt-3">{{ $memos->links() }}</div>
        @endif
    @endif
@endsection
