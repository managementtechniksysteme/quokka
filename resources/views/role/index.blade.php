@extends('layouts.app')

@section('content')
    <div class="q-container">

        <div class="q-page-head">
            {{-- Desktop: icon + title + count, as before. --}}
            <div class="d-none d-md-flex align-items-center gap-3">
                <span class="q-head-icon">
                    <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#key"></use></svg>
                </span>
                <div>
                    <h1 class="q-title">Rollen</h1>
                    @unless($roles->isEmpty())
                        <div class="q-subtitle">{{ trans_choice('messages.entries', $roles->total()) }}</div>
                    @endunless
                </div>
            </div>

            @can('create', \Spatie\Permission\Models\Role::class)
                <a class="btn btn-primary text-white d-none d-md-inline-flex align-items-center gap-2" href="{{ route('roles.create') }}">
                    <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#plus"></use></svg>
                    Rolle anlegen
                </a>
            @endcan

            {{-- Mobile: count inline with the actions, create label
                 shortened to just the entity name. --}}
            <div class="d-flex d-md-none align-items-center gap-2">
                @unless($roles->isEmpty())
                    <div class="q-subtitle mb-0">{{ trans_choice('messages.entries', $roles->total()) }}</div>
                @endunless
                @can('create', \Spatie\Permission\Models\Role::class)
                    <a class="btn btn-primary text-white d-inline-flex align-items-center gap-2 ms-auto" style="flex: none;" href="{{ route('roles.create') }}">
                        <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#plus"></use></svg>
                        Rolle
                    </a>
                @endcan
            </div>
        </div>

        @unless ($roles->isEmpty() && !Request::get('search'))
            <div class="q-banner mb-3">
                <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#exclamation-triangle"></use></svg>
                <span>
                    Bearbeitete oder gelöschte Rollen wirken sich nicht auf Benutzer aus. Benutzer sind direkt mit
                    Berechtigungen, nicht mit Rollen, verknüpft! Bei gewünschten Änderungen müssen einem Benutzer
                    entweder eine Rolle als Vorlage oder individuelle Berechtigungen vergeben werden.
                </span>
            </div>

            {{-- Desktop: search field — unchanged. --}}
            <div class="d-none d-md-flex flex-wrap align-items-center gap-3 mb-3">
                <form class="flex-grow-1" action="{{ route('roles.index') }}" method="get">
                    <div class="input-group">
                        <input type="text" class="form-control" name="search" value="{{ Request::get('search') ?? '' }}" placeholder="Rollen suchen" autocomplete="off" />
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
                 submit button. No sort here at all. --}}
            <div class="d-flex d-md-none align-items-center gap-2 mb-3">
                <form class="flex-grow-1" action="{{ route('roles.index') }}" method="get">
                    <div class="position-relative flex-grow-1">
                        <div class="input-group">
                            <input type="text" class="form-control ps-5" name="search" value="{{ Request::get('search') ?? '' }}" placeholder="Rollen suchen" autocomplete="off" />
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

        @if($roles->isEmpty())
            <div class="q-empty-state">
                <svg class="q-empty-icon"><use href="{{ asset('svg/bootstrap-icons.svg') }}#key"></use></svg>
                @if(Request::get('search'))
                    <p>Keine Rollen für diese Suche gefunden.</p>
                @else
                    <p>Es sind noch keine Rollen vorhanden.</p>
                    @can('create', \Spatie\Permission\Models\Role::class)
                        <a class="btn q-btn d-inline-flex align-items-center gap-2" href="{{ route('roles.create') }}">
                            <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#plus"></use></svg>
                            Rolle anlegen
                        </a>
                    @endcan
                @endif
            </div>
        @else
            <div class="q-card q-list">
                @foreach ($roles as $role)
                    @include('role.overview_card_content', ['role' => $role])
                @endforeach
            </div>

            <div class="mt-3">
                {{ $roles->links() }}
            </div>
        @endif
    </div>
@endsection
