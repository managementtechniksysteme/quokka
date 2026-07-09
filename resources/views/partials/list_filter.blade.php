{{-- Shared search + sort toolbar for detail-page list tabs (sits below the
     .q-subhead header, above the list). Preserves the active tab + current sort.
     Params:
       $action        form action URL (e.g. route('projects.show', $project))
       $placeholder   search input placeholder
       $sorts         [ 'sort-value' => 'Label', ... ]  (value ending -desc → down arrow)
       $quickFilters  optional [ 'Label' => 'search expression', ... ] --}}
@php $tab = request()->tab; $sort = request()->sort; $sortQuery = $sort ? '&sort=' . $sort : ''; @endphp
<div class="d-flex flex-wrap align-items-center gap-2 mb-3">
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
