@extends('layouts.app')

@section('content')
    <div class="q-container">

        <div class="q-page-head">
            {{-- Desktop: icon + title + count, as before. --}}
            <div class="d-none d-md-flex align-items-center gap-3">
                <span class="q-head-icon">
                    <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#patch-check"></use></svg>
                </span>
                <div>
                    <h1 class="q-title">Prüfberichte</h1>
                    @unless($inspectionReports->isEmpty())
                        <div class="q-subtitle">{{ trans_choice('messages.entries', $inspectionReports->total()) }}</div>
                    @endunless
                </div>
            </div>

            @can('create', \App\Models\InspectionReport::class)
                <a class="btn btn-primary text-white d-none d-md-inline-flex align-items-center gap-2" href="{{ route('inspection-reports.create') }}">
                    <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#plus"></use></svg>
                    Prüfbericht anlegen
                </a>
            @endcan

            {{-- Mobile: count inline with the actions, create label
                 shortened to just the entity name. --}}
            <div class="d-flex d-md-none align-items-center gap-2">
                @unless($inspectionReports->isEmpty())
                    <div class="q-subtitle mb-0">{{ trans_choice('messages.entries', $inspectionReports->total()) }}</div>
                @endunless
                @can('create', \App\Models\InspectionReport::class)
                    <a class="btn btn-primary text-white d-inline-flex align-items-center gap-2 ms-auto" style="flex: none;" href="{{ route('inspection-reports.create') }}">
                        <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#plus"></use></svg>
                        Prüfbericht
                    </a>
                @endcan
            </div>
        </div>

        @unless ($inspectionReports->isEmpty() && !Request::get('search'))
            {{-- Desktop: search field + quick-filter split-dropdown + sort dropdown — unchanged. --}}
            <div class="d-none d-md-flex flex-wrap align-items-center gap-3 mb-3">
                <form class="flex-grow-1" action="{{ route('inspection-reports.index') }}" method="get">
                    @if(request()->sort)
                        <input type="hidden" name="sort" value="{{ request()->sort }}">
                    @endif
                    <div class="input-group">
                        <filter-search-input name="search" input_class="form-control" :fields="{{ json_encode($filterFields) }}" suggestions_url="{{ route('filter-suggestions.search') }}" model="inspection_report" initial_value="{{ Request::get('search') ?? '' }}" placeholder="Prüfberichte suchen"></filter-search-input>
                        <button class="btn q-btn d-flex align-items-center" type="submit">
                            <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#search"></use></svg>
                        </button>
                        @if (Request::get('search'))
                            <a class="btn q-btn d-flex align-items-center" @if(Request::get('sort')) href="{{ Request::url() . '?search=&sort=' . Request::get('sort') }}" @else href="{{ Request::url() . '?search=' }}" @endif>
                                <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#x-circle"></use></svg>
                            </a>
                        @endif
                        <button type="button" class="btn q-btn dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="visually-hidden">Toggle Dropdown</span>
                        </button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item"
                               @if(Request::get('sort')) href="{{ Request::url() . '?search=t:' . Auth::user()->username . (Auth::user()->settings->show_finished_items ? '' : ' !ist:erledigt') . '&sort=' . Request::get('sort') }}"
                               @else href="{{ Request::url() . '?search=t:' . Auth::user()->username . (Auth::user()->settings->show_finished_items ? '' : ' !ist:erledigt') }}" @endif>
                                Meine Prüfberichte
                            </a>
                            <a class="dropdown-item"
                               @if(Request::get('sort')) href="{{ Request::url() . '?search=t:' . Auth::user()->username . ' ist:neu' . '&sort=' . Request::get('sort') }}"
                               @else href="{{ Request::url() . '?search=t:' . Auth::user()->username . ' ist:neu' }}" @endif>
                                Meine nicht unterschriebenen Prüfberichte
                            </a>
                        </div>
                    </div>
                </form>

                <div class="dropdown ms-auto">
                    <button class="btn q-btn dropdown-toggle d-flex align-items-center gap-2" type="button" id="sortOrderDropdown" data-bs-toggle="dropdown">
                        <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#sort-down"></use></svg>
                        Sortierung
                    </button>
                    <div class="dropdown-menu dropdown-menu-end">
                        <form action="{{ route('inspection-reports.index') }}" method="get">
                            @if(request()->has('search'))
                                <input type="hidden" name="search" value="{{ request()->search ?? '' }}">
                            @endif

                            <button type="submit" name="sort" value="inspected_on-asc" class="dropdown-item d-inline-flex align-items-center gap-2">
                                <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#arrow-up"></use></svg>Datum
                            </button>
                            <button type="submit" name="sort" value="inspected_on-desc" class="dropdown-item d-inline-flex align-items-center gap-2">
                                <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#arrow-down"></use></svg>Datum
                            </button>
                            <button type="submit" name="sort" value="status-asc" class="dropdown-item d-inline-flex align-items-center gap-2">
                                <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#arrow-up"></use></svg>Status
                            </button>
                            <button type="submit" name="sort" value="status-desc" class="dropdown-item d-inline-flex align-items-center gap-2">
                                <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#arrow-down"></use></svg>Status
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Mobile: leading search icon inline in the field, no separate
                 submit button. Filter joins the input-group as a fused
                 trailing segment; sort stays a standalone icon button. --}}
            <div class="d-flex d-md-none align-items-center gap-2 mb-3">
                <form class="flex-grow-1" action="{{ route('inspection-reports.index') }}" method="get">
                    @if(request()->sort)
                        <input type="hidden" name="sort" value="{{ request()->sort }}">
                    @endif
                    <div class="position-relative flex-grow-1">
                        <div class="input-group">
                            <filter-search-input name="search" input_class="form-control ps-5" :fields="{{ json_encode($filterFields) }}" suggestions_url="{{ route('filter-suggestions.search') }}" model="inspection_report" initial_value="{{ Request::get('search') ?? '' }}" placeholder="Prüfberichte suchen"></filter-search-input>
                            @if (Request::get('search'))
                                <a class="btn q-btn q-btn-icon d-flex align-items-center justify-content-center" @if(Request::get('sort')) href="{{ Request::url() . '?search=&sort=' . Request::get('sort') }}" @else href="{{ Request::url() . '?search=' }}" @endif>
                                    <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#x-circle"></use></svg>
                                </a>
                            @endif
                            <button type="button" class="btn q-btn q-btn-icon d-flex align-items-center justify-content-center" data-bs-toggle="offcanvas" data-bs-target="#inspectionReportQuickFilterSheet" aria-controls="inspectionReportQuickFilterSheet" aria-label="Schnellfilter">
                                <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#funnel"></use></svg>
                            </button>
                        </div>
                        <svg class="icon-bs icon-16 text-muted position-absolute top-50 start-0 translate-middle-y ms-3 pe-none q-search-icon">
                            <use href="{{ asset('svg/bootstrap-icons.svg') }}#search"></use>
                        </svg>
                    </div>
                </form>

                <button class="btn q-btn q-btn-icon" type="button" data-bs-toggle="offcanvas" data-bs-target="#inspectionReportSortSheet" aria-controls="inspectionReportSortSheet" aria-label="Sortierung">
                    <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#sort-down"></use></svg>
                </button>
            </div>

            @php
                $inspectionReportFilterUsername = Auth::user()->username;
                $inspectionReportShowFinished = Auth::user()->settings->show_finished_items;
                $inspectionReportQuickFilters = [
                    'Meine Prüfberichte' => 't:' . $inspectionReportFilterUsername . ($inspectionReportShowFinished ? '' : ' !ist:erledigt'),
                    'Meine nicht unterschriebenen Prüfberichte' => 't:' . $inspectionReportFilterUsername . ' ist:neu',
                ];
            @endphp
            <div class="offcanvas offcanvas-bottom q-sheet" tabindex="-1" id="inspectionReportQuickFilterSheet" aria-label="Schnellfilter">
                <div class="q-sheet__handle" aria-hidden="true"><span class="q-sheet__handle-bar"></span></div>
                <div class="offcanvas-body">
                    <div class="q-sheet__label">Schnellfilter</div>
                    @foreach($inspectionReportQuickFilters as $quickFilterLabel => $quickFilterExpr)
                        <a class="q-row" href="{{ Request::url() . '?search=' . urlencode($quickFilterExpr) . (Request::get('sort') ? '&sort=' . Request::get('sort') : '') }}">
                            <span class="q-row__title">{{ $quickFilterLabel }}</span>
                            @if(Request::get('search') === $quickFilterExpr)
                                <svg class="icon-bs icon-18 q-row__check"><use href="{{ asset('svg/bootstrap-icons.svg') }}#check"></use></svg>
                            @endif
                        </a>
                    @endforeach
                </div>
            </div>

            @php
                $inspectionReportSortOptions = [
                    'inspected_on-asc' => ['Datum', 'arrow-up'],
                    'inspected_on-desc' => ['Datum', 'arrow-down'],
                    'status-asc' => ['Status', 'arrow-up'],
                    'status-desc' => ['Status', 'arrow-down'],
                ];
            @endphp
            <div class="offcanvas offcanvas-bottom q-sheet" tabindex="-1" id="inspectionReportSortSheet" aria-label="Sortierung">
                <div class="q-sheet__handle" aria-hidden="true"><span class="q-sheet__handle-bar"></span></div>
                <div class="offcanvas-body">
                    <div class="q-sheet__label">Sortierung</div>
                    <form action="{{ route('inspection-reports.index') }}" method="get">
                        @if(request()->has('search'))
                            <input type="hidden" name="search" value="{{ request()->search ?? '' }}">
                        @endif
                        @foreach($inspectionReportSortOptions as $sortValue => $sortMeta)
                            <button type="submit" name="sort" value="{{ $sortValue }}" class="q-row">
                                <span class="q-avatar q-avatar--muted">
                                    <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#{{ $sortMeta[1] }}"></use></svg>
                                </span>
                                <span class="q-row__title">{{ $sortMeta[0] }}</span>
                                @if(request('sort') === $sortValue)
                                    <svg class="icon-bs icon-18 q-row__check"><use href="{{ asset('svg/bootstrap-icons.svg') }}#check"></use></svg>
                                @endif
                            </button>
                        @endforeach
                    </form>
                </div>
            </div>
        @endunless

        @if($inspectionReports->isEmpty())
            <div class="q-empty-state">
                <svg class="q-empty-icon"><use href="{{ asset('svg/bootstrap-icons.svg') }}#patch-check"></use></svg>
                @if(Request::get('search'))
                    <p>Keine Prüfberichte für diese Suche gefunden.</p>
                @else
                    <p>Es sind noch keine Prüfberichte vorhanden.</p>
                    @can('create', \App\Models\InspectionReport::class)
                        <a class="btn q-btn d-inline-flex align-items-center gap-2" href="{{ route('inspection-reports.create') }}">
                            <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#plus"></use></svg>
                            Prüfbericht anlegen
                        </a>
                    @endcan
                @endif
            </div>
        @else
            <div class="q-card q-list">
                @foreach ($inspectionReports as $inspectionReport)
                    @include('inspection_report.overview_card_content', ['inspectionReport' => $inspectionReport, 'actionRedirect' => 'index'])
                @endforeach
            </div>

            <div class="mt-3">
                {{ $inspectionReports->links() }}
            </div>
        @endif
    </div>
@endsection
