@extends('layouts.app')

@section('content')
    <div class="bg-gray-100 mt-0">
        <div class="container py-4">
            <h3>
                <svg class="icon icon-baseline text-muted mr-1">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#activity"></use>
                </svg>
                Letzte Änderungen
                @unless($results->isEmpty())
                    <small class="text-muted">{{ $results->total() }} Elemente</small>
                @endif
            </h3>
        </div>
    </div>

    <div class="container my-4">
        <div class="mt-3">
            @unless($changesToday->isEmpty())
                <h4 class="mt-4">
                    <svg class="icon-bs icon-baseline text-muted mr-1">
                        <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#calendar-date"></use>
                    </svg>
                    Heute
                </h4>
                @foreach($changesToday as $change)
                    @component('latest_changes.result_card', ['result' => $change])
                    @endcomponent
                    @if(!$loop->last)
                        <hr class="m-0 mx-1" />
                    @endif
                @endforeach
            @endunless
            @unless($changesYesterday->isEmpty())
                <h4 class="mt-4">
                    <svg class="icon-bs icon-baseline text-muted mr-1">
                        <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#calendar-minus"></use>
                    </svg>
                    Gestern
                </h4>
                @foreach($changesYesterday as $change)
                    @component('latest_changes.result_card', ['result' => $change])
                    @endcomponent
                    @if(!$loop->last)
                        <hr class="m-0 mx-1" />
                    @endif
                @endforeach
            @endunless
            @unless($changesThisWeek->isEmpty())
                <h4 class="mt-4">
                    <svg class="icon-bs icon-baseline text-muted mr-1">
                        <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#calendar-range"></use>
                    </svg>
                    Diese Woche
                </h4>
                @foreach($changesThisWeek as $change)
                    @component('latest_changes.result_card', ['result' => $change])
                    @endcomponent
                    @if(!$loop->last)
                        <hr class="m-0 mx-1" />
                    @endif
                @endforeach
            @endunless
            @unless($changesOlderThanThisWeek->isEmpty())
                @unless($changesThisWeek->isEmpty() && $changesYesterday->isEmpty() && $changesToday->isEmpty())
                    <h4 class="mt-4">
                        <svg class="icon-bs icon-baseline text-muted mr-1">
                            <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#calendar"></use>
                        </svg>
                        Vorher
                    </h4>
                @endunless
                @foreach($changesOlderThanThisWeek as $change)
                    @component('latest_changes.result_card', ['result' => $change])
                    @endcomponent
                    @if(!$loop->last)
                        <hr class="m-0 mx-1" />
                    @endif
                @endforeach
            @endunless
            @empty($results)
                <div class="text-center mt-4">
                    <img class="empty-state" src="{{ asset('svg/no-data.svg') }}" alt="no data" />
                    <p class="lead text-muted">Es wurden keine Ergebnisse passend zur Suche gefunden.</p>
                </div>
            @endempty
        </div>

        <div class="mt-2">
            {{ $results->links() }}
        </div>

    </div>
@endsection
