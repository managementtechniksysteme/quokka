@extends('layouts.app')

@section('content')
    <div class="q-container">

        <div class="q-page-head">
            {{-- Desktop: icon + title + count, as before. --}}
            <div class="d-none d-md-flex align-items-center gap-3">
                <span class="q-head-icon">
                    <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#clipboard"></use></svg>
                </span>
                <div>
                    <h1 class="q-title">Projekte</h1>
                    @unless($projects->isEmpty())
                        <div class="q-subtitle">{{ trans_choice('messages.entries', $projects->total()) }}</div>
                    @endunless
                </div>
            </div>

            <div class="d-none d-md-flex gap-2">
                @can('create', \App\Models\Project::class)
                    <a class="btn btn-primary text-white d-inline-flex align-items-center gap-2" href="{{ route('projects.create') }}">
                        <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#plus"></use></svg>
                        Projekt anlegen
                    </a>
                @endcan
                @can('downloadList', \App\Models\Project::class)
                    <a class="btn q-btn d-inline-flex align-items-center gap-2" href="{{ route('projects.download-list') }}" target="_blank">
                        <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#printer"></use></svg>
                        PDF Liste
                    </a>
                @endcan
            </div>

            {{-- Mobile: count inline with the actions, create label
                 shortened to just the entity name, PDF list export dropped
                 (desktop-only convenience action). --}}
            <div class="d-flex d-md-none align-items-center gap-2">
                @unless($projects->isEmpty())
                    <div class="q-subtitle mb-0">{{ trans_choice('messages.entries', $projects->total()) }}</div>
                @endunless
                @can('create', \App\Models\Project::class)
                    <a class="btn btn-primary text-white d-inline-flex align-items-center gap-2 ms-auto" style="flex: none;" href="{{ route('projects.create') }}">
                        <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#plus"></use></svg>
                        Projekt
                    </a>
                @endcan
            </div>
        </div>

        @unless ($projects->isEmpty() && !Request::get('search'))
            {{-- Desktop: search field + sort dropdown — unchanged. --}}
            <div class="d-none d-md-flex flex-wrap align-items-center gap-3 mb-3">
                <form class="flex-grow-1" action="{{ route('projects.index') }}" method="get">
                    @if(request()->sort)
                        <input type="hidden" name="sort" value="{{ request()->sort }}">
                    @endif
                    <div class="input-group">
                        <filter-search-input name="search" input_class="form-control" :fields="{{ json_encode($filterFields) }}" suggestions_url="{{ route('filter-suggestions.search') }}" model="project" initial_value="{{ Request::get('search') ?? '' }}" placeholder="Projekte suchen"></filter-search-input>
                        <button class="btn q-btn d-flex align-items-center" type="submit">
                            <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#search"></use></svg>
                        </button>
                        @if (Request::get('search'))
                            <a class="btn q-btn d-flex align-items-center" @if(Request::get('sort')) href="{{ Request::url() . '?search=&sort=' . Request::get('sort') }}" @else href="{{ Request::url() . '?search=' }}" @endif>
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
                        <form action="{{ route('projects.index') }}" method="get">
                            @if(request()->search)
                                <input type="hidden" name="search" value="{{ request()->search }}">
                            @endif

                            <button type="submit" name="sort" value="name-asc" class="dropdown-item d-inline-flex align-items-center gap-2">
                                <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#arrow-up"></use></svg>Name
                            </button>
                            <button type="submit" name="sort" value="name-desc" class="dropdown-item d-inline-flex align-items-center gap-2">
                                <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#arrow-down"></use></svg>Name
                            </button>
                            <button type="submit" name="sort" value="wage-costs-asc" class="dropdown-item d-inline-flex align-items-center gap-2">
                                <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#arrow-up"></use></svg>Lohnkosten
                            </button>
                            <button type="submit" name="sort" value="wage-costs-desc" class="dropdown-item d-inline-flex align-items-center gap-2">
                                <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#arrow-down"></use></svg>Lohnkosten
                            </button>
                            <button type="submit" name="sort" value="material-costs-asc" class="dropdown-item d-inline-flex align-items-center gap-2">
                                <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#arrow-up"></use></svg>Materialkosten
                            </button>
                            <button type="submit" name="sort" value="material-costs-desc" class="dropdown-item d-inline-flex align-items-center gap-2">
                                <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#arrow-down"></use></svg>Materialkosten
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Mobile: leading search icon inline in the field, no separate
                 submit button, sort as an icon-only button opening a bottom
                 sheet. No quick-filter — desktop has none either. --}}
            <div class="d-flex d-md-none align-items-center gap-2 mb-3">
                <form class="flex-grow-1" action="{{ route('projects.index') }}" method="get">
                    @if(request()->sort)
                        <input type="hidden" name="sort" value="{{ request()->sort }}">
                    @endif
                    <div class="position-relative flex-grow-1">
                        <div class="input-group">
                            <filter-search-input name="search" input_class="form-control ps-5" :fields="{{ json_encode($filterFields) }}" suggestions_url="{{ route('filter-suggestions.search') }}" model="project" initial_value="{{ Request::get('search') ?? '' }}" placeholder="Projekte suchen"></filter-search-input>
                            @if (Request::get('search'))
                                <a class="btn q-btn q-btn-icon d-flex align-items-center justify-content-center" @if(Request::get('sort')) href="{{ Request::url() . '?search=&sort=' . Request::get('sort') }}" @else href="{{ Request::url() . '?search=' }}" @endif>
                                    <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#x-circle"></use></svg>
                                </a>
                            @endif
                        </div>
                        <svg class="icon-bs icon-16 text-muted position-absolute top-50 start-0 translate-middle-y ms-3 pe-none q-search-icon">
                            <use href="{{ asset('svg/bootstrap-icons.svg') }}#search"></use>
                        </svg>
                    </div>
                </form>

                <button class="btn q-btn q-btn-icon" type="button" data-bs-toggle="offcanvas" data-bs-target="#projectSortSheet" aria-controls="projectSortSheet" aria-label="Sortierung">
                    <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#sort-down"></use></svg>
                </button>
            </div>

            @php
                $projectSortOptions = [
                    'name-asc' => ['Name', 'arrow-up'],
                    'name-desc' => ['Name', 'arrow-down'],
                    'wage-costs-asc' => ['Lohnkosten', 'arrow-up'],
                    'wage-costs-desc' => ['Lohnkosten', 'arrow-down'],
                    'material-costs-asc' => ['Materialkosten', 'arrow-up'],
                    'material-costs-desc' => ['Materialkosten', 'arrow-down'],
                ];
            @endphp
            <div class="offcanvas offcanvas-bottom q-sheet" tabindex="-1" id="projectSortSheet" aria-label="Sortierung">
                <div class="q-sheet__handle" aria-hidden="true"><span class="q-sheet__handle-bar"></span></div>
                <div class="offcanvas-body">
                    <div class="q-sheet__label">Sortierung</div>
                    <form action="{{ route('projects.index') }}" method="get">
                        @if(request()->search)
                            <input type="hidden" name="search" value="{{ request()->search }}">
                        @endif
                        @foreach($projectSortOptions as $sortValue => $sortMeta)
                            <button type="submit" name="sort" value="{{ $sortValue }}" class="q-row">
                                <span class="q-avatar q-avatar--muted">
                                    <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#{{ $sortMeta[1] }}"></use></svg>
                                </span>
                                <span class="q-row__title">{{ $sortMeta[0] }}</span>
                                @if(request('sort', 'name-asc') === $sortValue)
                                    <svg class="icon-bs icon-18 q-row__check"><use href="{{ asset('svg/bootstrap-icons.svg') }}#check"></use></svg>
                                @endif
                            </button>
                        @endforeach
                    </form>
                </div>
            </div>
        @endunless

        @if($projects->isEmpty())
            <div class="q-empty-state">
                <svg class="q-empty-icon"><use href="{{ asset('svg/bootstrap-icons.svg') }}#clipboard"></use></svg>
                @if(Request::get('search'))
                    <p>Keine Projekte für diese Suche gefunden.</p>
                @else
                    <p>Es sind noch keine Projekte vorhanden.</p>
                    @can('create', \App\Models\Project::class)
                        <a class="btn q-btn d-inline-flex align-items-center gap-2" href="{{ route('projects.create') }}">
                            <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#plus"></use></svg>
                            Projekt anlegen
                        </a>
                    @endcan
                @endif
            </div>
        @else
            <div class="q-card q-list">
                @foreach ($projects as $project)
                    @include('project.overview_card_content', ['project' => $project, 'actionRedirect' => 'index'])
                @endforeach
            </div>

            <div class="mt-3">
                {{ $projects->links() }}
            </div>

            @include('project.cost_legend')
        @endif
    </div>
@endsection
