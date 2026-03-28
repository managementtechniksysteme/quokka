@extends('service.index')

@section('tab')
    @unless ($wageServices->isEmpty() && !Request::get('search'))
        @can('create', \App\Models\WageService::class)
            <a class="btn btn-outline-secondary d-inline-flex align-items-center" href="{{ route('wage-services.create') }}">
                <svg class="icon icon-16 me-2">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#plus"></use>
                </svg>
                Lohndienstleistung anlegen
            </a>
        @endcan

        <div class="row mt-4">

            <div class="col col-lg-6">

                <form action="{{ route('wage-services.index') }}" method="get">
                    @if(request()->sort)
                        <input type="hidden" id="sort" name="sort" value="{{ request()->sort }}">
                    @endif

                    <div class="input-group">
                        <input type="text" class="form-control" id="search" name="search" value="{{ Request::get('search') ?? '' }}" placeholder="Lohndienstleistungen suchen" autocomplete="off" />
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

                </form>

            </div>

            <div class="col-auto ms-auto">
                <div class="dropdown">
                    <button class="btn btn-outline-secondary w-100 dropdown-toggle d-flex align-items-center justify-content-center" type="button" id="sortOrderDropdown" data-bs-toggle="dropdown">
                        <svg class="icon icon-16 me-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#arrow-up"></use>
                        </svg>
                        Sortierung
                    </button>
                    <div class="dropdown-menu dropdown-menu-end w-100">
                        <form action="{{ route('wage-services.index') }}" method="get">
                            @if(request()->search)
                                <input type="hidden" id="search" name="search" value="{{ request()->search }}">
                            @endif

                            <button type="submit" name="sort" value="name-asc" class="dropdown-item w-100  d-inline-flex align-items-center">
                                <svg class="icon icon-16 me-2">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#arrow-up"></use>
                                </svg>
                                Name
                            </button>
                            <button type="submit" name="sort" value="name-desc" class="dropdown-item w-100  d-inline-flex align-items-center">
                                <svg class="icon icon-16 me-2">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#arrow-down"></use>
                                </svg>
                                Name
                            </button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    @endunless

    <div class="mt-3">
        @forelse ($wageServices as $wageService)
            @component('wage_service.overview_card', [ 'wageService' => $wageService ])
            @endcomponent

                @if(!$loop->last)
                    <hr class="m-0 mx-1" />
                @endif

        @empty
            <div class="text-center">
                <img class="empty-state" src="{{ asset('svg/no-data.svg') }}" alt="no data" />
                @if(Request::get('search'))
                    <p class="lead text-muted">Es wurden keine Lohndienstleistungen passend zur Suche gefunden.</p>
                @else
                    <p class="lead text-muted">Es sind keine Lohndienstleistungen im System vorhanden.</p>
                    @can('create', \App\Models\WageService::class)
                        <p class="lead">Lege eine neue Lohndienstleistung an.</p>
                        <a class="btn btn-primary btn-lg d-inline-flex align-items-center" href="{{ route('wage-services.create') }}">
                            <svg class="icon icon-20 me-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#plus"></use>
                            </svg>
                            Lohndienstleistung anlegen
                        </a>
                    @endcan
                @endif
            </div>
        @endforelse
    </div>

    <div class="mt-2">
        {{ $wageServices->links() }}
    </div>
@endsection
