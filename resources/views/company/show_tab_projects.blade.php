@extends('company.show')

@section('tab')
    @unless ($company->projects->isEmpty() && !Request::get('search'))
        <a class="btn btn-outline-secondary d-inline-flex align-items-center" href="{{ route('projects.create', ['company' => $company->id]) }}">
            <svg class="feather feather-16 mr-2">
                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#plus"></use>
            </svg>
            Projekt anlegen
        </a>

        <div class="row mt-4">

            <div class="col col-lg-6">

                <form action="{{ route('companies.show', $company) }}" method="get">
                    @if(request()->tab)
                        <input type="hidden" id="tab" name="tab" value="{{ request()->tab }}">
                    @endif
                    @if(request()->sort)
                        <input type="hidden" id="sort" name="sort" value="{{ request()->sort }}">
                    @endif

                    <div class="input-group">
                        <input type="text" class="form-control" id="search" name="search" value="{{ Request::get('search') ?? '' }}" placeholder="Projekte suchen" autocomplete="off" />
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary d-flex align-items-center justify-content-center" type="submit">
                                <svg class="feather feather-16">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#search"></use>
                                </svg>
                            </button>
                            @if (Request::get('search'))
                                <a class="btn btn-outline-secondary d-flex align-items-center justify-content-center" @if(Request::get('sort')) href="{{ Request::url() . '?tab=' . Request::get('tab') . '&sort=' . Request::get('sort') }}" @else href="{{ Request::url() . '?tab=' . Request::get('tab') }}" @endif>
                                    <svg class="feather feather-16">
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
                        <svg class="feather feather-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#arrow-up"></use>
                        </svg>
                        Sortierung
                    </button>
                    <div class="dropdown-menu dropdown-menu-right w-100">
                        <form action="{{ route('companies.show', $company) }}" method="get">
                            @if(request()->tab)
                                <input type="hidden" id="tab" name="tab" value="{{ request()->tab }}">
                            @endif
                            @if(request()->search)
                                <input type="hidden" id="search" name="search" value="{{ request()->search }}">
                            @endif

                            <button type="submit" name="sort" value="name-asc" class="dropdown-item btn-block d-inline-flex align-items-center">
                                <svg class="feather feather-16 mr-2">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#arrow-up"></use>
                                </svg>
                                Name
                            </button>
                            <button type="submit" name="sort" value="name-desc" class="dropdown-item btn-block d-inline-flex align-items-center">
                                <svg class="feather feather-16 mr-2">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#arrow-down"></use>
                                </svg>
                                Name
                            </button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    @endunless

    <div class="mt-3">
        @forelse ($projects as $project)
            @component('project.overview_card', [ 'project' => $project, 'secondaryInformation' => 'dates' ])
            @endcomponent

            @if(!$loop->last)
                <hr class="m-0 mx-1" />
            @endif
        @empty
            <div class="text-center">
                <img class="empty-state" src="{{ asset('svg/no-data.svg') }}" alt="no data" />
                @if(Request::get('search'))
                    <p class="lead text-muted">Es wurden keine Projekte passend zur Suche gefunden.</p>
                @else
                    <p class="lead text-muted">Der Firma {{ $company->full_name }} sind keine Projekte zugeordnet.</p>
                    <p class="lead">Lege ein neues Projekt an.</p>
                    <a class="btn btn-primary btn-lg d-inline-flex align-items-center" href="{{ route('projects.create', ['company' => $company->id]) }}">
                        <svg class="feather feather-20 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#plus"></use>
                        </svg>
                        Projekt anlegen
                    </a>
                @endif
            </div>
        @endforelse
    </div>

    <div class="mt-2">
        {{ $projects->links() }}
    </div>
@endsection
