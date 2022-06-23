@extends('layouts.app')

@section('content')
    <div class="bg-gray-100 mt-0">
        <div class="container py-4">
            <h3>
                <svg class="icon icon-baseline text-muted mr-1">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#search"></use>
                </svg>
                Suche - {{ $query }}
                @unless($results->isEmpty())
                    <small class="text-muted">{{ trans_choice('messages.results', $results->total()) }}</small>
                @endif
            </h3>
        </div>
    </div>

    <div class="container my-4">
        <div class="mt-3">
            @forelse ($results as $result)
                @component('search.result_card', [ 'result' => $result, ])
                @endcomponent

                @if(!$loop->last)
                    <hr class="m-0 mx-1" />
                @endif
            @empty
                <div class="text-center mt-4">
                    <img class="empty-state" src="{{ asset('svg/no-data.svg') }}" alt="no data" />
                    <p class="lead text-muted">Es wurden keine Ergebnisse passend zur Suche gefunden.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-2">
            {{ $results->links() }}
        </div>

    </div>
@endsection
