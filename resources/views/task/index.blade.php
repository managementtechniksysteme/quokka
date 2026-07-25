@extends('layouts.app')

@section('content')
    <div class="q-container">

        <div class="q-page-head">
            {{-- Desktop: icon + title + count, as before. --}}
            <div class="d-none d-md-flex align-items-center gap-3">
                <span class="q-head-icon">
                    <svg class="icon-bs icon-20"><use href="{{ asset('svg/bootstrap-icons.svg') }}#check2-square"></use></svg>
                </span>
                <div>
                    <h1 class="q-title">Aufgaben</h1>
                    @unless($tasks->isEmpty())
                        <div class="q-subtitle">{{ trans_choice('messages.entries', $tasks->total()) }}</div>
                    @endunless
                </div>
            </div>

            <div class="d-none d-md-flex gap-2">
                @can('create', \App\Models\Task::class)
                    <a class="btn btn-primary text-white d-inline-flex align-items-center gap-2" href="{{ route('tasks.create') }}">
                        <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#plus"></use></svg>
                        Aufgabe anlegen
                    </a>
                @endcan
                @can('downloadList', \App\Models\Task::class)
                    <a class="btn q-btn d-inline-flex align-items-center gap-2" href="{{ route('tasks.download-list') }}" target="_blank">
                        <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#printer"></use></svg>
                        PDF Liste
                    </a>
                @endcan
            </div>

            {{-- Mobile: count inline with the actions. Create first/primary
                 then PDF Liste icon-only after it (2026-07-21, user: match
                 desktop's own left-to-right order — was reversed here) —
                 create's label also shortened to just the entity name
                 ("+ Aufgabe" not "Aufgabe anlegen"), the icon already reads
                 as "create," a bit airier on the tight mobile row. Button
                 group uses ms-auto, not justify-content-between on the
                 parent — the count is conditional (@unless empty), and
                 space-between with only ONE remaining flex child collapses
                 it to the start instead of the end (user-caught: buttons
                 shifted left on an empty list). ms-auto pushes the group
                 right regardless of whether the count sibling exists. --}}
            <div class="d-flex d-md-none align-items-center gap-2">
                @unless($tasks->isEmpty())
                    <div class="q-subtitle mb-0">{{ trans_choice('messages.entries', $tasks->total()) }}</div>
                @endunless
                <div class="d-flex align-items-center gap-2 ms-auto" style="flex: none;">
                    @can('create', \App\Models\Task::class)
                        <a class="btn btn-primary text-white d-inline-flex align-items-center gap-2" style="flex: none;" href="{{ route('tasks.create') }}">
                            <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#plus"></use></svg>
                            Aufgabe
                        </a>
                    @endcan
                    @can('downloadList', \App\Models\Task::class)
                        <a class="btn q-btn q-btn-icon d-flex align-items-center justify-content-center" href="{{ route('tasks.download-list') }}" target="_blank" aria-label="PDF Liste">
                            <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#printer"></use></svg>
                        </a>
                    @endcan
                </div>
            </div>
        </div>

        @unless ($tasks->isEmpty() && !Request::get('search'))
            {{-- Desktop: search field + quick-filter split-dropdown + sort dropdown — unchanged. --}}
            <div class="d-none d-md-flex flex-wrap align-items-center gap-3 mb-3">
                <form class="flex-grow-1" action="{{ route('tasks.index') }}" method="get">
                    @if(request()->sort)
                        <input type="hidden" name="sort" value="{{ request()->sort }}">
                    @endif
                    <div class="input-group">
                        <input type="text" class="form-control" name="search" value="{{ Request::get('search') ?? '' }}" placeholder="Aufgaben suchen" autocomplete="off" />
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
                               @if(Request::get('sort')) href="{{ Request::url() . '?search=v:' . Auth::user()->username . (Auth::user()->settings->show_finished_items ? '' : ' !ist:erledigt') . '&sort=' . Request::get('sort') }}"
                               @else href="{{ Request::url() . '?search=v:' . Auth::user()->username . (Auth::user()->settings->show_finished_items ? '' : ' !ist:erledigt') }}" @endif>
                                Meine Aufgaben
                            </a>
                            <a class="dropdown-item"
                               @if(Request::get('sort')) href="{{ Request::url() . '?search=v:' . Auth::user()->username . ' ist:bald_fällig' . '&sort=' . Request::get('sort') }}"
                               @else href="{{ Request::url() . '?search=v:' . Auth::user()->username . ' ist:bald_fällig' }}" @endif>
                                Meine bald fälligen Aufgaben
                            </a>
                            <a class="dropdown-item"
                               @if(Request::get('sort')) href="{{ Request::url() . '?search=v:' . Auth::user()->username . ' ist:überfällig' . '&sort=' . Request::get('sort') }}"
                               @else href="{{ Request::url() . '?search=v:' . Auth::user()->username . ' ist:überfällig' }}" @endif>
                                Meine überfälligen Aufgaben
                            </a>
                            <a class="dropdown-item"
                               @if(Request::get('sort')) href="{{ Request::url() . '?search=b:' . Auth::user()->username . (Auth::user()->settings->show_finished_items ? '' : ' !ist:erledigt') . '&sort=' . Request::get('sort') }}"
                               @else href="{{ Request::url() . '?search=b:' . Auth::user()->username . (Auth::user()->settings->show_finished_items ? '' : ' !ist:erledigt') }}" @endif>
                                Beteiligte Aufgaben
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
                        <form action="{{ route('tasks.index') }}" method="get">
                            @if(request()->has('search'))
                                <input type="hidden" name="search" value="{{ request()->search ?? '' }}">
                            @endif

                            <button type="submit" name="sort" value="due_on-asc" class="dropdown-item d-inline-flex align-items-center gap-2">
                                <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#arrow-up"></use></svg>fällig am
                            </button>
                            <button type="submit" name="sort" value="due_on-desc" class="dropdown-item d-inline-flex align-items-center gap-2">
                                <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#arrow-down"></use></svg>fällig am
                            </button>
                            <button type="submit" name="sort" value="name-asc" class="dropdown-item d-inline-flex align-items-center gap-2">
                                <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#arrow-up"></use></svg>Name
                            </button>
                            <button type="submit" name="sort" value="name-desc" class="dropdown-item d-inline-flex align-items-center gap-2">
                                <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#arrow-down"></use></svg>Name
                            </button>
                            <button type="submit" name="sort" value="status-asc" class="dropdown-item d-inline-flex align-items-center gap-2">
                                <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#arrow-up"></use></svg>Status
                            </button>
                            <button type="submit" name="sort" value="status-desc" class="dropdown-item d-inline-flex align-items-center gap-2">
                                <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#arrow-down"></use></svg>Status
                            </button>
                            <button type="submit" name="sort" value="priority-asc" class="dropdown-item d-inline-flex align-items-center gap-2">
                                <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#arrow-up"></use></svg>Priorität
                            </button>
                            <button type="submit" name="sort" value="priority-desc" class="dropdown-item d-inline-flex align-items-center gap-2">
                                <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#arrow-down"></use></svg>Priorität
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Mobile: leading search icon inline in the field, no separate
                 submit button. Filter joins the input-group as a fused
                 trailing segment (2026-07-21, user: "same as desktop" — its
                 split-dropdown toggle is likewise fused to the field there)
                 instead of floating as its own separate button; sort stays
                 a standalone icon button outside the group since it isn't
                 part of the desktop field either. --}}
            <div class="d-flex d-md-none align-items-center gap-2 mb-3">
                <form class="flex-grow-1" action="{{ route('tasks.index') }}" method="get">
                    @if(request()->sort)
                        <input type="hidden" name="sort" value="{{ request()->sort }}">
                    @endif
                    <div class="position-relative flex-grow-1">
                        <div class="input-group">
                            <input type="text" class="form-control ps-5" name="search" value="{{ Request::get('search') ?? '' }}" placeholder="Aufgaben suchen" autocomplete="off" />
                            @if (Request::get('search'))
                                <a class="btn q-btn q-btn-icon d-flex align-items-center justify-content-center" @if(Request::get('sort')) href="{{ Request::url() . '?search=&sort=' . Request::get('sort') }}" @else href="{{ Request::url() . '?search=' }}" @endif>
                                    <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#x-circle"></use></svg>
                                </a>
                            @endif
                            <button type="button" class="btn q-btn q-btn-icon d-flex align-items-center justify-content-center" data-bs-toggle="offcanvas" data-bs-target="#taskQuickFilterSheet" aria-controls="taskQuickFilterSheet" aria-label="Schnellfilter">
                                <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#funnel"></use></svg>
                            </button>
                        </div>
                        <svg class="icon-bs icon-16 text-muted position-absolute top-50 start-0 translate-middle-y ms-3 pe-none q-search-icon">
                            <use href="{{ asset('svg/bootstrap-icons.svg') }}#search"></use>
                        </svg>
                    </div>
                </form>

                <button class="btn q-btn q-btn-icon" type="button" data-bs-toggle="offcanvas" data-bs-target="#taskSortSheet" aria-controls="taskSortSheet" aria-label="Sortierung">
                    <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#sort-down"></use></svg>
                </button>
            </div>

            @php
                $taskFilterUsername = Auth::user()->username;
                $taskFilterShowFinished = Auth::user()->settings->show_finished_items;
                $taskQuickFilters = [
                    'Meine Aufgaben' => 'v:' . $taskFilterUsername . ($taskFilterShowFinished ? '' : ' !ist:erledigt'),
                    'Meine bald fälligen Aufgaben' => 'v:' . $taskFilterUsername . ' ist:bald_fällig',
                    'Meine überfälligen Aufgaben' => 'v:' . $taskFilterUsername . ' ist:überfällig',
                    'Beteiligte Aufgaben' => 'b:' . $taskFilterUsername . ($taskFilterShowFinished ? '' : ' !ist:erledigt'),
                ];
            @endphp
            <div class="offcanvas offcanvas-bottom q-sheet" tabindex="-1" id="taskQuickFilterSheet" aria-label="Schnellfilter">
                <div class="q-sheet__handle" aria-hidden="true"><span class="q-sheet__handle-bar"></span></div>
                <div class="offcanvas-body">
                    <div class="q-sheet__label">Schnellfilter</div>
                    @foreach($taskQuickFilters as $quickFilterLabel => $quickFilterExpr)
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
                $taskSortOptions = [
                    'due_on-asc' => ['fällig am', 'arrow-up'],
                    'due_on-desc' => ['fällig am', 'arrow-down'],
                    'name-asc' => ['Name', 'arrow-up'],
                    'name-desc' => ['Name', 'arrow-down'],
                    'status-asc' => ['Status', 'arrow-up'],
                    'status-desc' => ['Status', 'arrow-down'],
                    'priority-asc' => ['Priorität', 'arrow-up'],
                    'priority-desc' => ['Priorität', 'arrow-down'],
                ];
            @endphp
            <div class="offcanvas offcanvas-bottom q-sheet" tabindex="-1" id="taskSortSheet" aria-label="Sortierung">
                <div class="q-sheet__handle" aria-hidden="true"><span class="q-sheet__handle-bar"></span></div>
                <div class="offcanvas-body">
                    <div class="q-sheet__label">Sortierung</div>
                    <form action="{{ route('tasks.index') }}" method="get">
                        @if(request()->has('search'))
                            <input type="hidden" name="search" value="{{ request()->search ?? '' }}">
                        @endif
                        @foreach($taskSortOptions as $sortValue => $sortMeta)
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

        @if($tasks->isEmpty())
            <div class="q-empty-state">
                <svg class="q-empty-icon"><use href="{{ asset('svg/bootstrap-icons.svg') }}#check2-square"></use></svg>
                @if(Request::get('search'))
                    <p>Keine Aufgaben für diese Suche gefunden.</p>
                @else
                    <p>Es sind noch keine Aufgaben vorhanden.</p>
                    @can('create', \App\Models\Task::class)
                        <a class="btn q-btn d-inline-flex align-items-center gap-2" href="{{ route('tasks.create') }}">
                            <svg class="icon-bs icon-16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#plus"></use></svg>
                            Aufgabe anlegen
                        </a>
                    @endcan
                @endif
            </div>
        @else
            <div class="q-card q-list">
                @foreach ($tasks as $task)
                    @include('task.overview_card_content', ['task' => $task, 'actionRedirect' => 'index'])
                @endforeach
            </div>

            <div class="mt-3">
                {{ $tasks->links() }}
            </div>
        @endif
    </div>
@endsection
