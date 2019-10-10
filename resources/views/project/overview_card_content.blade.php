<div class="overview-card rounded">
    <div class="row align-items-center px-3">

        <div class="col flex-grow-1 h-100 py-3">
            <a class="stretched-link outline-none" href="#"></a>
            <p class="m-0">
                {{ $project->name }}
            </p>
            <p class="text-muted d-inline-flex align-items-center m-0">
                @if(isset($secondaryInformation))
                    @switch($secondaryInformation)
                        @case('dates')
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
                            {{ optional($project->company)->name ?? 'keine Firma angegeben' }}
                            @break
                    @endswitch()
                @else
                    <svg class="feather feather-16 mr-1">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#briefcase"></use>
                    </svg>
                    {{ optional($project->company)->name ?? 'keine Firma angegeben' }}
                @endif
            </p>
        </div>

        <div class="col-md-auto d-none d-md-block">
            <div class="dropdown d-inline">
                <button class="btn btn-lg btn-link dropdown-toggle-vertical-points text-muted" type="button" id="companyOverviewDropdown" data-toggle="dropdown"></button>

                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item d-inline-flex align-items-center" href="#">
                        <svg class="feather feather-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#edit"></use>
                        </svg>
                        Projekt bearbeiten
                    </a>

                    <form action="#" method="post">
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
