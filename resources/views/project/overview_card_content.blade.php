<div class="overview-card rounded">
    <div class="row align-items-center px-3">

        <div class="col flex-grow-1 h-100 py-3">
            <a class="stretched-link outline-none" href="{{ route('projects.show', $project) }}"></a>
            <div class="mw-100 text-truncate">
                {{ $project->name }}
            </div>
            <div class="text-muted d-inline-flex align-items-center">
                @if(isset($secondaryInformation))
                    @switch($secondaryInformation)
                        @case('dates')
                            <svg class="feather feather-16 mr-1">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#calendar"></use>
                            </svg>
                            {{ $project->starts_on ?? 'kein Start' }}
                            <svg class="feather feather-16 mx-1">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#arrow-right"></use>
                            </svg>
                            {{ $project->ends_on ?? 'kein Ende' }}
                            @break
                        @default
                            <svg class="feather feather-16 mr-1">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#briefcase"></use>
                            </svg>
                            <span class="mw-100 text-truncate">
                                {{ $project->company->name }}
                            </span>
                            @break
                    @endswitch()
                @else
                    <svg class="feather feather-16 mr-1">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#briefcase"></use>
                    </svg>
                    <span class="mw-100 text-truncate">
                        {{ $project->company->name }}
                    </span>
                @endif
            </div>
        </div>

        <div class="d-none d-sm-block col-sm-auto  text-right">
            <a class="text-muted d-inline-flex align-items-center" href="{{ route('projects.show', [$project, 'tab' => 'tasks']) }}">
                <svg class="feather feather-16 mr-1">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#check-square"></use>
                </svg>
                {{ $project->tasks_count }}
            </a>

            <a class="text-muted d-inline-flex align-items-center ml-2" href="{{ route('projects.show', [$project, 'tab' => 'memos']) }}">
                <svg class="feather feather-16 mr-1">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#voicemail"></use>
                </svg>
                {{ $project->memos_count }}
            </a>

            <a class="text-muted d-inline-flex align-items-center ml-2" href="{{ route('projects.show', [$project, 'tab' => 'service_reports']) }}">
                <svg class="feather feather-16 mr-1">
                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#settings"></use>
                </svg>
                {{ $project->service_reports_count }}
            </a>
        </div>

        <div class="col-md-auto d-none d-md-block">
            <div class="dropdown d-inline">
                <button class="btn btn-lg btn-link dropdown-toggle-vertical-points text-muted" type="button" id="projectOverviewDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>

                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="projectOverviewDropdown">
                    @can('update', $project)
                        <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('projects.edit', $project) }}">
                            <svg class="feather feather-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#edit"></use>
                            </svg>
                            Bearbeiten
                        </a>
                    @endcan
                    @can('email', $project)
                        <a class="dropdown-item d-inline-flex align-items-center" href="#">
                            <svg class="feather feather-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#mail"></use>
                            </svg>
                            Email senden
                        </a>
                    @endcan
                    @can('createPdf', $project)
                        <a class="dropdown-item d-inline-flex align-items-center" href="#">
                            <svg class="feather feather-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#printer"></use>
                            </svg>
                            PDF erstellen
                        </a>
                    @endcan
                    <a class="dropdown-item d-inline-flex align-items-center" href="#">
                        <svg class="feather feather-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#star"></use>
                        </svg>
                        Favorisieren
                    </a>
                    @can('delete', $project)
                        <form action="{{ route('projects.destroy', ['project' => $project, 'redirect' => $actionRedirect ?? 'index']) }}" method="post">
                            @csrf
                            @method('DELETE')

                            <button type="submit" class="dropdown-item dropdown-item-delete d-inline-flex align-items-center">
                                <svg class="feather feather-16 mr-2">
                                    <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#trash-2"></use>
                                </svg>
                                Entfernen
                            </button>
                        </form>
                    @endcan
                </div>
            </div>
        </div>

    </div>
</div>
