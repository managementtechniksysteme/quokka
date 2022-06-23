@extends('layouts.app')

@section('content')
    <div class="bg-gray-100 mt-0">
        <div class="container py-4">
            <h3>
                <svg class="icon icon-baseline text-muted mr-1">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#alert-triangle"></use>
                </svg>
                Fehlerdateien
                @unless($exceptions->isEmpty())
                    <small class="text-muted">{{ trans_choice('messages.entries', $exceptions->total()) }}</small>
                @endunless
            </h3>
        </div>
    </div>

    <div class="container my-4">
        @unless ($exceptions->isEmpty() && !Request::get('search'))
            <div class="row">

                <div class="col">

                    <form action="{{ route('employees.index') }}" method="get">
                        <div class="input-group">
                            <input type="text" class="form-control" id="search" name="search" value="{{ Request::get('search') ?? '' }}" placeholder="Fehlerdatei suchen" autocomplete="off" />
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary d-flex align-items-center justify-content-center" type="submit">
                                    <svg class="icon icon-16">
                                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#search"></use>
                                    </svg>
                                </button>
                                @if (Request::get('search'))
                                    <a class="btn btn-outline-secondary d-flex align-items-center justify-content-center" href="{{ Request::url() }}">
                                        <svg class="icon icon-16">
                                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#x-circle"></use>
                                        </svg>
                                    </a>
                                @endif
                            </div>
                        </div>

                    </form>

                </div>

            </div>
        @endunless

        <div class="mt-3">
            @forelse ($exceptions as $exception)
                @component('exception.overview_card', [ 'exception' => $exception ])
                @endcomponent

                @if(!$loop->last)
                    <hr class="m-0 mx-1" />
                @endif
            @empty
                <div class="text-center mt-4">
                    @if(Request::get('search'))
                        <img class="empty-state" src="{{ asset('svg/no-data.svg') }}" alt="no data" />
                        <p class="lead text-muted">Es wurden keine Fehlerdateien passend zur Suche gefunden.</p>
                    @else
                        <img class="empty-state" src="{{ asset('svg/astronaut.svg') }}" alt="no data" />
                        <p class="lead text-muted">Es sind keine Fehlerdateien im System vorhanden.</p>
                    @endif
                </div>
            @endforelse
        </div>

        <div class="mt-2">
            {{ $exceptions->links() }}
        </div>

    </div>
@endsection
