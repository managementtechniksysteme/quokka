@extends('project.show')

@section('tab')
    @unless ($project->deliveryNotes->isEmpty() && !Request::get('search'))
        @can('create', \App\Models\DeliveryNote::class)
            <a class="btn btn-outline-secondary d-inline-flex align-items-center" href="{{ route('delivery-notes.create', ['project' => $project->id]) }}">
                <svg class="icon icon-16 mr-2">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#plus"></use>
                </svg>
                Lieferschein anlegen
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
                        <input type="text" class="form-control" id="search" name="search" value="{{ Request::get('search') ?? '' }}" placeholder="Lieferscheine suchen" autocomplete="off" />
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary d-flex align-items-center justify-content-center" type="submit">
                                <svg class="icon icon-16">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#search"></use>
                                </svg>
                            </button>
                            @if (Request::get('search'))
                                <a class="btn btn-outline-secondary d-flex align-items-center justify-content-center" @if(Request::get('sort')) href="{{ Request::url() . '?tab=' . Request::get('tab') . '&search=&sort=' . Request::get('sort') }}" @else href="{{ Request::url() . '?tab=' . Request::get('tab') . '&search=' }}" @endif>
                                    <svg class="icon icon-16">
                                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#x-circle"></use>
                                    </svg>
                                </a>
                            @endif
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
                            @if(request()->has('search'))
                                <input type="hidden" id="search" name="search" value="{{ request()->search ?? '' }}">
                            @endif

                            <button type="submit" name="sort" value="written_on-asc" class="dropdown-item btn-block  d-inline-flex align-items-center">
                                <svg class="icon icon-16 mr-2">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#arrow-up"></use>
                                </svg>
                                Datum
                            </button>
                            <button type="submit" name="sort" value="written_on-desc" class="dropdown-item btn-block  d-inline-flex align-items-center">
                                <svg class="icon icon-16 mr-2">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#arrow-down"></use>
                                </svg>
                                Datum
                            </button>

                            <button type="submit" name="sort" value="title-asc" class="dropdown-item btn-block  d-inline-flex align-items-center">
                                <svg class="icon icon-16 mr-2">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#arrow-up"></use>
                                </svg>
                                Nummer (Titel)
                            </button>
                            <button type="submit" name="sort" value="title-desc" class="dropdown-item btn-block  d-inline-flex align-items-center">
                                <svg class="icon icon-16 mr-2">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#arrow-down"></use>
                                </svg>
                                Nummer (Title)
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
                        </form>
                    </div>
                </div>
            </div>

        </div>
    @endunless

    <div class="mt-3">
        @forelse ($deliveryNotes as $deliveryNote)
            @component('delivery_note.overview_card', [ 'deliveryNote' => $deliveryNote, 'secondaryInformation' => 'withoutProject', 'actionRedirect' => 'project' ])
            @endcomponent

            @if(!$loop->last)
                <hr class="m-0 mx-1" />
            @endif
        @empty
            <div class="text-center">
                <img class="empty-state" src="{{ asset('svg/no-data.svg') }}" alt="no data" />
                @if(Request::get('search'))
                    <p class="lead text-muted">Es wurden keine Lieferscheine passend zur Suche gefunden.</p>
                @else
                    <p class="lead text-muted">Dem Projekt {{ $project->name }} sind keine Lieferscheine zugeordnet.</p>
                    @can('create', \App\Models\DeliveryNote::class)
                        <p class="lead">Lege einen neuen Lieferschein an.</p>
                        <a class="btn btn-primary btn-lg d-inline-flex align-items-center" href="{{ route('delivery-notes.create', ['project' => $project->id]) }}">
                            <svg class="icon icon-20 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#plus"></use>
                            </svg>
                            Lieferschein anlegen
                        </a>
                    @endcan
                @endif
            </div>
        @endforelse
    </div>

    <div class="mt-2">
        {{ $deliveryNotes->links() }}
    </div>

    @if($deliveryNotes->count() > 0)
        <p class="mt-3 small">
            Der linke farbliche Rand zeigt den Status des jeweiligen Lieferscheins:
            <span class="badge badge-blue-100 text-blue-800">neu</span>
            <span class="badge badge-yellow-100 text-yellow-800">unterschrieben</span>
            <span class="badge badge-green-100 text-green-800">erledigt</span>
        </p>
    @endif

@endsection
