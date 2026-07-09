@extends('service.index')

@section('head-action')
    @can('create', \App\Models\WageService::class)
        <a class="btn btn-primary text-white d-inline-flex align-items-center gap-2" href="{{ route('wage-services.create') }}">
            <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#plus"></use></svg>
            Lohndienstleistung anlegen
        </a>
    @endcan
@endsection

@section('tab')
    @unless ($wageServices->isEmpty() && !Request::get('search'))
        <div class="d-flex flex-wrap align-items-center gap-3 mb-3">
            <form class="flex-grow-1" action="{{ route('wage-services.index') }}" method="get">
                @if(request()->sort)
                    <input type="hidden" name="sort" value="{{ request()->sort }}">
                @endif
                <div class="input-group">
                    <input type="text" class="form-control" name="search" value="{{ Request::get('search') ?? '' }}" placeholder="Lohndienstleistungen suchen" autocomplete="off" />
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
                    <form action="{{ route('wage-services.index') }}" method="get">
                        @if(request()->search)
                            <input type="hidden" name="search" value="{{ request()->search }}">
                        @endif

                        <button type="submit" name="sort" value="name-asc" class="dropdown-item d-inline-flex align-items-center gap-2">
                            <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#arrow-up"></use></svg>Name
                        </button>
                        <button type="submit" name="sort" value="name-desc" class="dropdown-item d-inline-flex align-items-center gap-2">
                            <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#arrow-down"></use></svg>Name
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @endunless

    @if($wageServices->isEmpty())
        <div class="text-center mt-5">
            <img class="empty-state" src="{{ asset('svg/no-data.svg') }}" alt="no data" />
            @if(Request::get('search'))
                <p class="lead text-muted">Es wurden keine Lohndienstleistungen passend zur Suche gefunden.</p>
            @else
                <p class="lead text-muted">Es sind keine Lohndienstleistungen im System vorhanden.</p>
                @can('create', \App\Models\WageService::class)
                    <p class="lead">Lege eine neue Lohndienstleistung an.</p>
                    <a class="btn btn-primary text-white btn-lg d-inline-flex align-items-center gap-2" href="{{ route('wage-services.create') }}">
                        <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#plus"></use></svg>
                        Lohndienstleistung anlegen
                    </a>
                @endcan
            @endif
        </div>
    @else
        <div class="q-card q-list">
            @foreach ($wageServices as $wageService)
                @include('wage_service.overview_card_content', ['wageService' => $wageService])
            @endforeach
        </div>

        <div class="mt-3">
            {{ $wageServices->links() }}
        </div>
    @endif
@endsection
