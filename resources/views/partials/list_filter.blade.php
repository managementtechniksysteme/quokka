{{-- Shared search + sort toolbar for detail-page list tabs (sits below the
     .q-subhead header, above the list). Preserves the active tab + current sort.
     Params:
       $action        form action URL (e.g. route('projects.show', $project))
       $placeholder   search input placeholder
       $sorts         [ 'sort-value' => 'Label', ... ]  (value ending -desc → down arrow)
       $quickFilters  optional [ 'Label' => 'search expression', ... ] --}}
@php $tab = request()->tab; $sort = request()->sort; $sortQuery = $sort ? '&sort=' . $sort : ''; @endphp

{{-- Desktop: unchanged. --}}
<div class="d-none d-md-flex flex-wrap align-items-center gap-2 mb-3">
    <form class="flex-grow-1" action="{{ $action }}" method="get">
        @if($tab)<input type="hidden" name="tab" value="{{ $tab }}">@endif
        @if($sort)<input type="hidden" name="sort" value="{{ $sort }}">@endif
        <div class="input-group">
            <input type="text" class="form-control" name="search" value="{{ Request::get('search') ?? '' }}" placeholder="{{ $placeholder }}" autocomplete="off" />
            <button class="btn q-btn d-flex align-items-center" type="submit">
                <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#search"></use></svg>
            </button>
            @if(Request::get('search') !== null && Request::get('search') !== '')
                <a class="btn q-btn d-flex align-items-center" href="{{ $action }}?tab={{ $tab }}&search={{ $sortQuery }}" title="Filter zurücksetzen">
                    <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#x-circle"></use></svg>
                </a>
            @endif
            @isset($quickFilters)
                <button type="button" class="btn q-btn dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                    <span class="visually-hidden">Schnellfilter</span>
                </button>
                <div class="dropdown-menu">
                    @foreach($quickFilters as $label => $expr)
                        <a class="dropdown-item" href="{{ $action }}?tab={{ $tab }}&search={{ urlencode($expr) }}{{ $sortQuery }}">{{ $label }}</a>
                    @endforeach
                </div>
            @endisset
        </div>
    </form>

    <div class="dropdown">
        <button class="btn q-btn dropdown-toggle d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown">
            <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#sort-down"></use></svg>
            Sortierung
        </button>
        <div class="dropdown-menu dropdown-menu-end">
            <form action="{{ $action }}" method="get">
                @if($tab)<input type="hidden" name="tab" value="{{ $tab }}">@endif
                @if(Request::has('search'))<input type="hidden" name="search" value="{{ Request::get('search') ?? '' }}">@endif
                @foreach($sorts as $value => $label)
                    <button type="submit" name="sort" value="{{ $value }}" class="dropdown-item d-inline-flex align-items-center gap-2">
                        <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#arrow-{{ \Illuminate\Support\Str::endsWith($value, '-desc') ? 'down' : 'up' }}"></use></svg>
                        {{ $label }}
                    </button>
                @endforeach
            </form>
        </div>
    </div>
</div>

{{-- Mobile: leading search icon inline in the field, no separate submit
     button, quick-filter (if any) fused into the same input-group as a
     trailing segment (matches desktop, where it's also attached to the
     field), sort as a standalone icon-only button opening a bottom sheet —
     same recipe as every top-level list's toolbar (2026-07-21). Only one
     copy of this partial ever renders per page (a tabbed show page only
     ever has one active tab), so fixed sheet ids are safe sitewide. --}}
<div class="d-flex d-md-none align-items-center gap-2 mb-3">
    <form class="flex-grow-1" action="{{ $action }}" method="get">
        @if($tab)<input type="hidden" name="tab" value="{{ $tab }}">@endif
        @if($sort)<input type="hidden" name="sort" value="{{ $sort }}">@endif
        <div class="position-relative flex-grow-1">
            <div class="input-group">
                <input type="text" class="form-control ps-5" name="search" value="{{ Request::get('search') ?? '' }}" placeholder="{{ $placeholder }}" autocomplete="off" />
                @if(Request::get('search') !== null && Request::get('search') !== '')
                    <a class="btn q-btn q-btn-icon d-flex align-items-center justify-content-center" href="{{ $action }}?tab={{ $tab }}&search={{ $sortQuery }}" title="Filter zurücksetzen">
                        <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#x-circle"></use></svg>
                    </a>
                @endif
                @isset($quickFilters)
                    <button type="button" class="btn q-btn q-btn-icon d-flex align-items-center justify-content-center" data-bs-toggle="offcanvas" data-bs-target="#listFilterQuickFilterSheet" aria-controls="listFilterQuickFilterSheet" aria-label="Schnellfilter">
                        <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#funnel"></use></svg>
                    </button>
                @endisset
            </div>
            <svg class="icon-bs icon-16 text-muted position-absolute top-50 start-0 translate-middle-y ms-3 pe-none q-search-icon">
                <use href="{{ asset('svg/bootstrap-icons.svg') }}#search"></use>
            </svg>
        </div>
    </form>

    <button class="btn q-btn q-btn-icon" type="button" data-bs-toggle="offcanvas" data-bs-target="#listFilterSortSheet" aria-controls="listFilterSortSheet" aria-label="Sortierung">
        <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#sort-down"></use></svg>
    </button>
</div>

@isset($quickFilters)
    <div class="offcanvas offcanvas-bottom q-sheet" tabindex="-1" id="listFilterQuickFilterSheet" aria-label="Schnellfilter">
        <div class="q-sheet__handle" aria-hidden="true"><span class="q-sheet__handle-bar"></span></div>
        <div class="offcanvas-body">
            <div class="q-sheet__label">Schnellfilter</div>
            @foreach($quickFilters as $label => $expr)
                <a class="q-row" href="{{ $action }}?tab={{ $tab }}&search={{ urlencode($expr) }}{{ $sortQuery }}">
                    <span class="q-avatar q-avatar--muted"><svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#funnel"></use></svg></span>
                    <span class="q-row__title">{{ $label }}</span>
                    @if(Request::get('search') === $expr)
                        <svg class="icon-bs icon-18 q-row__check"><use href="{{ asset('svg/bootstrap-icons.svg') }}#check"></use></svg>
                    @endif
                </a>
            @endforeach
        </div>
    </div>
@endisset

<div class="offcanvas offcanvas-bottom q-sheet" tabindex="-1" id="listFilterSortSheet" aria-label="Sortierung">
    <div class="q-sheet__handle" aria-hidden="true"><span class="q-sheet__handle-bar"></span></div>
    <div class="offcanvas-body">
        <div class="q-sheet__label">Sortierung</div>
        <form action="{{ $action }}" method="get">
            @if($tab)<input type="hidden" name="tab" value="{{ $tab }}">@endif
            @if(Request::has('search'))<input type="hidden" name="search" value="{{ Request::get('search') ?? '' }}">@endif
            @foreach($sorts as $value => $label)
                <button type="submit" name="sort" value="{{ $value }}" class="q-row">
                    <span class="q-avatar q-avatar--muted">
                        <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#arrow-{{ \Illuminate\Support\Str::endsWith($value, '-desc') ? 'down' : 'up' }}"></use></svg>
                    </span>
                    <span class="q-row__title">{{ $label }}</span>
                    @if($sort === $value)
                        <svg class="icon-bs icon-18 q-row__check"><use href="{{ asset('svg/bootstrap-icons.svg') }}#check"></use></svg>
                    @endif
                </button>
            @endforeach
        </form>
    </div>
</div>
