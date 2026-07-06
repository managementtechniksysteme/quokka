@extends('layouts.app')

@section('content')
    <div class="q-container">

        <div class="q-page-head">
            <div class="d-flex align-items-center gap-3">
                <span class="q-head-icon">
                    <svg class="icon icon-20"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#truck"></use></svg>
                </span>
                <div>
                    <h1 class="q-title">Fuhrpark</h1>
                    @unless($vehicles->isEmpty())
                        <div class="q-subtitle">{{ trans_choice('messages.entries', $vehicles->total()) }}</div>
                    @endunless
                </div>
            </div>

            @can('create', \App\Models\Vehicle::class)
                <a class="btn btn-primary text-white d-inline-flex align-items-center gap-2" href="{{ route('vehicles.create') }}">
                    <svg class="icon icon-16"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#plus"></use></svg>
                    Fahrzeug anlegen
                </a>
            @endcan
        </div>

        @unless ($vehicles->isEmpty() && !Request::get('search'))
            <div class="d-flex flex-wrap align-items-center gap-3 mb-3">
                <form class="flex-grow-1" action="{{ route('vehicles.index') }}" method="get">
                    @if(request()->sort)
                        <input type="hidden" name="sort" value="{{ request()->sort }}">
                    @endif
                    <div class="input-group">
                        <input type="text" class="form-control" name="search" value="{{ Request::get('search') ?? '' }}" placeholder="Fahrzeuge suchen" autocomplete="off" />
                        <button class="btn q-btn d-flex align-items-center" type="submit">
                            <svg class="icon icon-16"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#search"></use></svg>
                        </button>
                        @if (Request::get('search'))
                            <a class="btn q-btn d-flex align-items-center" @if(Request::get('sort')) href="{{ Request::url() . '?sort=' . Request::get('sort') }}" @else href="{{ Request::url() }}" @endif>
                                <svg class="icon icon-16"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#x-circle"></use></svg>
                            </a>
                        @endif
                    </div>
                </form>

                <div class="dropdown ms-auto">
                    <button class="btn q-btn dropdown-toggle d-flex align-items-center gap-2" type="button" id="sortOrderDropdown" data-bs-toggle="dropdown">
                        <svg class="icon icon-16"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#arrow-up"></use></svg>
                        Sortierung
                    </button>
                    <div class="dropdown-menu dropdown-menu-end">
                        <form action="{{ route('vehicles.index') }}" method="get">
                            @if(request()->search)
                                <input type="hidden" name="search" value="{{ request()->search }}">
                            @endif

                            <button type="submit" name="sort" value="reg-asc" class="dropdown-item d-inline-flex align-items-center gap-2">
                                <svg class="icon icon-16"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#arrow-up"></use></svg>Kennzeichen
                            </button>
                            <button type="submit" name="sort" value="reg-desc" class="dropdown-item d-inline-flex align-items-center gap-2">
                                <svg class="icon icon-16"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#arrow-down"></use></svg>Kennzeichen
                            </button>
                            <button type="submit" name="sort" value="type-asc" class="dropdown-item d-inline-flex align-items-center gap-2">
                                <svg class="icon icon-16"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#arrow-up"></use></svg>Typ
                            </button>
                            <button type="submit" name="sort" value="type-desc" class="dropdown-item d-inline-flex align-items-center gap-2">
                                <svg class="icon icon-16"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#arrow-down"></use></svg>Typ
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endunless

        @if($vehicles->isEmpty())
            <div class="text-center mt-5">
                <img class="empty-state" src="{{ asset('svg/no-data.svg') }}" alt="no data" />
                @if(Request::get('search'))
                    <p class="lead text-muted">Es wurden keine Fahrzeuge passend zur Suche gefunden.</p>
                @else
                    <p class="lead text-muted">Es sind keine Fahrzeuge im System vorhanden.</p>
                    @can('create', \App\Models\Vehicle::class)
                        <p class="lead">Lege ein neues Fahrzeug an.</p>
                        <a class="btn btn-primary text-white btn-lg d-inline-flex align-items-center gap-2" href="{{ route('vehicles.create') }}">
                            <svg class="icon icon-20"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#plus"></use></svg>
                            Fahrzeug anlegen
                        </a>
                    @endcan
                @endif
            </div>
        @else
            <div class="q-card q-list">
                @foreach ($vehicles as $vehicle)
                    @include('vehicle.overview_card_content', ['vehicle' => $vehicle])
                @endforeach
            </div>

            <div class="mt-3">
                {{ $vehicles->links() }}
            </div>
        @endif
    </div>
@endsection
