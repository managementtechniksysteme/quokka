@extends('project.show')

@section('tab')
    @unless ($project->memos->isEmpty())
        <a class="btn btn-outline-secondary d-inline-flex align-items-center" href="{{ route('memos.create', ['project' => $project->id]) }}">
            <svg class="feather feather-16 mr-2">
                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#plus"></use>
            </svg>
            Aktenvermerk anlegen
        </a>

        <div class="row mt-4">

            <div class="col col-lg-6">

                <form action="{{ route('projects.show', $project) }}" method="get">
                    @if(request()->tab)
                        <input type="hidden" id="tab" name="tab" value="{{ request()->tab }}">
                    @endif
                    @if(request()->sort)
                        <input type="hidden" id="sort" name="sort" value="{{ request()->sort }}">
                    @endif

                    <div class="input-group">
                        <input type="text" class="form-control" id="search" name="search" placeholder="Aktenvermerke suchen">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary d-flex align-items-center justify-content-center" type="submit">
                                <svg class="feather feather-16">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#search"></use>
                                </svg>
                            </button>
                        </div>
                    </div>

                </form>

            </div>

            <div class="col-auto ml-auto">
                <div class="dropdown">
                    <button class="btn btn-outline-secondary btn-block dropdown-toggle d-flex align-items-center justify-content-center" type="button" id="sortOrderDropdown" data-toggle="dropdown">
                        <svg class="feather feather-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#arrow-up"></use>
                        </svg>
                        Sortierung
                    </button>
                    <div class="dropdown-menu dropdown-menu-right w-100">
                        <form action="{{ route('projects.show', $project) }}" method="get">
                            @if(request()->tab)
                                <input type="hidden" id="tab" name="tab" value="{{ request()->tab }}">
                            @endif
                            @if(request()->search)
                                <input type="hidden" id="search" name="search" value="{{ request()->search }}">
                            @endif

                            <button type="submit" name="sort" value="number-asc" class="dropdown-item btn-block  d-inline-flex align-items-center">
                                <svg class="feather feather-16 mr-2">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#arrow-up"></use>
                                </svg>
                                Nummer
                            </button>
                            <button type="submit" name="sort" value="number-desc" class="dropdown-item btn-block  d-inline-flex align-items-center">
                                <svg class="feather feather-16 mr-2">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#arrow-down"></use>
                                </svg>
                                Nummer
                            </button>

                            <button type="submit" name="sort" value="meeting_held_on-asc" class="dropdown-item btn-block  d-inline-flex align-items-center">
                                <svg class="feather feather-16 mr-2">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#arrow-up"></use>
                                </svg>
                                Datum
                            </button>
                            <button type="submit" name="sort" value="meeting_held_on-desc" class="dropdown-item btn-block  d-inline-flex align-items-center">
                                <svg class="feather feather-16 mr-2">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#arrow-down"></use>
                                </svg>
                                Datum
                            </button>

                            <button type="submit" name="sort" value="title-asc" class="dropdown-item btn-block  d-inline-flex align-items-center">
                                <svg class="feather feather-16 mr-2">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#arrow-up"></use>
                                </svg>
                                Titel
                            </button>
                            <button type="submit" name="sort" value="title-desc" class="dropdown-item btn-block  d-inline-flex align-items-center">
                                <svg class="feather feather-16 mr-2">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#arrow-down"></use>
                                </svg>
                                Titel
                            </button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    @endunless

    <div class="mt-3">
        @forelse ($project->memos as $memo)
            @component('memo.overview_card', [ 'memo' => $memo, 'secondaryInformation' => 'withoutProject' ])
            @endcomponent
        @empty
            <div class="text-center">
                <img class="empty-state" src="{{ asset('svg/no-data.svg') }}" alt="no data" />
                <p class="lead text-muted">Dem Projekt {{ $project->name }} sind keine Aktenvermerke zugeordnet.</p>
                <p class="lead">Lege einen neuen Aktenvermerk an.</p>
                <a class="btn btn-primary btn-lg d-inline-flex align-items-center" href="{{ route('memos.create', ['project' => $project->id]) }}">
                    <svg class="feather feather-20 mr-2">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#plus"></use>
                    </svg>
                    Aktenvermerk anlegen
                </a>
            </div>
        @endforelse
    </div>
@endsection
