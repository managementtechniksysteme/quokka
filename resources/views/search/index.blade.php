@extends('layouts.app')

@section('content')
    <div class="q-container">

        <div class="q-page-head">
            <div class="d-flex align-items-center gap-3">
                <span class="q-head-icon">
                    <svg class="icon icon-20"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#search"></use></svg>
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
            <div class="text-center mt-5">
                <img class="empty-state" src="{{ asset('svg/no-data.svg') }}" alt="no data" />
                <p class="lead text-muted">Es wurden keine Ergebnisse passend zur Suche gefunden.</p>
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
