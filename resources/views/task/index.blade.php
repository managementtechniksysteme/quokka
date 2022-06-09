@extends('layouts.app')

@section('content')
    <div class="bg-gray-100 mt-0">
        <div class="container pt-4">
            <h3>
                <svg class="icon icon-baseline text-muted mr-1">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#check-square"></use>
                </svg>
                Aufgaben
                @unless($tasks->isEmpty())
                    <small class="text-muted">{{ $tasks->total() }} Einträge</small>
                @endunless
            </h3>

            <div class="scroll-x d-flex">
                @can('create', \App\Models\Task::class)
                    <a class="btn btn-outline-secondary border-0 d-inline-flex align-items-center" href="{{ route('tasks.create') }}">
                        <svg class="icon icon-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#plus"></use>
                        </svg>
                        Aufgabe anlegen
                    </a>
                @endcan
            </div>
        </div>
    </div>

    <div class="container mt-4">
        @unless ($tasks->isEmpty() && !Request::get('search'))
            <div class="row">

                <div class="col col-md-6">

                    <form action="{{ route('tasks.index') }}" method="get">
                        @if(request()->sort)
                            <input type="hidden" id="sort" name="sort" value="{{ request()->sort }}">
                        @endif

                        <div class="input-group">
                            <input type="text" class="form-control" id="search" name="search" value="{{ Request::get('search') ?? '' }}" placeholder="Aufgaben suchen" autocomplete="off" />
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary d-flex align-items-center justify-content-center" type="submit">
                                    <svg class="icon icon-16">
                                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#search"></use>
                                    </svg>
                                </button>
                                @if (Request::get('search'))
                                    <a class="btn btn-outline-secondary d-flex align-items-center justify-content-center" @if(Request::get('sort')) href="{{ Request::url() . '?search=&sort=' . Request::get('sort') }}" @else href="{{ Request::url() . '?search=' }}" @endif>
                                        <svg class="icon icon-16">
                                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#x-circle"></use>
                                        </svg>
                                    </a>
                                @endif
                                <button type="button" class="btn btn-outline-secondary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item"
                                       @if(Request::get('sort')) href="{{ Request::url() . '?search=v:' . Auth::user()->username . (Auth::user()->settings->show_finished_items ? '' : ' !ist:erledigt') . '&sort=' . Request::get('sort') }}"
                                       @else href="{{ Request::url() . '?search=v:' . Auth::user()->username . (Auth::user()->settings->show_finished_items ? '' : ' !ist:erledigt') }}"
                                       @endif>
                                       Meine Aufgaben
                                    </a>
                                    <a class="dropdown-item"
                                       @if(Request::get('sort')) href="{{ Request::url() . '?search=v:' . Auth::user()->username . ' ist:bald_fällig' . '&sort=' . Request::get('sort') }}"
                                       @else href="{{ Request::url() . '?search=v:' . Auth::user()->username . ' ist:bald_fällig' }}"
                                       @endif>
                                       Meine bald fälligen Aufgaben
                                    </a>
                                    <a class="dropdown-item"
                                       @if(Request::get('sort')) href="{{ Request::url() . '?search=v:' . Auth::user()->username . ' ist:überfällig' . '&sort=' . Request::get('sort') }}"
                                       @else href="{{ Request::url() . '?search=v:' . Auth::user()->username . ' ist:überfällig' }}"
                                       @endif>
                                       Meine überfälligen Aufgaben
                                    </a>
                                    <a class="dropdown-item"
                                       @if(Request::get('sort')) href="{{ Request::url() . '?search=b:' . Auth::user()->username . (Auth::user()->settings->show_finished_items ? '' : ' !ist:erledigt') . '&sort=' . Request::get('sort') }}"
                                       @else href="{{ Request::url() . '?search=b:' . Auth::user()->username . (Auth::user()->settings->show_finished_items ? '' : ' !ist:erledigt') }}"
                                       @endif>
                                       Beteiligte Aufgaben
                                    </a>
                                </div>
                            </div>
                        </div>

                    </form>

                </div>

                <div class="col-auto ml-auto">
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary btn-block dropdown-toggle d-flex align-items-center justify-content-center" type="button" id="sortOrderDropdown" data-toggle="dropdown">
                            <svg class="icon icon-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#arrow-up"></use>
                            </svg>
                            Sortierung
                        </button>
                        <div class="dropdown-menu dropdown-menu-right w-100">
                            <form action="{{ route('tasks.index') }}" method="get">
                                @if(request()->has('search'))
                                    <input type="hidden" id="search" name="search" value="{{ request()->search ?? '' }}">
                                @endif

                                <button type="submit" name="sort" value="due_on-asc" class="dropdown-item btn-block  d-inline-flex align-items-center">
                                    <svg class="icon icon-16 mr-2">
                                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#arrow-up"></use>
                                    </svg>
                                    fällig am
                                </button>
                                <button type="submit" name="sort" value="due_on-desc" class="dropdown-item btn-block  d-inline-flex align-items-center">
                                    <svg class="icon icon-16 mr-2">
                                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#arrow-down"></use>
                                    </svg>
                                    fällig am
                                </button>

                                <button type="submit" name="sort" value="name-asc" class="dropdown-item btn-block  d-inline-flex align-items-center">
                                    <svg class="icon icon-16 mr-2">
                                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#arrow-up"></use>
                                    </svg>
                                    Name
                                </button>
                                <button type="submit" name="sort" value="name-desc" class="dropdown-item btn-block  d-inline-flex align-items-center">
                                    <svg class="icon icon-16 mr-2">
                                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#arrow-down"></use>
                                    </svg>
                                    Name
                                </button>

                                <button type="submit" name="sort" value="status-asc" class="dropdown-item btn-block  d-inline-flex align-items-center">
                                    <svg class="icon icon-16 mr-2">
                                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#arrow-up"></use>
                                    </svg>
                                    Status
                                </button>
                                <button type="submit" name="sort" value="status-desc" class="dropdown-item btn-block  d-inline-flex align-items-center">
                                    <svg class="icon icon-16 mr-2">
                                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#arrow-down"></use>
                                    </svg>
                                    Status
                                </button>

                                <button type="submit" name="sort" value="priority-asc" class="dropdown-item btn-block  d-inline-flex align-items-center">
                                    <svg class="icon icon-16 mr-2">
                                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#arrow-up"></use>
                                    </svg>
                                    Priorität
                                </button>
                                <button type="submit" name="sort" value="priority-desc" class="dropdown-item btn-block  d-inline-flex align-items-center">
                                    <svg class="icon icon-16 mr-2">
                                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#arrow-down"></use>
                                    </svg>
                                    Priorität
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        @endunless

        <div class="mt-3">
            @forelse ($tasks as $task)
                @component('task.overview_card', [ 'task' => $task, 'actionRedirect' => 'index' ])
                @endcomponent

                @if(!$loop->last)
                    <hr class="m-0 mx-1" />
                @endif
            @empty
                <div class="text-center mt-4">
                    <img class="empty-state" src="{{ asset('svg/no-data.svg') }}" alt="no data" />
                    @if(Request::get('search'))
                        <p class="lead text-muted">Es wurden keine Aufgaben passend zur Suche gefunden.</p>
                    @else
                        <p class="lead text-muted">Es sind keine Aufgaben im System vorhanden.</p>
                        @can('create', \App\Models\Task::class)
                            <p class="lead">Lege eine neue Aufgabe an.</p>
                            <a class="btn btn-primary btn-lg d-inline-flex align-items-center" href="{{ route('tasks.create') }}">
                                <svg class="icon icon-20 mr-2">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#plus"></use>
                                </svg>
                                Aufgabe anlegen
                            </a>
                        @endcan
                    @endif
                </div>
            @endforelse
        </div>

        <div class="mt-2">
            {{ $tasks->links() }}
        </div>

        @if($tasks->count() > 0)
            <p class="mt-3 small">
                Der linke farbliche Rand zeigt den Status der jeweiligen Aufgabe:
                <span class="badge badge-blue-100 text-blue-800">neu</span>
                <span class="badge badge-yellow-100 text-yellow-800">in Bearbeitung</span>
                <span class="badge badge-green-100 text-green-800">erledigt</span>
            </p>
        @endif

    </div>
@endsection
