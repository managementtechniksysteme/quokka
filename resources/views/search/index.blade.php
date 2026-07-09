@extends('layouts.app')

@section('content')
    <div class="q-container">

        <div class="q-page-head">
            <div class="d-flex align-items-center gap-3">
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
