@extends('company.show')

@section('tab')
    @if ($company->people->isEmpty())
        <div class="q-empty-state">
            <svg class="q-empty-icon"><use href="{{ asset('svg/bootstrap-icons.svg') }}#people"></use></svg>
            <p>Dieser Firma sind noch keine Personen zugeordnet.</p>
            @can('create', \App\Models\Person::class)
                <a class="btn q-btn d-inline-flex align-items-center gap-2" href="{{ route('people.create', ['company' => $company->id]) }}">
                    <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#plus"></use></svg>
                    Person anlegen
                </a>
            @endcan
        </div>
    @else
        <div class="d-flex align-items-center gap-2 mb-3">
            <h2 class="q-subhead">Personen</h2>
            @can('create', \App\Models\Person::class)
                <a class="btn q-btn ms-auto d-inline-flex align-items-center gap-2" href="{{ route('people.create', ['company' => $company->id]) }}">
                    <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#plus"></use></svg>
                    <span class="d-none d-md-inline">Person anlegen</span>
                    <span class="d-inline d-md-none">Person</span>
                </a>
            @endcan
        </div>

        @include('partials.list_filter', [
            'action' => route('companies.show', $company),
            'placeholder' => 'Personen suchen',
            'sorts' => ['first-name-asc' => 'Vorname', 'first-name-desc' => 'Vorname', 'last-name-asc' => 'Nachname', 'last-name-desc' => 'Nachname'],
        ])

        @if ($people->isEmpty())
            <div class="q-card"><div class="q-card__body text-center text-muted py-4">Keine Personen passend zur Suche gefunden.</div></div>
        @else
            <div class="q-card q-list">
                @foreach ($people as $person)
                    @include('person.overview_card_content', ['person' => $person, 'secondaryInformation' => 'address', 'actionRedirect' => 'company'])
                @endforeach
            </div>
            <div class="mt-3">{{ $people->links() }}</div>
        @endif
    @endif
@endsection
