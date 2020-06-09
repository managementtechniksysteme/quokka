<div class="overview-card rounded">
    <div class="row align-items-center px-3">

        <div class="col flex-grow-1 h-100 py-3">
            <a class="stretched-link outline-none" href="{{ route('projects.show', $project) }}"></a>
            <div>
                {{ $project->name }}
            </div>
            <div class="text-muted d-inline-flex align-items-center">
                @if(isset($secondaryInformation))
                    @switch($secondaryInformation)
                        @case('dates')
                            <svg class="feather feather-16 mr-1">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#calendar"></use>
                            </svg>
                            {{ $project->starts_on ? $project->starts_on->format('d.m.Y') : 'kein Start angegeben' }}
                            <svg class="feather feather-16 mx-1">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#arrow-right"></use>
                            </svg>
                            {{ $project->ends_on ? $project->ends_on->format('d.m.Y') : 'kein Ende angegeben' }}
                            @break
                        @default
                            <svg class="feather feather-16 mr-1">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#briefcase"></use>
                            </svg>
                            {{ $project->company->name }}
                            @break
                    @endswitch()
                @else
                    <svg class="feather feather-16 mr-1">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#briefcase"></use>
                    </svg>
                    {{ $project->company->name }}
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
        </div>

        <div class="col-md-auto d-none d-md-block">
            <div class="dropdown d-inline">
                <button class="btn btn-lg btn-link dropdown-toggle-vertical-points text-muted" type="button" id="companyOverviewDropdown" data-toggle="dropdown"></button>

                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('projects.edit', $project) }}">
                        <svg class="feather feather-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#edit"></use>
                        </svg>
                        Projekt bearbeiten
                    </a>

                    <form action="{{ route('projects.destroy', $project) }}" method="post">
                        @csrf
                        @method('DELETE')

                        <button type="submit" class="dropdown-item dropdown-item-delete d-inline-flex align-items-center">
                            <svg class="feather feather-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#trash-2"></use>
                            </svg>
                            Projekt entfernen
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>
