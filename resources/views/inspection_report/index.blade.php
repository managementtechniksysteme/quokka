@extends('layouts.app')

@section('content')
    <div class="q-container">

        <div class="q-page-head">
            <div class="d-flex align-items-center gap-3">
                <span class="q-head-icon">
                    <svg class="icon-bs icon-20"><use xlink:href="{{ asset('svg/bootstrap-icons.svg') }}#patch-check"></use></svg>
                </span>
                <div>
                    <h1 class="q-title">Prüfberichte</h1>
                    @unless($inspectionReports->isEmpty())
                        <div class="q-subtitle">{{ trans_choice('messages.entries', $inspectionReports->total()) }}</div>
                    @endunless
                </div>
            </div>

            @can('create', \App\Models\InspectionReport::class)
                <a class="btn btn-primary text-white d-inline-flex align-items-center gap-2" href="{{ route('inspection-reports.create') }}">
                    <svg class="icon icon-16"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#plus"></use></svg>
                    Prüfbericht anlegen
                </a>
            @endcan
        </div>

        @unless ($inspectionReports->isEmpty() && !Request::get('search'))
            <div class="d-flex flex-wrap align-items-center gap-3 mb-3">
                <form class="flex-grow-1" action="{{ route('inspection-reports.index') }}" method="get">
                    @if(request()->sort)
                        <input type="hidden" name="sort" value="{{ request()->sort }}">
                    @endif
                    <div class="input-group">
                        <input type="text" class="form-control" name="search" value="{{ Request::get('search') ?? '' }}" placeholder="Prüfberichte suchen" autocomplete="off" />
                        <button class="btn q-btn d-flex align-items-center" type="submit">
                            <svg class="icon icon-16"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#search"></use></svg>
                        </button>
                        @if (Request::get('search'))
                            <a class="btn q-btn d-flex align-items-center" @if(Request::get('sort')) href="{{ Request::url() . '?search=&sort=' . Request::get('sort') }}" @else href="{{ Request::url() . '?search=' }}" @endif>
                                <svg class="icon icon-16"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#x-circle"></use></svg>
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
                        <svg class="icon icon-16"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#arrow-up"></use></svg>
                        Sortierung
                    </button>
                    <div class="dropdown-menu dropdown-menu-end">
                        <form action="{{ route('inspection-reports.index') }}" method="get">
                            @if(request()->has('search'))
                                <input type="hidden" name="search" value="{{ request()->search ?? '' }}">
                            @endif

                            <button type="submit" name="sort" value="inspected_on-asc" class="dropdown-item d-inline-flex align-items-center gap-2">
                                <svg class="icon icon-16"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#arrow-up"></use></svg>Datum
                            </button>
                            <button type="submit" name="sort" value="inspected_on-desc" class="dropdown-item d-inline-flex align-items-center gap-2">
                                <svg class="icon icon-16"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#arrow-down"></use></svg>Datum
                            </button>
                            <button type="submit" name="sort" value="status-asc" class="dropdown-item d-inline-flex align-items-center gap-2">
                                <svg class="icon icon-16"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#arrow-up"></use></svg>Status
                            </button>
                            <button type="submit" name="sort" value="status-desc" class="dropdown-item d-inline-flex align-items-center gap-2">
                                <svg class="icon icon-16"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#arrow-down"></use></svg>Status
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endunless

        @if($inspectionReports->isEmpty())
            <div class="text-center mt-5">
                <img class="empty-state" src="{{ asset('svg/no-data.svg') }}" alt="no data" />
                @if(Request::get('search'))
                    <p class="lead text-muted">Es wurden keine Prüfberichte passend zur Suche gefunden.</p>
                @else
                    <p class="lead text-muted">Es sind keine Prüfberichte im System vorhanden.</p>
                    @can('create', \App\Models\InspectionReport::class)
                        <p class="lead">Lege einen neuen Prüfbericht an.</p>
                        <a class="btn btn-primary text-white btn-lg d-inline-flex align-items-center gap-2" href="{{ route('inspection-reports.create') }}">
                            <svg class="icon icon-20"><use xlink:href="{{ asset('svg/feather-sprite.svg') }}#plus"></use></svg>
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
