@extends('project.show')

@section('tab')
    @unless ($project->memos->isEmpty() && !Request::get('search'))
        @can('create', \App\Models\Memo::class)
            <a class="btn btn-outline-secondary d-inline-flex align-items-center" href="{{ route('memos.create', ['project' => $project->id]) }}">
                <svg class="icon icon-16 mr-2">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#plus"></use>
                </svg>
                Aktenvermerk anlegen
            </a>
        @endcan
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
                        <input type="text" class="form-control" id="search" name="search" value="{{ Request::get('search') ?? '' }}" placeholder="Aktenvermerke suchen" autocomplete="off" />
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary d-flex align-items-center justify-content-center" type="submit">
                                <svg class="icon icon-16">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#search"></use>
                                </svg>
                            </button>
                            @if (Request::get('search'))
                                <a class="btn btn-outline-secondary d-flex align-items-center justify-content-center" @if(Request::get('sort')) href="{{ Request::url() . '?tab=' . Request::get('tab') . '&sort=' . Request::get('sort') }}" @else href="{{ Request::url() . '?tab=' . Request::get('tab') }}" @endif>
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
                                   @if(Request::get('sort')) href="{{ Request::url() . '?tab=' . Request::get('tab') . '&search=von:' . Auth::user()->username . '&sort=' . Request::get('sort') }}"
                                   @else href="{{ Request::url() . '?tab=' . Request::get('tab') . '&search=von:' . Auth::user()->username }}"
                                   @endif>
                                   Meine Aktenvermerke
                                </a>
                                <a class="dropdown-item"
                                   @if(Request::get('sort')) href="{{ Request::url() . '?tab=' . Request::get('tab') . '&search=von:' . Auth::user()->username . ' ist:entwurf&sort=' . Request::get('sort') }}"
                                   @else href="{{ Request::url() . '?tab=' . Request::get('tab') . '&search=von:' . Auth::user()->username . ' ist:entwurf' }}"
                                    @endif>
                                    Meine Entwürfe
                                </a>
                                <a class="dropdown-item"
                                   @if(Request::get('sort')) href="{{ Request::url() . '?tab=' . Request::get('tab') . '&search=bm:' . Auth::user()->username . '&sort=' . Request::get('sort') }}"
                                   @else href="{{ Request::url() . '?tab=' . Request::get('tab') . '&search=bm:' . Auth::user()->username }}"
                                   @endif>
                                   Beteiligte Aktenvermerke
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
                        <form action="{{ route('projects.show', $project) }}" method="get">
                            @if(request()->tab)
                                <input type="hidden" id="tab" name="tab" value="{{ request()->tab }}">
                            @endif
                            @if(request()->search)
                                <input type="hidden" id="search" name="search" value="{{ request()->search }}">
                            @endif

                            <button type="submit" name="sort" value="number-asc" class="dropdown-item btn-block  d-inline-flex align-items-center">
                                <svg class="icon icon-16 mr-2">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#arrow-up"></use>
                                </svg>
                                Nummer
                            </button>
                            <button type="submit" name="sort" value="number-desc" class="dropdown-item btn-block  d-inline-flex align-items-center">
                                <svg class="icon icon-16 mr-2">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#arrow-down"></use>
                                </svg>
                                Nummer
                            </button>

                            <button type="submit" name="sort" value="meeting_held_on-asc" class="dropdown-item btn-block  d-inline-flex align-items-center">
                                <svg class="icon icon-16 mr-2">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#arrow-up"></use>
                                </svg>
                                Datum
                            </button>
                            <button type="submit" name="sort" value="meeting_held_on-desc" class="dropdown-item btn-block  d-inline-flex align-items-center">
                                <svg class="icon icon-16 mr-2">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#arrow-down"></use>
                                </svg>
                                Datum
                            </button>

                            <button type="submit" name="sort" value="title-asc" class="dropdown-item btn-block  d-inline-flex align-items-center">
                                <svg class="icon icon-16 mr-2">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#arrow-up"></use>
                                </svg>
                                Titel
                            </button>
                            <button type="submit" name="sort" value="title-desc" class="dropdown-item btn-block  d-inline-flex align-items-center">
                                <svg class="icon icon-16 mr-2">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#arrow-down"></use>
                                </svg>
                                Titel
                            </button>
                                <button type="submit" name="sort" value="draft-asc" class="dropdown-item btn-block  d-inline-flex align-items-center">
                                    <svg class="icon icon-16 mr-2">
                                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#arrow-up"></use>
                                    </svg>
                                    Entwurf
                                </button>
                                <button type="submit" name="sort" value="draft-desc" class="dropdown-item btn-block  d-inline-flex align-items-center">
                                    <svg class="icon icon-16 mr-2">
                                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#arrow-down"></use>
                                    </svg>
                                    Entwurf
                                </button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    @endunless

    <div class="mt-3">
        @forelse ($memos as $memo)
            @component('memo.overview_card', [ 'memo' => $memo, 'secondaryInformation' => 'withoutProject', 'actionRedirect' => 'project' ])
            @endcomponent

            @if(!$loop->last)
                <hr class="m-0 mx-1" />
            @endif
        @empty
            <div class="text-center">
                <img class="empty-state" src="{{ asset('svg/no-data.svg') }}" alt="no data" />
                @if(Request::get('search'))
                    <p class="lead text-muted">Es wurden keine Aktenvermerke passend zur Suche gefunden.</p>
                @else
                    <p class="lead text-muted">Dem Projekt {{ $project->name }} sind keine Aktenvermerke zugeordnet.</p>
                    @can('create', \App\Models\Memo::class)
                        <p class="lead">Lege einen neuen Aktenvermerk an.</p>
                        <a class="btn btn-primary btn-lg d-inline-flex align-items-center" href="{{ route('memos.create', ['project' => $project->id]) }}">
                            <svg class="icon icon-20 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#plus"></use>
                            </svg>
                            Aktenvermerk anlegen
                        </a>
                    @endcan
                @endif
            </div>
        @endforelse
    </div>

    <div class="mt-2">
        {{ $memos->links() }}
    </div>

    @if($memos->count() > 0)
        <p class="mt-3 small">
            Der linke farbliche Rand zeigt den Status des jeweiligen Aktenvermerkes:
            <span class="badge badge-yellow-100 text-yellow-800">Entwurf</span>
        </p>
    @endif
@endsection
