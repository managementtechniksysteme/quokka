@extends('service.index')

@section('head-action')
    @can('create', \App\Models\MaterialService::class)
        <a class="btn btn-primary text-white d-none d-md-inline-flex align-items-center gap-2" href="{{ route('material-services.create') }}">
            <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#plus"></use></svg>
            Materialleistung anlegen
        </a>
    @endcan
@endsection

@section('head-action-mobile')
    @can('create', \App\Models\MaterialService::class)
        <a class="btn btn-primary text-white d-inline-flex align-items-center gap-2 ms-auto" style="flex: none;" href="{{ route('material-services.create') }}">
            <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#plus"></use></svg>
            Materialleistung
        </a>
    @endcan
@endsection

@section('tab')
    {{-- Desktop: search field + sort dropdown — unchanged. --}}
    @unless ($materialServices->isEmpty() && !Request::get('search'))
        <div class="d-none d-md-flex flex-wrap align-items-center gap-3 mb-3">
            <form class="flex-grow-1" action="{{ route('material-services.index') }}" method="get">
                @if(request()->sort)
                    <input type="hidden" name="sort" value="{{ request()->sort }}">
                @endif
                <div class="input-group">
                    <input type="text" class="form-control" name="search" value="{{ Request::get('search') ?? '' }}" placeholder="Materialleistungen suchen" autocomplete="off" />
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
                    <form action="{{ route('material-services.index') }}" method="get">
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

        {{-- Mobile: leading search icon inline in the field, no separate
             submit button, sort as an icon-only button opening a bottom
             sheet. No quick-filter — desktop has none either. --}}
        <div class="d-flex d-md-none align-items-center gap-2 mb-3">
            <form class="flex-grow-1" action="{{ route('material-services.index') }}" method="get">
                @if(request()->sort)
                    <input type="hidden" name="sort" value="{{ request()->sort }}">
                @endif
                <div class="position-relative flex-grow-1">
                    <div class="input-group">
                        <input type="text" class="form-control ps-5" name="search" value="{{ Request::get('search') ?? '' }}" placeholder="Materialleistungen suchen" autocomplete="off" />
                        @if (Request::get('search'))
                            <a class="btn q-btn q-btn-icon d-flex align-items-center justify-content-center" @if(Request::get('sort')) href="{{ Request::url() . '?sort=' . Request::get('sort') }}" @else href="{{ Request::url() }}" @endif>
                                <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#x-circle"></use></svg>
                            </a>
                        @endif
                    </div>
                    <svg class="icon-bs icon-16 text-muted position-absolute top-50 start-0 translate-middle-y ms-3 pe-none q-search-icon">
                        <use href="{{ asset('svg/bootstrap-icons.svg') }}#search"></use>
                    </svg>
                </div>
            </form>

            <button class="btn q-btn q-btn-icon" type="button" data-bs-toggle="offcanvas" data-bs-target="#materialServiceSortSheet" aria-controls="materialServiceSortSheet" aria-label="Sortierung">
                <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#sort-down"></use></svg>
            </button>
        </div>

        <div class="offcanvas offcanvas-bottom q-sheet" tabindex="-1" id="materialServiceSortSheet" aria-label="Sortierung">
            <div class="q-sheet__handle" aria-hidden="true"><span class="q-sheet__handle-bar"></span></div>
            <div class="offcanvas-body">
                <div class="q-sheet__label">Sortierung</div>
                <form action="{{ route('material-services.index') }}" method="get">
                    @if(request()->search)
                        <input type="hidden" name="search" value="{{ request()->search }}">
                    @endif
                    <button type="submit" name="sort" value="name-asc" class="q-row">
                        <span class="q-avatar q-avatar--muted">
                            <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#arrow-up"></use></svg>
                        </span>
                        <span class="q-row__title">Name</span>
                        @if(request('sort', 'name-asc') === 'name-asc')
                            <svg class="icon-bs icon-18 q-row__check"><use href="{{ asset('svg/bootstrap-icons.svg') }}#check"></use></svg>
                        @endif
                    </button>
                    <button type="submit" name="sort" value="name-desc" class="q-row">
                        <span class="q-avatar q-avatar--muted">
                            <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#arrow-down"></use></svg>
                        </span>
                        <span class="q-row__title">Name</span>
                        @if(request('sort') === 'name-desc')
                            <svg class="icon-bs icon-18 q-row__check"><use href="{{ asset('svg/bootstrap-icons.svg') }}#check"></use></svg>
                        @endif
                    </button>
                </form>
            </div>
        </div>
    @endunless

    @if($materialServices->isEmpty())
        <div class="q-empty-state">
            <svg class="q-empty-icon"><use href="{{ asset('svg/bootstrap-icons.svg') }}#box"></use></svg>
            @if(Request::get('search'))
                <p>Keine Materialleistungen für diese Suche gefunden.</p>
            @else
                <p>Es sind noch keine Materialleistungen vorhanden.</p>
                @can('create', \App\Models\MaterialService::class)
                    <a class="btn q-btn d-inline-flex align-items-center gap-2" href="{{ route('material-services.create') }}">
                        <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#plus"></use></svg>
                        Materialleistung anlegen
                    </a>
                @endcan
            @endif
        </div>
    @else
        <div class="q-card q-list">
            @foreach ($materialServices as $materialService)
                @include('material_service.overview_card_content', ['materialService' => $materialService])
            @endforeach
        </div>

        <div class="mt-3">
            {{ $materialServices->links() }}
        </div>
    @endif
@endsection
