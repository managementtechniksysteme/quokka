@extends('layouts.app')

@section('content')
    <div class="q-container">

        <div class="q-page-head">
            {{-- Desktop: icon + title + count, as before. --}}
            <div class="d-none d-md-flex align-items-center gap-3">
                <span class="q-head-icon">
                    <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#search"></use></svg>
                </span>
                <div>
                    <h1 class="q-title">Suche – {{ $query }}</h1>
                    @unless($results->isEmpty())
                        <div class="q-subtitle">{{ trans_choice('messages.results', $results->total()) }}</div>
                    @endunless
                </div>
            </div>

            {{-- Mobile: the app bar already carries "Suche" + its own icon,
                 but the query text itself is real per-page info the app bar
                 doesn't show — kept as a compact subtitle line instead of
                 dropped entirely (2026-07-21, user: "double headers"). --}}
            <div class="d-flex d-md-none align-items-center gap-2">
                <div class="q-subtitle mb-0">
                    „{{ $query }}“@unless($results->isEmpty()) · {{ trans_choice('messages.results', $results->total()) }}@endunless
                </div>
            </div>
        </div>

        @if($results->isEmpty())
            <div class="q-empty-state">
                <svg class="q-empty-icon"><use href="{{ asset('svg/bootstrap-icons.svg') }}#search"></use></svg>
                <p>Keine Ergebnisse für diese Suche gefunden.</p>
            </div>
        @else
            <div class="q-card q-list">
                @foreach($results as $result)
                    @include('search.result_card', ['result' => $result])
                @endforeach
            </div>

            <div class="mt-3">
                {{ $results->links() }}
            </div>
        @endif
    </div>
@endsection
