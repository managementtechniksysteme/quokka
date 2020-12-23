@extends('layouts.app')

@section('content')
    <div class="bg-gray-100 mt-0">
        <div class="container py-4">
            <h3>Personen</h3>
        </div>
    </div>

    <div class="container mt-4">
        @unless ($people->isEmpty())
            <a class="btn btn-primary d-inline-flex align-items-center" href="{{ route('people.create') }}">
                <svg class="feather feather-16 mr-2">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#plus"></use>
                </svg>
                Person anlegen
            </a>

            <div class="row mt-4">

                <div class="col col-md-6">

                    <form action="{{ route('people.index') }}" method="get">
                        @if(request()->sort)
                            <input type="hidden" id="sort" name="sort" value="{{ request()->sort }}">
                        @endif

                        <div class="input-group">
                            <input type="text" class="form-control" id="search" name="search" placeholder="Personen suchen">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary d-flex align-items-center justify-content-center" type="submit">
                                    <svg class="feather feather-16">
                                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#search"></use>
                                    </svg>
                                </button>
                            </div>
                        </div>

                    </form>

                </div>

                <div class="col-auto ml-auto">
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary btn-block dropdown-toggle d-flex align-items-center justify-content-center" type="button" id="sortOrderDropdown" data-toggle="dropdown">
                            <svg class="feather feather-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#arrow-up"></use>
                            </svg>
                            Sortierung
                        </button>
                        <div class="dropdown-menu dropdown-menu-right w-100">
                            <form action="{{ route('people.index') }}" method="get">
                                @if(request()->tab)
                                    <input type="hidden" id="tab" name="tab" value="{{ request()->tab }}">
                                @endif
                                @if(request()->search)
                                    <input type="hidden" id="search" name="search" value="{{ request()->search }}">
                                @endif

                                <button type="submit" name="sort" value="first-name-asc" class="dropdown-item btn-block d-inline-flex align-items-center">
                                    <svg class="feather feather-16 mr-2">
                                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#arrow-up"></use>
                                    </svg>
                                    Vorname
                                </button>
                                <button type="submit" name="sort" value="first-name-desc" class="dropdown-item btn-block d-inline-flex align-items-center">
                                    <svg class="feather feather-16 mr-2">
                                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#arrow-down"></use>
                                    </svg>
                                    Vorname
                                </button>
                                <button type="submit" name="sort" value="last-name-asc" class="dropdown-item btn-block d-inline-flex align-items-center">
                                    <svg class="feather feather-16 mr-2">
                                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#arrow-up"></use>
                                    </svg>
                                    Nachname
                                </button>
                                <button type="submit" name="sort" value="last-name-desc" class="dropdown-item btn-block d-inline-flex align-items-center">
                                    <svg class="feather feather-16 mr-2">
                                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#arrow-down"></use>
                                    </svg>
                                    Nachname
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        @endunless

        <div class="mt-3">
            @forelse ($people as $person)
                @component('person.overview_card', [ 'person' => $person ])
                @endcomponent
            @empty
                <div class="text-center mt-4">
                    <img class="empty-state" src="{{ asset('svg/no-data.svg') }}" alt="no data" />
                    <p class="lead text-muted">Es sind keine Personen im System vorhanden.</p>
                    <p class="lead">Lege eine neues Person an.</p>
                    <a class="btn btn-primary btn-lg d-inline-flex align-items-center" href="{{ route('people.create') }}">
                        <svg class="feather feather-20 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#plus"></use>
                        </svg>
                        Person anlegen
                    </a>
                </div>
            @endforelse
        </div>

        <div class="mt-2">
            {{ $people->links() }}
        </div>

    </div>
@endsection
