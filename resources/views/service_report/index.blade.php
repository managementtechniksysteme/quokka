@extends('layouts.app')

@section('content')
    <div class="q-container">

        <div class="q-page-head">
            {{-- Desktop: icon + title + count, as before. --}}
            <div class="d-none d-md-flex align-items-center gap-3">
                <span class="q-head-icon">
                    <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#gear"></use></svg>
                </span>
                <div>
                    <h1 class="q-title">Serviceberichte</h1>
                    @unless($serviceReports->isEmpty())
                        <div class="q-subtitle">{{ trans_choice('messages.entries', $serviceReports->total()) }}</div>
                    @endunless
                </div>
            </div>

            <div class="d-none d-md-flex gap-2">
                @can('create', \App\Models\ServiceReport::class)
                    <a class="btn btn-primary text-white d-inline-flex align-items-center gap-2" href="{{ route('service-reports.create') }}">
                        <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#plus"></use></svg>
                        Servicebericht anlegen
                    </a>
                @endcan
                @can('downloadList', \App\Models\ServiceReport::class)
                    <a class="btn q-btn d-inline-flex align-items-center gap-2" href="{{ route('service-reports.download-list') }}" target="_blank">
                        <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#printer"></use></svg>
                        PDF Liste
                    </a>
                @endcan
            </div>

            {{-- Mobile: count inline with the actions — PDF Liste icon-only,
                 create stays labeled (shortened to just the entity name).
                 ms-auto on the button group, not justify-content-between on
                 the parent — space-between collapses a single remaining
                 flex child to the start when the count is conditionally
                 absent (empty list). --}}
            <div class="d-flex d-md-none align-items-center gap-2">
                @unless($serviceReports->isEmpty())
                    <div class="q-subtitle mb-0">{{ trans_choice('messages.entries', $serviceReports->total()) }}</div>
                @endunless
                <div class="d-flex align-items-center gap-2 ms-auto" style="flex: none;">
                    @can('create', \App\Models\ServiceReport::class)
                        <a class="btn btn-primary text-white d-inline-flex align-items-center gap-2" style="flex: none;" href="{{ route('service-reports.create') }}">
                            <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#plus"></use></svg>
                            Servicebericht
                        </a>
                    @endcan
                    @can('downloadList', \App\Models\ServiceReport::class)
                        <a class="btn q-btn q-btn-icon d-flex align-items-center justify-content-center" href="{{ route('service-reports.download-list') }}" target="_blank" aria-label="PDF Liste">
                            <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#printer"></use></svg>
                        </a>
                    @endcan
                </div>
            </div>
        </div>

        @unless ($serviceReports->isEmpty() && !Request::get('search'))
            {{-- Desktop: search field + quick-filter split-dropdown + sort dropdown — unchanged. --}}
            <div class="d-none d-md-flex flex-wrap align-items-center gap-3 mb-3">
                <form class="flex-grow-1" action="{{ route('service-reports.index') }}" method="get">
                    @if(request()->sort)
                        <input type="hidden" name="sort" value="{{ request()->sort }}">
                    @endif
                    <div class="input-group">
                        <input type="text" class="form-control" name="search" value="{{ Request::get('search') ?? '' }}" placeholder="Serviceberichte suchen" autocomplete="off" />
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
                                Meine Serviceberichte
                            </a>
                            <a class="dropdown-item"
                               @if(Request::get('sort')) href="{{ Request::url() . '?search=t:' . Auth::user()->username . ' ist:neu' . '&sort=' . Request::get('sort') }}"
                               @else href="{{ Request::url() . '?search=t:' . Auth::user()->username . ' ist:neu' }}" @endif>
                                Meine nicht unterschriebenen Serviceberichte
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
                        <form action="{{ route('service-reports.index') }}" method="get">
                            @if(request()->has('search'))
                                <input type="hidden" name="search" value="{{ request()->search ?? '' }}">
                            @endif

                            <button type="submit" name="sort" value="number-asc" class="dropdown-item d-inline-flex align-items-center gap-2">
                                <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#arrow-up"></use></svg>Nummer
                            </button>
                            <button type="submit" name="sort" value="number-desc" class="dropdown-item d-inline-flex align-items-center gap-2">
                                <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#arrow-down"></use></svg>Nummer
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
                <form class="flex-grow-1" action="{{ route('service-reports.index') }}" method="get">
                    @if(request()->sort)
                        <input type="hidden" name="sort" value="{{ request()->sort }}">
                    @endif
                    <div class="position-relative flex-grow-1">
                        <div class="input-group">
                            <input type="text" class="form-control ps-5" name="search" value="{{ Request::get('search') ?? '' }}" placeholder="Serviceberichte suchen" autocomplete="off" />
                            @if (Request::get('search'))
                                <a class="btn q-btn q-btn-icon d-flex align-items-center justify-content-center" @if(Request::get('sort')) href="{{ Request::url() . '?search=&sort=' . Request::get('sort') }}" @else href="{{ Request::url() . '?search=' }}" @endif>
                                    <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#x-circle"></use></svg>
                                </a>
                            @endif
                            <button type="button" class="btn q-btn q-btn-icon d-flex align-items-center justify-content-center" data-bs-toggle="offcanvas" data-bs-target="#serviceReportQuickFilterSheet" aria-controls="serviceReportQuickFilterSheet" aria-label="Schnellfilter">
                                <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#funnel"></use></svg>
                            </button>
                        </div>
                        <svg class="icon-bs icon-16 text-muted position-absolute top-50 start-0 translate-middle-y ms-3 pe-none q-search-icon">
                            <use href="{{ asset('svg/bootstrap-icons.svg') }}#search"></use>
                        </svg>
                    </div>
                </form>

                <button class="btn q-btn q-btn-icon" type="button" data-bs-toggle="offcanvas" data-bs-target="#serviceReportSortSheet" aria-controls="serviceReportSortSheet" aria-label="Sortierung">
                    <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#sort-down"></use></svg>
                </button>
            </div>

            @php
                $serviceReportFilterUsername = Auth::user()->username;
                $serviceReportShowFinished = Auth::user()->settings->show_finished_items;
                $serviceReportQuickFilters = [
                    'Meine Serviceberichte' => 't:' . $serviceReportFilterUsername . ($serviceReportShowFinished ? '' : ' !ist:erledigt'),
                    'Meine nicht unterschriebenen Serviceberichte' => 't:' . $serviceReportFilterUsername . ' ist:neu',
                ];
            @endphp
            <div class="offcanvas offcanvas-bottom q-sheet" tabindex="-1" id="serviceReportQuickFilterSheet" aria-label="Schnellfilter">
                <div class="q-sheet__handle" aria-hidden="true"><span class="q-sheet__handle-bar"></span></div>
                <div class="offcanvas-body">
                    <div class="q-sheet__label">Schnellfilter</div>
                    @foreach($serviceReportQuickFilters as $quickFilterLabel => $quickFilterExpr)
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
                $serviceReportSortOptions = [
                    'number-asc' => ['Nummer', 'arrow-up'],
                    'number-desc' => ['Nummer', 'arrow-down'],
                    'status-asc' => ['Status', 'arrow-up'],
                    'status-desc' => ['Status', 'arrow-down'],
                ];
            @endphp
            <div class="offcanvas offcanvas-bottom q-sheet" tabindex="-1" id="serviceReportSortSheet" aria-label="Sortierung">
                <div class="q-sheet__handle" aria-hidden="true"><span class="q-sheet__handle-bar"></span></div>
                <div class="offcanvas-body">
                    <div class="q-sheet__label">Sortierung</div>
                    <form action="{{ route('service-reports.index') }}" method="get">
                        @if(request()->has('search'))
                            <input type="hidden" name="search" value="{{ request()->search ?? '' }}">
                        @endif
                        @foreach($serviceReportSortOptions as $sortValue => $sortMeta)
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

        @if($serviceReports->isEmpty())
            <div class="q-empty-state">
                <svg class="q-empty-icon"><use href="{{ asset('svg/bootstrap-icons.svg') }}#gear"></use></svg>
                @if(Request::get('search'))
                    <p>Keine Serviceberichte für diese Suche gefunden.</p>
                @else
                    <p>Es sind noch keine Serviceberichte vorhanden.</p>
                    @can('create', \App\Models\ServiceReport::class)
                        <a class="btn q-btn d-inline-flex align-items-center gap-2" href="{{ route('service-reports.create') }}">
                            <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#plus"></use></svg>
                            Servicebericht anlegen
                        </a>
                    @endcan
                @endif
            </div>
        @else
            <div class="q-card q-list">
                @foreach ($serviceReports as $serviceReport)
                    @include('service_report.overview_card_content', ['serviceReport' => $serviceReport, 'actionRedirect' => 'index'])
                @endforeach
            </div>

            <div class="mt-3">
                {{ $serviceReports->links() }}
            </div>
        @endif
    </div>
@endsection
