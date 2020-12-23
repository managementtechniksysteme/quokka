<div class="overview-card rounded border-status @if($serviceReport->isNew()) border-primary @elseif($serviceReport->isSigned()) border-warning @else border-success @endif">
    <div class="row align-items-center px-3">

        <div class="col flex-grow-1 h-100 py-3">
            <a class="stretched-link outline-none" href="{{ route('service-reports.show', $serviceReport) }}"></a>
            <div>
                @unless(isset($secondaryInformation) && $secondaryInformation == 'withoutProject'){{ $serviceReport->project->name }} @endif#{{ $serviceReport->number }}
                    ({{ \Carbon\Carbon::parse($serviceReport->services_min_provided_on) }}
                    @if(\Carbon\Carbon::parse($serviceReport->services_min_provided_on)->ne(\Carbon\Carbon::parse($serviceReport->services_max_provided_on)))
                        <svg class="feather feather-16 mx-1">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#arrow-right"></use>
                        </svg>
                        {{ \Carbon\Carbon::parse($serviceReport->services_max_provided_on) }}@endif)
            </div>
            <div class="text-muted">
                <div class="d-flex d-md-inline-flex align-items-center">
                    <svg class="feather feather-16 mr-1">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#user"></use>
                    </svg>
                    {{ $serviceReport->employee->person->name }}
                </div>
                <div class="d-flex d-md-inline-flex align-items-center">
                    <svg class="feather feather-16 ml-lg-2 mr-1">
                        <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#clock"></use>
                    </svg>
                    {{ $serviceReport->services_sum_hours }}
                </div>
            </div>
        </div>

        <div class="col-md-auto d-none d-md-block">
            <div class="dropdown d-inline">
                <button class="btn btn-lg btn-link dropdown-toggle-vertical-points text-muted" type="button" id="companyOverviewDropdown" data-toggle="dropdown"></button>

                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item d-inline-flex align-items-center" href="{{ route('service-reports.edit', $serviceReport) }}">
                        <svg class="feather feather-16 mr-2">
                            <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#edit"></use>
                        </svg>
                        Servicebericht bearbeiten
                    </a>

                    <form action="{{ route('service-reports.destroy', $serviceReport) }}" method="post">
                        @csrf
                        @method('DELETE')

                        <button type="submit" class="dropdown-item dropdown-item-delete d-inline-flex align-items-center">
                            <svg class="feather feather-16 mr-2">
                                <use xlink:href="{{ asset('svg/feather-sprite.svg') }}#trash-2"></use>
                            </svg>
                            Servicebericht entfernen
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>
