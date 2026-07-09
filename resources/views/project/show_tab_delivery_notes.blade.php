@extends('project.show')

@section('tab')
    @if ($project->deliveryNotes->isEmpty())
        <div class="text-center mt-5">
            <img class="empty-state" src="{{ asset('svg/no-data.svg') }}" alt="no data" />
            <p class="lead text-muted">Dem Projekt {{ $project->name }} sind keine Lieferscheine zugeordnet.</p>
            @can('create', \App\Models\DeliveryNote::class)
                <p class="lead">Lege einen neuen Lieferschein an.</p>
                <a class="btn btn-primary text-white btn-lg d-inline-flex align-items-center gap-2" href="{{ route('delivery-notes.create', ['project' => $project->id]) }}">
                    <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#plus"></use></svg>
                    Lieferschein anlegen
                </a>
            @endcan
        </div>
    @else
        <div class="d-flex align-items-center gap-2 mb-3">
            <h2 class="q-subhead">Lieferscheine</h2>
            @can('create', \App\Models\DeliveryNote::class)
                <a class="btn q-btn ms-auto d-inline-flex align-items-center gap-2" href="{{ route('delivery-notes.create', ['project' => $project->id]) }}">
                    <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#plus"></use></svg>
                    Lieferschein anlegen
                </a>
            @endcan
        </div>

        @include('partials.list_filter', [
            'action' => route('projects.show', $project),
            'placeholder' => 'Lieferscheine suchen',
            'sorts' => ['written_on-asc' => 'Datum', 'written_on-desc' => 'Datum', 'title-asc' => 'Titel', 'title-desc' => 'Titel', 'status-asc' => 'Status', 'status-desc' => 'Status'],
        ])

        @if ($deliveryNotes->isEmpty())
            <div class="q-card"><div class="q-card__body text-center text-muted py-4">Keine Lieferscheine passend zur aktuellen Filterung.</div></div>
        @else
            <div class="q-card q-list">
                @foreach ($deliveryNotes as $deliveryNote)
                    @include('delivery_note.overview_card_content', ['deliveryNote' => $deliveryNote, 'secondaryInformation' => 'withoutProject', 'actionRedirect' => 'project'])
                @endforeach
            </div>
            <div class="mt-3">{{ $deliveryNotes->links() }}</div>
        @endif
    @endif
@endsection
