@extends('layouts.app')

@section('content')
    <div class="q-container">

        <div class="q-page-head">
            <div class="d-flex align-items-center gap-3">
                <span class="q-head-icon">
                    <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#book"></use></svg>
                </span>
                <div>
                    <h1 class="q-title">Notizbuch</h1>
                    @unless($notes->isEmpty())
                        <div class="q-subtitle">{{ trans_choice('messages.entries', $notes->total()) }}</div>
                    @endunless
                </div>
            </div>

            <div class="d-flex gap-2">
                @can('create', \App\Models\Note::class)
                    <a class="btn btn-primary text-white d-inline-flex align-items-center gap-2" href="{{ route('notes.create') }}">
                        <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#plus"></use></svg>
                        Notiz anlegen
                    </a>
                @endcan
                @can('downloadList', \App\Models\Note::class)
                    <a class="btn q-btn d-inline-flex align-items-center gap-2" href="{{ route('notes.download-list') }}" target="_blank">
                        <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#printer"></use></svg>
                        PDF Liste
                    </a>
                @endcan
            </div>
        </div>

        @unless ($notes->isEmpty() && !Request::get('search'))
            <div class="d-flex flex-wrap align-items-center gap-3 mb-3">
                <form class="flex-grow-1" action="{{ route('notes.index') }}" method="get">
                    @if(request()->sort)
                        <input type="hidden" name="sort" value="{{ request()->sort }}">
                    @endif
                    <div class="input-group">
                        <input type="text" class="form-control" name="search" value="{{ Request::get('search') ?? '' }}" placeholder="Notizen suchen" autocomplete="off" />
                        <button class="btn q-btn d-flex align-items-center" type="submit">
                            <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#search"></use></svg>
                        </button>
                        @if (Request::get('search'))
                            <a class="btn q-btn d-flex align-items-center" @if(Request::get('sort')) href="{{ Request::url() . '?sort=' . Request::get('sort') }}" @else href="{{ Request::url() }}" @endif>
                                <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#x-circle"></use></svg>
                            </a>
                        @endif
                    </div>
                </form>

                <div class="dropdown ms-auto">
                    <button class="btn q-btn dropdown-toggle d-flex align-items-center gap-2" type="button" id="sortOrderDropdown" data-bs-toggle="dropdown">
                        <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#sort-down"></use></svg>
                        Sortierung
                    </button>
                    <div class="dropdown-menu dropdown-menu-end">
                        <form action="{{ route('notes.index') }}" method="get">
                            @if(request()->search)
                                <input type="hidden" name="search" value="{{ request()->search }}">
                            @endif

                            <button type="submit" name="sort" value="created_at-asc" class="dropdown-item d-inline-flex align-items-center gap-2">
                                <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#arrow-up"></use></svg>Datum
                            </button>
                            <button type="submit" name="sort" value="created_at-desc" class="dropdown-item d-inline-flex align-items-center gap-2">
                                <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#arrow-down"></use></svg>Datum
                            </button>
                            <button type="submit" name="sort" value="title-asc" class="dropdown-item d-inline-flex align-items-center gap-2">
                                <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#arrow-up"></use></svg>Titel
                            </button>
                            <button type="submit" name="sort" value="title-desc" class="dropdown-item d-inline-flex align-items-center gap-2">
                                <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#arrow-down"></use></svg>Titel
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endunless

        @if($notes->isEmpty())
            <div class="q-empty-state">
                <svg class="q-empty-icon"><use href="{{ asset('svg/bootstrap-icons.svg') }}#book"></use></svg>
                @if(Request::get('search'))
                    <p>Keine Notizen für diese Suche gefunden.</p>
                @else
                    <p>Es sind noch keine Notizen vorhanden.</p>
                    @can('create', \App\Models\Note::class)
                        <a class="btn q-btn d-inline-flex align-items-center gap-2" href="{{ route('notes.create') }}">
                            <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#plus"></use></svg>
                            Notiz anlegen
                        </a>
                    @endcan
                @endif
            </div>
        @else
            <div class="q-card q-list">
                @foreach ($notes as $note)
                    @include('note.overview_card_content', ['note' => $note, 'actionRedirect' => 'index'])
                @endforeach
            </div>

            <div class="mt-3">
                {{ $notes->links() }}
            </div>
        @endif
    </div>
@endsection
