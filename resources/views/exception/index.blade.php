@extends('layouts.app')

@section('content')
    <div class="q-container">

        <div class="q-page-head">
            {{-- Desktop: icon + title + count, as before. --}}
            <div class="d-none d-md-flex align-items-center gap-3">
                <span class="q-head-icon">
                    <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#exclamation-triangle"></use></svg>
                </span>
                <div>
                    <h1 class="q-title">Fehlerdateien</h1>
                    @unless($exceptions->isEmpty())
                        <div class="q-subtitle">{{ trans_choice('messages.entries', $exceptions->total()) }}</div>
                    @endunless
                </div>
            </div>

            {{-- Mobile: the app bar already carries "Fehlerdateien" + its own
                 icon — collapses to just the count, no repeated icon/title
                 (2026-07-21, user: "double headers"). --}}
            <div class="d-flex d-md-none align-items-center gap-2">
                @unless($exceptions->isEmpty())
                    <div class="q-subtitle mb-0">{{ trans_choice('messages.entries', $exceptions->total()) }}</div>
                @endunless
            </div>
        </div>

        @unless ($exceptions->isEmpty() && !Request::get('search'))
            {{-- Desktop: search field, unchanged. --}}
            <div class="d-none d-md-flex flex-wrap align-items-center gap-3 mb-3">
                <form class="flex-grow-1" action="{{ route('exceptions.index') }}" method="get">
                    <div class="input-group">
                        <input type="text" class="form-control" name="search" value="{{ Request::get('search') ?? '' }}" placeholder="Fehlerdatei suchen" autocomplete="off" />
                        <button class="btn q-btn d-flex align-items-center" type="submit">
                            <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#search"></use></svg>
                        </button>
                        @if (Request::get('search'))
                            <a class="btn q-btn d-flex align-items-center" href="{{ Request::url() }}">
                                <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#x-circle"></use></svg>
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            {{-- Mobile: leading search icon inline in the field, no separate
                 submit button. No sort here, so no sheet needed. --}}
            <div class="d-flex d-md-none align-items-center gap-2 mb-3">
                <form class="flex-grow-1" action="{{ route('exceptions.index') }}" method="get">
                    <div class="position-relative flex-grow-1">
                        <div class="input-group">
                            <input type="text" class="form-control ps-5" name="search" value="{{ Request::get('search') ?? '' }}" placeholder="Fehlerdatei suchen" autocomplete="off" />
                            @if (Request::get('search'))
                                <a class="btn q-btn q-btn-icon d-flex align-items-center justify-content-center" href="{{ Request::url() }}">
                                    <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#x-circle"></use></svg>
                                </a>
                            @endif
                        </div>
                        <svg class="icon-bs icon-16 text-muted position-absolute top-50 start-0 translate-middle-y ms-3 pe-none q-search-icon">
                            <use href="{{ asset('svg/bootstrap-icons.svg') }}#search"></use>
                        </svg>
                    </div>
                </form>
            </div>
        @endunless

        @if($exceptions->isEmpty())
            <div class="q-empty-state">
                @if(Request::get('search'))
                    <svg class="q-empty-icon"><use href="{{ asset('svg/bootstrap-icons.svg') }}#exclamation-triangle"></use></svg>
                    <p>Keine Fehlerdateien für diese Suche gefunden.</p>
                @else
                    <svg class="q-empty-icon"><use href="{{ asset('svg/bootstrap-icons.svg') }}#check-circle"></use></svg>
                    <p>Keine Fehlerdateien vorhanden.</p>
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
