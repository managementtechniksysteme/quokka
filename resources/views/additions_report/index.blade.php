@extends('layouts.app')

@section('content')
    <div class="bg-gray-100 mt-0">
        <div class="container pt-4">
            <h3>
                <svg class="icon-bs icon-baseline mr-1">
                    <use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#tools"></use>
                </svg>
                Regieberichte
                @unless($additionsReports->isEmpty())
                    <small class="text-muted">{{ $additionsReports->total() }} Einträge</small>
                @endunless
            </h3>

            <div class="scroll-x d-flex">
                @can('create', \App\Models\AdditionsReport::class)
                    <a class="btn btn-outline-secondary border-0 d-inline-flex align-items-center" href="{{ route('additions-reports.create') }}">
                        <svg class="icon icon-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#plus"></use>
                        </svg>
                        Regiebericht anlegen
                    </a>
                @endcan
            </div>
        </div>
    </div>

    <div class="container mt-4">
        @unless ($additionsReports->isEmpty() && !Request::get('search'))
            <div class="row">

                <div class="col col-md-6">

                    <form action="{{ route('additions-reports.index') }}" method="get">
                        @if(request()->sort)
                            <input type="hidden" id="sort" name="sort" value="{{ request()->sort }}">
                        @endif

                        <div class="input-group">
                            <input type="text" class="form-control" id="search" name="search" value="{{ Request::get('search') ?? '' }}" placeholder="Regieberichte suchen" autocomplete="off" />
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary d-flex align-items-center justify-content-center" type="submit">
                                    <svg class="icon icon-16">
                                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#search"></use>
                                    </svg>
                                </button>
                                @if (Request::get('search'))
                                    <a class="btn btn-outline-secondary d-flex align-items-center justify-content-center" @if(Request::get('sort')) href="{{ Request::url() . '?search=&sort=' . Request::get('sort') }}" @else href="{{ Request::url() . '?search=' }}" @endif>
                                        <svg class="icon icon-16">
                                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#x-circle"></use>
                                        </svg>
                                    </a>
                                @endif
                                <button type="button" class="btn btn-outline-secondary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item"
                                       @if(Request::get('sort')) href="{{ Request::url() . '?search=t:' . Auth::user()->username . (Auth::user()->settings->show_finished_items ? '' : ' !ist:erledigt') . '&sort=' . Request::get('sort') }}"
                                       @else href="{{ Request::url() . '?search=t:' . Auth::user()->username . (Auth::user()->settings->show_finished_items ? '' : ' !ist:erledigt') }}"
                                        @endif>
                                        Meine Regieberichte
                                    </a>
                                    <a class="dropdown-item"
                                       @if(Request::get('sort')) href="{{ Request::url() . '?search=t:' . Auth::user()->username . ' ist:neu' . '&sort=' . Request::get('sort') }}"
                                       @else href="{{ Request::url() . '?search=t:' . Auth::user()->username . ' ist:neu' }}"
                                        @endif>
                                        Meine nicht unterschriebenen Regieberichte
                                    </a>
                                </div>
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
                            <form action="{{ route('additions-reports.index') }}" method="get">
                                @if(request()->has('search'))
                                    <input type="hidden" id="search" name="search" value="{{ request()->search ?? '' }}">
                                @endif

                                <button type="submit" name="sort" value="services_provided_on-asc" class="dropdown-item btn-block  d-inline-flex align-items-center">
                                    <svg class="icon icon-16 mr-2">
                                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#arrow-up"></use>
                                    </svg>
                                    Datum
                                </button>
                                <button type="submit" name="sort" value="services_provided_on-desc" class="dropdown-item btn-block  d-inline-flex align-items-center">
                                    <svg class="icon icon-16 mr-2">
                                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#arrow-down"></use>
                                    </svg>
                                    Datum
                                </button>

                                <button type="submit" name="sort" value="number-asc" class="dropdown-item btn-block  d-inline-flex align-items-center">
                                    <svg class="icon icon-16 mr-2">
                                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#arrow-up"></use>
                                    </svg>
                                    Nummer
                                </button>
                                <button type="submit" name="sort" value="number-desc" class="dropdown-item btn-block  d-inline-flex align-items-center">
                                    <svg class="icon icon-16 mr-2">
                                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#arrow-down"></use>
                                    </svg>
                                    Nummer
                                </button>

                                <button type="submit" name="sort" value="status-asc" class="dropdown-item btn-block  d-inline-flex align-items-center">
                                    <svg class="icon icon-16 mr-2">
                                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#arrow-up"></use>
                                    </svg>
                                    Status
                                </button>
                                <button type="submit" name="sort" value="status-desc" class="dropdown-item btn-block  d-inline-flex align-items-center">
                                    <svg class="icon icon-16 mr-2">
                                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#arrow-down"></use>
                                    </svg>
                                    Status
                                </button>

                            </form>
                        </div>
                    </div>
                </div>

            </div>
        @endunless

        <div class="mt-3">
            @forelse ($additionsReports as $additionsReport)
                @component('additions_report.overview_card', [ 'additionsReport' => $additionsReport, 'actionRedirect' => 'index' ])
                @endcomponent

                @if(!$loop->last)
                    <hr class="m-0 mx-1" />
                @endif
            @empty
                <div class="text-center mt-4">
                    <img class="empty-state" src="{{ asset('svg/no-data.svg') }}" alt="no data" />
                    @if(Request::get('search'))
                        <p class="lead text-muted">Es wurden keine Regieberichte passend zur Suche gefunden.</p>
                    @else
                        <p class="lead text-muted">Es sind keine Regieberichte im System vorhanden.</p>
                        @can('create', \App\Models\AdditionsReport::class)
                            <p class="lead">Lege einen neuen Regiebericht an.</p>
                            <a class="btn btn-primary btn-lg d-inline-flex align-items-center" href="{{ route('additions-reports.create') }}">
                                <svg class="icon icon-20 mr-2">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#plus"></use>
                                </svg>
                                Regiebericht anlegen
                            </a>
                        @endcan
                    @endif
                </div>
            @endforelse
        </div>

        <div class="mt-2">
            {{ $additionsReports->links() }}
        </div>

        @if($additionsReports->count() > 0)
            <p class="mt-3 small">
                Der linke farbliche Rand zeigt den Status des jeweiligen Regieberichtes:
                <span class="badge badge-blue-100 text-blue-800">neu</span>
                <span class="badge badge-yellow-100 text-yellow-800">unterschrieben</span>
                <span class="badge badge-green-100 text-green-800">erledigt</span>
            </p>
        @endif

    </div>
@endsection
