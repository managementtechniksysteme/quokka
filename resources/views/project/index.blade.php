@extends('layouts.app')

@section('content')
    <div class="bg-gray-100 mt-0">
        <div class="container pt-4">
            <h3>
                Projekte
                @if($projects)
                    <small class="text-muted">{{ $projects->total() }} Einträge</small>
                @endif
            </h3>

            <div class="scroll-x d-flex">
                @can('create', \App\Models\Project::class)
                    <a class="btn btn-outline-secondary border-0 d-inline-flex align-items-center" href="{{ route('projects.create') }}">
                        <svg class="icon icon-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#plus"></use>
                        </svg>
                        Projekt anlegen
                    </a>
                @endcan
            </div>
        </div>
    </div>

    <div class="container my-4">
        @unless ($projects->isEmpty() && !Request::get('search'))
           <div class="row">

                <div class="col col-md-6">

                    <form action="{{ route('projects.index') }}" method="get">
                        @if(request()->sort)
                            <input type="hidden" id="sort" name="sort" value="{{ request()->sort }}">
                        @endif

                        <div class="input-group">
                            <input type="text" class="form-control" id="search" name="search" value="{{ Request::get('search') ?? '' }}" placeholder="Projekte suchen" autocomplete="off" />
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary d-flex align-items-center justify-content-center" type="submit">
                                    <svg class="icon icon-16">
                                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#search"></use>
                                    </svg>
                                </button>
                                @if (Request::get('search'))
                                    <a class="btn btn-outline-secondary d-flex align-items-center justify-content-center" @if(Request::get('sort')) href="{{ Request::url() . '?sort=' . Request::get('sort') }}" @else href="{{ Request::url() }}" @endif>
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
                            <form action="{{ route('projects.index') }}" method="get">
                                @if(request()->search)
                                    <input type="hidden" id="search" name="search" value="{{ request()->search }}">
                                @endif

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

                                <button type="submit" name="sort" value="wage-costs-asc" class="dropdown-item btn-block  d-inline-flex align-items-center">
                                    <svg class="icon icon-16 mr-2">
                                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#arrow-up"></use>
                                    </svg>
                                    Lohnkosten
                                </button>
                                <button type="submit" name="sort" value="wage-costs-desc" class="dropdown-item btn-block  d-inline-flex align-items-center">
                                    <svg class="icon icon-16 mr-2">
                                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#arrow-down"></use>
                                    </svg>
                                    Lohnkosten
                                </button>

                                <button type="submit" name="sort" value="material-costs-asc" class="dropdown-item btn-block  d-inline-flex align-items-center">
                                    <svg class="icon icon-16 mr-2">
                                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#arrow-up"></use>
                                    </svg>
                                    Materialkosten
                                </button>
                                <button type="submit" name="sort" value="material-costs-desc" class="dropdown-item btn-block  d-inline-flex align-items-center">
                                    <svg class="icon icon-16 mr-2">
                                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#arrow-down"></use>
                                    </svg>
                                    Materialkosten
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        @endunless

        <div class="mt-3">
            @forelse ($projects as $project)
                @component('project.overview_card', [ 'project' => $project, 'actionRedirect' => 'index' ])
                @endcomponent

                @if(!$loop->last)
                    <hr class="m-0 mx-1" />
                @endif
            @empty
                <div class="text-center mt-4">
                    <img class="empty-state" src="{{ asset('svg/no-data.svg') }}" alt="no data" />
                    @if(Request::get('search'))
                        <p class="lead text-muted">Es wurden keine Projekte passend zur Suche gefunden.</p>
                    @else
                        <p class="lead text-muted">Es sind keine Projekte im System vorhanden.</p>
                        @can('create', \App\Models\Project::class)
                            <p class="lead">Lege ein neues Projekt an.</p>
                            <a class="btn btn-primary btn-lg d-inline-flex align-items-center" href="{{ route('projects.create') }}">
                                <svg class="icon icon-20 mr-2">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#plus"></use>
                                </svg>
                                Projekt anlegen
                            </a>
                        @endcan
                    @endif
                </div>
            @endforelse
        </div>

        <div class="mt-2">
            {{ $projects->links() }}
        </div>

        @if(Auth::user()->can('projects.view.estimates') && Auth::user()->settings->show_cost_estimates)
            @if($projects->count() > 0 && ($projectOverwallCostsWarningPercentage || $projectMaterialCostsWarningPercentage || $projectWageCostsWarningPercentage))
                <p class="mt-3 small">
                    Die Pfeile für die
                    <span class="font-weight-bolder"><u>G</u></span>esamt, <span class="font-weight-bold"><u>L</u></span>ohn und <span class="font-weight-bold"><u>M</u></span>aterialosten
                    zeigen folgende Information:<br />
                    <svg class="icon icon-baseline text-success">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#arrow-down"></use>
                    </svg>
                    Die aktuellen Kosten liegen unter der Warnschwelle.<br />
                    <svg class="icon icon-baseline text-warning">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#arrow-down-right"></use>
                    </svg>
                    Die aktuellen Kosten liegen zwischen der Warnschwelle und den geschätzten Kosten.<br />
                    <svg class="icon icon-baseline text-danger">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#arrow-up"></use>
                    </svg>
                    Die aktuellen Kosten liegen über den geschätzten Kosten.<br />
                    Warnschwellen:
                    @if($projectOverwallCostsWarningPercentage)Gesamtkosten: {{ $projectOverwallCostsWarningPercentage }}% @endif
                    @if($projectWageCostsWarningPercentage)Lohnkosten: {{ $projectWageCostsWarningPercentage }}% @endif
                    @if($projectMaterialCostsWarningPercentage)Materialkosten: {{ $projectMaterialCostsWarningPercentage }}% @endif
                </p>
            @endif
        @endif

    </div>
@endsection
