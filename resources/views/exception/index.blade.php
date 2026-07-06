@extends('layouts.app')

@section('content')
    <div class="q-container">

        <div class="q-page-head">
            <div class="d-flex align-items-center gap-3">
                <span class="q-head-icon">
                    <svg class="icon icon-20"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#alert-triangle"></use></svg>
                </span>
                <div>
                    <h1 class="q-title">Fehlerdateien</h1>
                    @unless($exceptions->isEmpty())
                        <div class="q-subtitle">{{ trans_choice('messages.entries', $exceptions->total()) }}</div>
                    @endunless
                </div>
            </div>
        </div>

        @unless ($exceptions->isEmpty() && !Request::get('search'))
            <div class="d-flex flex-wrap align-items-center gap-3 mb-3">
                <form class="flex-grow-1" action="{{ route('exceptions.index') }}" method="get">
                    <div class="input-group">
                        <input type="text" class="form-control" name="search" value="{{ Request::get('search') ?? '' }}" placeholder="Fehlerdatei suchen" autocomplete="off" />
                        <button class="btn q-btn d-flex align-items-center" type="submit">
                            <svg class="icon icon-16"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#search"></use></svg>
                        </button>
                        @if (Request::get('search'))
                            <a class="btn q-btn d-flex align-items-center" href="{{ Request::url() }}">
                                <svg class="icon icon-16"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#x-circle"></use></svg>
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        @endunless

        @if($exceptions->isEmpty())
            <div class="text-center mt-5">
                @if(Request::get('search'))
                    <img class="empty-state" src="{{ asset('svg/no-data.svg') }}" alt="no data" />
                    <p class="lead text-muted">Es wurden keine Fehlerdateien passend zur Suche gefunden.</p>
                @else
                    <img class="empty-state" src="{{ asset('svg/astronaut.svg') }}" alt="no data" />
                    <p class="lead text-muted">Es sind keine Fehlerdateien im System vorhanden.</p>
                @endif
            </div>
        @else
            <div class="q-card q-list">
                @foreach ($exceptions as $exception)
                    @include('exception.overview_card', ['exception' => $exception])
                @endforeach
            </div>

            <div class="mt-3">
                {{ $exceptions->links() }}
            </div>
        @endif
    </div>
@endsection
