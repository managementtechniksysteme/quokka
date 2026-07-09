@extends('layouts.app')

@section('content')
    <div class="q-container">

        <div class="q-page-head">
            <div class="d-flex align-items-center gap-3">
                <span class="q-head-icon">
                    <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#tools"></use></svg>
                </span>
                <div>
                    <h1 class="q-title">Regieberichte</h1>
                    @unless($additionsReports->isEmpty())
                        <div class="q-subtitle">{{ trans_choice('messages.entries', $additionsReports->total()) }}</div>
                    @endunless
                </div>
            </div>

            @can('create', \App\Models\AdditionsReport::class)
                <a class="btn btn-primary text-white d-inline-flex align-items-center gap-2" href="{{ route('additions-reports.create') }}">
                    <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#plus"></use></svg>
                    Regiebericht anlegen
                </a>
            @endcan
        </div>

        @unless ($additionsReports->isEmpty() && !Request::get('search'))
            <div class="d-flex flex-wrap align-items-center gap-3 mb-3">
                <form class="flex-grow-1" action="{{ route('additions-reports.index') }}" method="get">
                    @if(request()->sort)
                        <input type="hidden" name="sort" value="{{ request()->sort }}">
                    @endif
                    <div class="input-group">
                        <input type="text" class="form-control" name="search" value="{{ Request::get('search') ?? '' }}" placeholder="Regieberichte suchen" autocomplete="off" />
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
                                Meine Regieberichte
                            </a>
                            <a class="dropdown-item"
                               @if(Request::get('sort')) href="{{ Request::url() . '?search=t:' . Auth::user()->username . ' ist:neu' . '&sort=' . Request::get('sort') }}"
                               @else href="{{ Request::url() . '?search=t:' . Auth::user()->username . ' ist:neu' }}" @endif>
                                Meine nicht unterschriebenen Regieberichte
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
                        <form action="{{ route('additions-reports.index') }}" method="get">
                            @if(request()->has('search'))
                                <input type="hidden" name="search" value="{{ request()->search ?? '' }}">
                            @endif

                            <button type="submit" name="sort" value="number-asc" class="dropdown-item d-inline-flex align-items-center gap-2">
                                <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#arrow-up"></use></svg>Nummer
                            </button>
                            <button type="submit" name="sort" value="number-desc" class="dropdown-item d-inline-flex align-items-center gap-2">
                                <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#arrow-down"></use></svg>Nummer
                            </button>
                            <button type="submit" name="sort" value="services_provided_on-asc" class="dropdown-item d-inline-flex align-items-center gap-2">
                                <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#arrow-up"></use></svg>Datum
                            </button>
                            <button type="submit" name="sort" value="services_provided_on-desc" class="dropdown-item d-inline-flex align-items-center gap-2">
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
        @endunless

        @if($additionsReports->isEmpty())
            <div class="q-empty-state">
                <svg class="q-empty-icon"><use href="{{ asset('svg/bootstrap-icons.svg') }}#tools"></use></svg>
                @if(Request::get('search'))
                    <p>Keine Regieberichte für diese Suche gefunden.</p>
                @else
                    <p>Es sind noch keine Regieberichte vorhanden.</p>
                    @can('create', \App\Models\AdditionsReport::class)
                        <a class="btn q-btn d-inline-flex align-items-center gap-2" href="{{ route('additions-reports.create') }}">
                            <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#plus"></use></svg>
                            Regiebericht anlegen
                        </a>
                    @endcan
                @endif
            </div>
        @else
            <div class="q-card q-list">
                @foreach ($additionsReports as $additionsReport)
                    @include('additions_report.overview_card_content', ['additionsReport' => $additionsReport, 'actionRedirect' => 'index'])
                @endforeach
            </div>

            <div class="mt-3">
                {{ $additionsReports->links() }}
            </div>
        @endif
    </div>
@endsection
