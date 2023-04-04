@extends('layouts.app')

@section('content')
    <div class="bg-gray-100 mt-0">
        <div class="container pt-4">
            <h3>
                <svg class="icon icon-baseline text-muted mr-1">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#menu"></use>
                </svg>
                Finanzgruppen
                @unless($financeGroups->isEmpty())
                    <small class="text-muted">{{ trans_choice('messages.entries', $financeGroups->total()) }}</small>
                @endunless
            </h3>

            <div class="scroll-x d-flex">
                @can('create', \App\Models\FinanceGroup::class)
                    <a class="btn btn-outline-secondary border-0 d-inline-flex align-items-center" href="{{ route('finance-groups.create') }}">
                        <svg class="icon icon-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#plus"></use>
                        </svg>
                        Finanzgruppe anlegen
                    </a>
                @endcan
            </div>
        </div>
    </div>

    <div class="container my-4">
        @unless ($financeGroups->isEmpty() && !Request::get('search'))
            <div class="row">

                <div class="col col-md-6">

                    <form action="{{ route('finance-groups.index') }}" method="get">
                        @if(request()->sort)
                            <input type="hidden" id="sort" name="sort" value="{{ request()->sort }}">
                        @endif

                        <div class="input-group">
                            <input type="text" class="form-control" id="search" name="search" value="{{ Request::get('search') ?? '' }}" placeholder="Finanzgruppen suchen" autocomplete="off" />
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary d-flex align-items-center justify-content-center" type="submit">
                                    <svg class="icon icon-16">
                                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#search"></use>
                                    </svg>
                                </button>
                                @if (Request::get('search'))
                                    <a class="btn btn-outline-secondary d-flex align-items-center justify-content-center" @if(Request::get('sort')) href="{{ Request::url() . '?sort=' . Request::get('sort') }}" @else href="{{ Request::url() }}" @endif>
                                        <svg class="icon icon-16">
                                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#x-circle"></use>
                                        </svg>
                                    </a>
                                @endif
                            </div>
                        </div>

                    </form>

                </div>

                <div class="col-auto ml-auto">
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary btn-block dropdown-toggle d-flex align-items-center justify-content-center" type="button" id="sortOrderDropdown" data-toggle="dropdown">
                            <svg class="icon icon-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#arrow-up"></use>
                            </svg>
                            Sortierung
                        </button>
                        <div class="dropdown-menu dropdown-menu-right w-100">
                            <form action="{{ route('finance-groups.index') }}" method="get">
                                @if(request()->search)
                                    <input type="hidden" id="search" name="search" value="{{ request()->search }}">
                                @endif

                                <button type="submit" name="sort" value="title-asc" class="dropdown-item btn-block  d-inline-flex align-items-center">
                                    <svg class="icon icon-16 mr-2">
                                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#arrow-up"></use>
                                    </svg>
                                    Titel
                                </button>
                                <button type="submit" name="sort" value="title-desc" class="dropdown-item btn-block  d-inline-flex align-items-center">
                                    <svg class="icon icon-16 mr-2">
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
            @forelse ($financeGroups as $financeGroup)
                @component('finance_group.overview_card', [ 'financeGroup' => $financeGroup ])
                @endcomponent

                @if(!$loop->last)
                    <hr class="m-0 mx-1" />
                @endif
            @empty
                <div class="text-center mt-4">
                    <img class="empty-state" src="{{ asset('svg/no-data.svg') }}" alt="no data" />
                    @if(Request::get('search'))
                        <p class="lead text-muted">Es wurden keine Finanzgruppen passend zur Suche gefunden.</p>
                    @else
                        <p class="lead text-muted">Es sind keine Finanzgruppen im System vorhanden.</p>
                        @can('create', \App\Models\FinanceGroup::class)
                            <p class="lead">Lege eine neue Finanzgruppe an.</p>
                            <a class="btn btn-primary btn-lg d-inline-flex align-items-center" href="{{ route('finance-groups.create') }}">
                                <svg class="icon icon-20 mr-2">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#plus"></use>
                                </svg>
                                Finanzgruppe anlegen
                            </a>
                        @endcan
                    @endif
                </div>
            @endforelse
        </div>

        <div class="mt-2">
            {{ $financeGroups->links() }}
        </div>

    </div>
@endsection
