@extends('service.index')

@section('head-action')
    @can('create', \App\Models\MaterialService::class)
        <a class="btn btn-primary text-white d-inline-flex align-items-center gap-2" href="{{ route('material-services.create') }}">
            <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#plus"></use></svg>
            Materialleistung anlegen
        </a>
    @endcan
@endsection

@section('tab')
    @unless ($materialServices->isEmpty() && !Request::get('search'))
        <div class="d-flex flex-wrap align-items-center gap-3 mb-3">
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
