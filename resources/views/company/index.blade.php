@extends('layouts.app')

@section('content')
    <div class="q-container">

        <div class="q-page-head">
            {{-- Desktop: icon + title + count, as before. --}}
            <div class="d-none d-md-flex align-items-center gap-3">
                <span class="q-head-icon">
                    <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#briefcase"></use></svg>
                </span>
                <div>
                    <h1 class="q-title">Firmen</h1>
                    @unless($companies->isEmpty())
                        <div class="q-subtitle">{{ trans_choice('messages.entries', $companies->total()) }}</div>
                    @endunless
                </div>
            </div>
            @can('create', \App\Models\Company::class)
                <a class="btn btn-primary text-white d-none d-md-inline-flex align-items-center gap-2" href="{{ route('companies.create') }}">
                    <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#plus"></use></svg>
                    Firma anlegen
                </a>
            @endcan

            {{-- Mobile: the app bar already carries "Firmen" + its own icon
                 (partials/navbar.blade.php) — collapses to just the count,
                 inline with the create button on one row (2026-07-21 —
                 previously its own row below, matching Quokka Mobile.dc.html
                 frame 3's compact header). Label shortened to just "Firma"
                 (2026-07-21, user: the icon already reads as "create," a bit
                 airier). ms-auto on the button, not justify-content-between
                 on the parent — the count is conditional (@unless empty),
                 and space-between with only ONE remaining flex child
                 collapses it to the start instead of the end (caught on
                 task's identical pattern — button shifted left on an empty
                 list; same fix applied here for consistency). --}}
            <div class="d-flex d-md-none align-items-center gap-2">
                @unless($companies->isEmpty())
                    <div class="q-subtitle mb-0">{{ trans_choice('messages.entries', $companies->total()) }}</div>
                @endunless
                @can('create', \App\Models\Company::class)
                    {{-- inline style:"flex:none" (not the flex-grow-0 utility
                         — that only zeroes flex-grow and leaves the
                         component rule's flex-basis:0% behind, which was
                         actually causing this to overflow) overrides
                         .q-page-head .btn{flex:1}. That fill-the-row rule is
                         right for the dashboard's 2 CTAs / a detail page's
                         lone edit button, but this button sits next to text,
                         not alone, so it should stay natural width. --}}
                    <a class="btn btn-primary text-white d-inline-flex align-items-center gap-2 ms-auto" style="flex: none;" href="{{ route('companies.create') }}">
                        <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#plus"></use></svg>
                        Firma
                    </a>
                @endcan
            </div>
        </div>

        @unless ($companies->isEmpty() && !Request::get('search'))
            {{-- Desktop: search field + submit button, "Sortierung" dropdown
                 with its text label — unchanged. --}}
            <div class="d-none d-md-flex flex-wrap align-items-center gap-3 mb-3">
                <form class="flex-grow-1" action="{{ route('companies.index') }}" method="get">
                    @if(request()->sort)
                        <input type="hidden" name="sort" value="{{ request()->sort }}">
                    @endif
                    <div class="input-group">
                        <filter-search-input name="search" input_class="form-control" :fields="{{ json_encode($filterFields) }}" suggestions_url="{{ route('filter-suggestions.search') }}" model="company" initial_value="{{ Request::get('search') ?? '' }}" placeholder="Firmen suchen"></filter-search-input>
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
                        <form action="{{ route('companies.index') }}" method="get">
                            @if(request()->search)
                                <input type="hidden" name="search" value="{{ request()->search }}">
                            @endif
                            <button type="submit" name="sort" value="name-asc" class="dropdown-item d-inline-flex align-items-center gap-2">
                                <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#arrow-up"></use></svg>
                                Name
                            </button>
                            <button type="submit" name="sort" value="name-desc" class="dropdown-item d-inline-flex align-items-center gap-2">
                                <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#arrow-down"></use></svg>
                                Name
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Mobile: leading search icon inline in the field, no separate
                 submit button (Enter already submits the form; the clear "x"
                 is a different, still-needed action so it stays) — and sort
                 reduced to an icon-only button, inline with search on one
                 row (2026-07-21 user request). --}}
            <div class="d-flex d-md-none align-items-center gap-3 mb-3">
                <form class="flex-grow-1" action="{{ route('companies.index') }}" method="get">
                    @if(request()->sort)
                        <input type="hidden" name="sort" value="{{ request()->sort }}">
                    @endif
                    {{-- A real Bootstrap .input-group again (2026-07-21 —
                         first pass made the clear button its own separate
                         boxed element; user wants it fused to the field like
                         desktop's does) so its automatic corner-collapsing
                         still connects the field + clear button into one
                         surface. The icon lives OUTSIDE .input-group
                         entirely, absolutely positioned against this
                         wrapper div instead — position:absolute does NOT
                         exempt an element from :first-child/:last-child
                         matching (those are DOM-structural, not
                         layout-aware), so an icon placed INSIDE .input-group
                         still counts as a real sibling for its corner-
                         rounding logic; with no clear button rendered that
                         made the icon the :last-child instead of the input,
                         squaring off the input's right corners (caught via
                         screenshot, not just reasoning about it up front). --}}
                    <div class="position-relative flex-grow-1">
                        <div class="input-group">
                            <filter-search-input name="search" input_class="form-control ps-5" :fields="{{ json_encode($filterFields) }}" suggestions_url="{{ route('filter-suggestions.search') }}" model="company" initial_value="{{ Request::get('search') ?? '' }}" placeholder="Firmen suchen"></filter-search-input>
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

                {{-- Sort as a bottom sheet, not a dropdown (2026-07-21 user
                     request — picking one of a short list of options is the
                     idiomatic mobile action-sheet case; reuses the same
                     offcanvas-bottom + .q-sheet infra as the Mehr sheet). --}}
                <button class="btn q-btn q-btn-icon" type="button" data-bs-toggle="offcanvas" data-bs-target="#companySortSheet" aria-controls="companySortSheet" aria-label="Sortierung">
                    <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#sort-down"></use></svg>
                </button>
            </div>

            <div class="offcanvas offcanvas-bottom q-sheet" tabindex="-1" id="companySortSheet" aria-label="Sortierung">
                <div class="q-sheet__handle" aria-hidden="true"><span class="q-sheet__handle-bar"></span></div>
                <div class="offcanvas-body">
                    <div class="q-sheet__label">Sortierung</div>
                    <form action="{{ route('companies.index') }}" method="get">
                        @if(request()->search)
                            <input type="hidden" name="search" value="{{ request()->search }}">
                        @endif
                        <button type="submit" name="sort" value="name-asc" class="q-row">
                            <span class="q-avatar q-avatar--muted">
                                <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#arrow-up"></use></svg>
                            </span>
                            <span class="q-row__title">Name aufsteigend</span>
                            @if(request('sort', 'name-asc') === 'name-asc')
                                <svg class="icon-bs icon-18 q-row__check"><use href="{{ asset('svg/bootstrap-icons.svg') }}#check"></use></svg>
                            @endif
                        </button>
                        <button type="submit" name="sort" value="name-desc" class="q-row">
                            <span class="q-avatar q-avatar--muted">
                                <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#arrow-down"></use></svg>
                            </span>
                            <span class="q-row__title">Name absteigend</span>
                            @if(request('sort') === 'name-desc')
                                <svg class="icon-bs icon-18 q-row__check"><use href="{{ asset('svg/bootstrap-icons.svg') }}#check"></use></svg>
                            @endif
                        </button>
                    </form>
                </div>
            </div>
        @endunless

        @if($companies->isEmpty())
            <div class="q-empty-state">
                <svg class="q-empty-icon"><use href="{{ asset('svg/bootstrap-icons.svg') }}#briefcase"></use></svg>
                @if(Request::get('search'))
                    <p>Keine Firmen für diese Suche gefunden.</p>
                @else
                    <p>Es sind noch keine Firmen vorhanden.</p>
                    @can('create', \App\Models\Company::class)
                        <a class="btn q-btn d-inline-flex align-items-center gap-2" href="{{ route('companies.create') }}">
                            <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#plus"></use></svg>
                            Firma anlegen
                        </a>
                    @endcan
                @endif
            </div>
        @else
            <div class="q-card q-list">
                @foreach ($companies as $company)
                    @include('company.overview_card_content', ['company' => $company])
                @endforeach
            </div>

            <div class="mt-3">
                {{ $companies->links() }}
            </div>
        @endif
    </div>
@endsection
