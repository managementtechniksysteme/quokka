@extends('layouts.app')

@section('content')
    <div class="container">
        <h3>Aktenvermerke</h3>

        @unless ($memos->isEmpty())
            <a class="btn btn-primary d-inline-flex align-items-center" href="{{ route('memos.create') }}">
                <svg class="feather feather-16 mr-2">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#plus"></use>
                </svg>
                Aktenvermerk anlegen
            </a>

            <div class="row mt-4">

                <div class="col col-md-6">

                    <form action="{{ route('memos.index') }}" method="get">
                        @if(request()->sort)
                            <input type="hidden" id="sort" name="sort" value="{{ request()->sort }}">
                        @endif

                        <div class="input-group">
                            <input type="text" class="form-control" id="search" name="search" placeholder="Aktenvermerke suchen">
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
                            <form action="{{ route('memos.index') }}" method="get">
                                @if(request()->search)
                                    <input type="hidden" id="search" name="search" value="{{ request()->search }}">
                                @endif

                                <button type="submit" name="sort" value="number-asc" class="dropdown-item btn-block  d-inline-flex align-items-center">
                                    <svg class="feather feather-16 mr-2">
                                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#arrow-up"></use>
                                    </svg>
                                    Nummer
                                </button>
                                <button type="submit" name="sort" value="number-desc" class="dropdown-item btn-block  d-inline-flex align-items-center">
                                    <svg class="feather feather-16 mr-2">
                                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#arrow-down"></use>
                                    </svg>
                                    Nummer
                                </button>

                                <button type="submit" name="sort" value="meeting_held_on-asc" class="dropdown-item btn-block  d-inline-flex align-items-center">
                                    <svg class="feather feather-16 mr-2">
                                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#arrow-up"></use>
                                    </svg>
                                    Datum
                                </button>
                                <button type="submit" name="sort" value="meeting_held_on-desc" class="dropdown-item btn-block  d-inline-flex align-items-center">
                                    <svg class="feather feather-16 mr-2">
                                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#arrow-down"></use>
                                    </svg>
                                    Datum
                                </button>

                                <button type="submit" name="sort" value="title-asc" class="dropdown-item btn-block  d-inline-flex align-items-center">
                                    <svg class="feather feather-16 mr-2">
                                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#arrow-up"></use>
                                    </svg>
                                    Titel
                                </button>
                                <button type="submit" name="sort" value="title-desc" class="dropdown-item btn-block  d-inline-flex align-items-center">
                                    <svg class="feather feather-16 mr-2">
                                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#arrow-down"></use>
                                    </svg>
                                    Titel
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        @endunless

        <div class="mt-3">
            @forelse ($memos as $memo)
                @component('memo.overview_card', [ 'memo' => $memo ])
                @endcomponent
            @empty
                <div class="text-center mt-4">
                    <img class="empty-state" src="{{ asset('svg/no-data.svg') }}" alt="no data" />
                    <p class="lead text-muted">Es sind keine Aktenvermerke im System vorhanden.</p>
                    <p class="lead">Lege ein neuen Aktenvermerk an.</p>
                    <a class="btn btn-primary btn-lg d-inline-flex align-items-center" href="{{ route('memos.create') }}">
                        <svg class="feather feather-20 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#plus"></use>
                        </svg>
                        Aktenvermerk anlegen
                    </a>
                </div>
            @endforelse
        </div>

        <div class="mt-2">
            {{ $memos->links() }}
        </div>

    </div>
@endsection
